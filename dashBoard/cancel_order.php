<?php
require 'config.php';

if (isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ?");
    if ($stmt->execute([$orderId])) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
