<?php
require 'config.php';

if (isset($_POST['order_id'])) {
    // تأكد من أن قيمة order_id هي عدد صحيح
    $orderId = intval($_POST['order_id']); 

    // إعداد الاستعلام مع الربط بين الجداول
    $stmt = $pdo->prepare("SELECT p.product_name, oi.quantity, oi.price 
                           FROM order_items oi 
                           JOIN products p ON oi.product_id = p.product_id 
                           WHERE oi.order_id = ?;");

    // التحقق من التنفيذ وتمرير معلمة واحدة فقط
    if ($stmt->execute([$orderId])) {
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // الحصول على النتائج كمصفوفة مرتبطة

        if (!empty($products)) {
            $totalPrice = 0; // متغير لحفظ مجموع الأسعار
            echo '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach ($products as $product) {
                // حساب السعر الإجمالي للمنتج (الكمية * السعر)
                $productTotal = $product['quantity'] * $product['price'];
                $totalPrice += $productTotal; // إضافة السعر الإجمالي للمنتج إلى مجموع الأسعار

                echo '<tr>
                        <td>' . htmlspecialchars($product['product_name']) . '</td>
                        <td>' . htmlspecialchars($product['quantity']) . '</td>
                        <td>' . htmlspecialchars(number_format($productTotal, 2)) . '</td>
                      </tr>';
            }
            // إظهار مجموع الأسعار في نهاية الجدول
            echo '<tr>
                    <td colspan="2"><strong>Total Price</strong></td>
                    <td><strong>' . htmlspecialchars(number_format($totalPrice, 2)) . '</strong></td>
                  </tr>';
            echo '</tbody></table>';
        } else {
            echo "No products found for order ID: " . htmlspecialchars($orderId);
        }
    } else {
        // عرض خطأ في تنفيذ الاستعلام
        echo "Query execution failed: " . implode(", ", $stmt->errorInfo());
    }
}
?>
