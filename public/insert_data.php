<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $product_id = $_POST['add_item_id'];
    $quantity = $_POST['quantity'];
    echo "product_id: $product_id";
    echo "<br>";
    echo "quantity: $quantity";
    include "conn.php";
    $query="SELECT * FROM `products` WHERE product_id = :product_id";
    $statment=$conn->prepare($query);
    $statment->bindParam(':product_id',$product_id,PDO::PARAM_INT);
    $statment->execute();
    $product = $statment->fetch(PDO::FETCH_ASSOC);
    echo "<br><br><br>";
    print_r($product);
    

    $sql = "INSERT INTO `order_items`(`order_id`, `product_id`, `quantity`, `price`, `on_cart`) VALUES (:order_id, :product_id, :quantity, :price, :on_cart)";
    $statment = $conn->prepare($sql);
    $data=[
        // 'order_id' => $name,
        'product_id' => $product_id,
        'quantity' => $quantity, 
        'price' => $product['price'], 
        'on_cart' => true, 
    ];
    ?>
</body>
</html>