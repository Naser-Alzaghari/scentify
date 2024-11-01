<?php
    session_start();
    $product_id = $_POST['add_item_id'];
    $quantity = $_POST['quantity'];
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $_SESSION["product_id"] = $product_id;
        header("location: LoginPage.php"); // not logiedin
    }
    echo "product_id: $product_id";
    echo "<br>";
    echo "quantity: $quantity";
    echo "<br><br><br>";

    include "conn.php";

    // Get product data from products table
    $query = "SELECT * FROM `products` WHERE product_id = :product_id";
    $statment = $conn->prepare($query);
    $statment->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statment->execute();
    $product = $statment->fetch(PDO::FETCH_ASSOC);
    echo "<br><br><br>";

    // Get last order number and check if cart is empty
    $query = "SELECT Max(orders.order_id) as last_order, MAX(on_cart) as cart_not_empty FROM `order_items` JOIN `orders` on order_items.order_id = orders.order_id WHERE user_id = :user_id";
    $statment = $conn->prepare($query);
    $statment->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statment->execute();
    $last_order = $statment->fetch(PDO::FETCH_ASSOC);
    print_r($last_order);

    // If cart is empty, create a new order; otherwise, get the last order
    if ($last_order["cart_not_empty"]) {
        $order_id = $last_order['last_order'];
        echo "lastorder is $order_id";
    } else {
        $sql = "INSERT INTO `orders`(`user_id`, `is_deleted`) VALUES (:user_id, 0);";
        $statment = $conn->prepare($sql);
        $statment->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statment->execute();
        $sql = "SELECT MAX(order_id) AS last_order FROM orders;";
        $statment = $conn->prepare($sql);
        $statment->execute();
        $last_order = $statment->fetch(PDO::FETCH_ASSOC);
        echo "new order ";
        $order_id = $last_order['last_order'];
        print_r($order_id);
    }

    // Check if the item already exists in the cart for the same order
    $sql = "SELECT quantity FROM `order_items` WHERE order_id = :order_id AND product_id = :product_id";
    $statement = $conn->prepare($sql);
    $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();
    $existingItem = $statement->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // If the item already exists, update the quantity
        $newQuantity = $existingItem['quantity'] + $quantity;
        $updateSql = "UPDATE `order_items` SET quantity = :new_quantity WHERE order_id = :order_id AND product_id = :product_id";
        $updateStatement = $conn->prepare($updateSql);
        $updateStatement->bindParam(':new_quantity', $newQuantity, PDO::PARAM_INT);
        $updateStatement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $updateStatement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $updateStatement->execute();
    } else {
        // If the item does not exist, insert a new record
        $insertSql = "INSERT INTO `order_items`(`order_id`, `product_id`, `quantity`, `price`) VALUES (:order_id, :product_id, :quantity, :price)";
        $insertStatement = $conn->prepare($insertSql);
        $price = $product['price'] * $quantity;
        $insertStatement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $insertStatement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $insertStatement->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $insertStatement->bindParam(':price', $price);
        $insertStatement->execute();
    }

    // Calculate total price for order
    $sql = "SELECT SUM(price) as total_amount FROM `order_items` JOIN `orders` on order_items.order_id = orders.order_id WHERE user_id = :user_id and on_cart = 1;";
    $statment = $conn->prepare($sql);
    $statment->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statment->execute();
    $total_amount = $statment->fetch(PDO::FETCH_ASSOC);
    echo $total_amount['total_amount'];

    // Update total price for the order
    $sql = "UPDATE `orders` SET `total_amount`= :total_amount WHERE order_id = :order_id";
    $statment = $conn->prepare($sql);
    $statment->bindParam(':total_amount', $total_amount['total_amount']);
    $statment->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $statment->execute();

    $_SESSION['added_item'] = $product["product_name"];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
    ?>