<?php 

session_start();
include 'conn.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $address = $_POST['address'];
    $comments = $_POST['comments'];
    $order_id=$_POST['order_id'];

}


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
$sql = "UPDATE `orders` SET `shipping_address` = :shipping_address, `comments` = :comments WHERE `user_id` = :user_id AND `order_id` = :order_id";
$statement = $conn->prepare($sql);

$statement->bindParam(':user_id', $user_id);
$statement->bindParam(':shipping_address', $address);
$statement->bindParam(':comments', $comments);
$statement->bindParam(':order_id', $order_id);

$statement->execute();
header('Location: index.php?success=1');

}

echo "address" . $address . " comments" . $comments;


?>