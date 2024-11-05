<?php 
if (!isset($_SESSION)) {
    session_start();
}

include 'conn.php';

// Check if the keys exist in $_POST and assign default values if they are not set
$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : null;
$address = isset($_POST['address']) ? $_POST['address'] : '';
$comments = isset($_POST['comments']) ? $_POST['comments'] : '';
$final_amount = isset($_POST['up_to_date_total_amount']) ? $_POST['up_to_date_total_amount'] : 0;
$productQuantitiesJson = $_POST['total_quantity'];
$productQuantities = json_decode($productQuantitiesJson, true);
print_r($productQuantities);
error_log(print_r($productQuantities, true));

echo $final_amount;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    try {
        $sql = "SELECT order_items.product_id, quantity, stock_quantity FROM `order_items` JOIN products on order_items.product_id = products.product_id Where order_id = :order_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("order_id", $order_id);
        $stmt->execute();
        $products_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allAvailable = true;

        foreach ($products_stock as $item) {
            if ($item["quantity"] > $item["stock_quantity"]) {
                $allAvailable = false;
                break;
            }
        }
        echo $allAvailable;
        if ($allAvailable) {
            $sql = "UPDATE `orders` SET `shipping_address` = :shipping_address, `comments` = :comments, `total_amount` = :total_amount, `order_status` = 'processing', `order_checkout_date` = NOW() WHERE `user_id` = :user_id AND `order_id` = :order_id";
        $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':shipping_address', $address);
        $stmt->bindParam(':total_amount', $final_amount);
        $stmt->bindParam(':comments', $comments);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        // Update `order_items` table
        $sql = "UPDATE order_items SET on_cart = 0 WHERE order_id = :order_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        // Update stock quantities in `products` table
        $updateQuery = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE product_id = :product_id";
        $stmt = $conn->prepare($updateQuery);

        // Loop through the associative array and execute the update query for each product
        foreach ($productQuantities as $productId => $quantity) {
            // Bind the parameters
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();
        }

        echo "Stock quantities updated successfully!";

        // Redirect to checkout complete page
        header('Location: thank_you.php?success=1');
        exit(); // Add exit to prevent further code execution after redirect
        } else {
            $_SESSION['stock_limit'] = "stock exeed limit";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        
        // Use a prepared statement to update the `orders` table
        $sql = "UPDATE `orders` SET `shipping_address` = :shipping_address, `comments` = :comments, `total_amount` = :total_amount, `order_status` = 'processing', `order_checkout_date` = NOW() WHERE `user_id` = :user_id AND `order_id` = :order_id";
        $stmt = $conn->prepare($sql);
        


        

    } catch (PDOException $e) {
        // Handle any errors
        error_log("Error: " . $e->getMessage());
        echo "An error occurred while processing your request. Please try again later.";
    }

} else {
    echo "User not set";
}

echo "address: " . $address . " comments: " . $comments;
?>
