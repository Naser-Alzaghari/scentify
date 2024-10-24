<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    session_start();
    $_SESSION['user_id']=1;
    $product_id = $_POST['add_item_id'];
    $quantity = $_POST['quantity'];
    echo "product_id: $product_id";
    echo "<br>";
    echo "quantity: $quantity";
    echo "<br><br><br>";
    
    include "conn.php";
    $query="SELECT * FROM `products` WHERE product_id = :product_id";
    $statment=$conn->prepare($query);
    $statment->bindParam(':product_id',$product_id,PDO::PARAM_INT);
    $statment->execute();
    $product = $statment->fetch(PDO::FETCH_ASSOC);
    print_r($product);
    echo "<br><br><br>";
    $query="SELECT Max(orders.order_id) as last_order, MAX(on_cart) as cart_not_empty FROM `order_items` JOIN `orders` on order_items.order_id = orders.order_id WHERE user_id = :user_id";
    $statment=$conn->prepare($query);
    $statment->bindParam(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
    $statment->execute();
    $last_order = $statment->fetch(PDO::FETCH_ASSOC);
    print_r($last_order);
    if ($last_order["cart_not_empty"]){
        $order_id=$last_order['last_order'];
        echo "lastorder is $order_id";
    } else {
        $sql ="INSERT INTO `orders`(`user_id`) VALUES (:user_id);";
        $statment=$conn->prepare($sql);
        $statment->bindParam(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $statment->execute();
        $sql = "SELECT MAX(order_id) AS last_order FROM orders;";
        $statment=$conn->prepare($sql);
        $statment->execute();
        $last_order = $statment->fetch(PDO::FETCH_ASSOC);
        echo "new order ";
        $order_id=$last_order['last_order'];
        print_r($order_id);
    }
    $sql ="INSERT INTO `order_items`(`order_id`, `product_id`, `quantity`, `price`) VALUES (:order_id,:product_id,:quantity,:price)";
    $statment=$conn->prepare($sql);
    $statment->bindParam(':order_id',$order_id,PDO::PARAM_INT);
    $statment->bindParam(':product_id',$product_id,PDO::PARAM_INT);
    $statment->bindParam(':quantity',$quantity,PDO::PARAM_INT);
    $statment->bindParam(':price',$product['price']);
    print_r($statment);
    echo $last_order['last_order']."<br>";
    echo $product_id."<br>";
    echo $quantity."<br>";
    echo $product['price']."<br>";
    $statment->execute();
    ?>
</body>
</html>