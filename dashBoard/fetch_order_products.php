<?php
require 'config.php';

if (isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
    // Make sure order_id is an integer
    $orderId = intval($_POST['order_id']); 


    $db = new Database();
    $pdo = $db->getConnection();

  
    $stmt = $pdo->prepare("SELECT p.product_name, oi.quantity, oi.price 
                           FROM order_items oi 
                           JOIN products p ON oi.product_id = p.product_id 
                           WHERE oi.order_id = ?;");

   // Check implementation and pass only one parameter
    if ($stmt->execute([$orderId])) {
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        
        if (!empty($products)) {
            $totalPrice = 0; 
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
               
                $productTotal = $product['price'];
                $totalPrice += $productTotal; 

                echo '<tr>
                        <td>' . htmlspecialchars($product['product_name']) . '</td>
                        <td>' . htmlspecialchars($product['quantity']) . '</td>
                        <td>' . htmlspecialchars(number_format($productTotal, 2)) . '</td>
                      </tr>';
            }
           
            
            echo '</tbody></table>';
        } else {
            echo "No products found for order ID: " . htmlspecialchars($orderId);
        }
    } else {
        // عرض خطأ في تنفيذ الاستعلام
        echo "Query execution failed: " . htmlspecialchars(implode(", ", $stmt->errorInfo()));
    }
} else {
    echo "Invalid order ID.";
}
?>
