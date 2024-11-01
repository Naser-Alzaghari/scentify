<?php 

session_start();
include 'conn.php';


$address = $_POST['address'];
$comments = $_POST['comments'];
$order_id=$_POST['order_id'];
$final_amount = $_POST['up_to_date_total_amount'];

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
$sql = "UPDATE `orders` SET `shipping_address` = :shipping_address, `comments` = :comments, total_amount = :total_amount, order_status = 'processing', order_checkout_date = NOW() WHERE `user_id` = :user_id AND `order_id` = :order_id";
$stmt = $conn->prepare($sql);

$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':shipping_address', $address);
$stmt->bindParam(':total_amount', $final_amount);
$stmt->bindParam(':comments', $comments);
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$stmt = null;

$sql = "UPDATE order_items SET on_cart = 0 WHERE order_id = :order_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();



header('Location: checkoutComplete.php?success=1');

}

echo "address" . $address . " comments" . $comments;


?>