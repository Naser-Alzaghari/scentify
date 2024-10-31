<?php
include 'conn.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$order_item_id = $input['order_item_id'];
$new_quantity = $input['quantity'];

try {
    $conn->beginTransaction();

    // Update quantity in `order_items`
    $updateQuery = "UPDATE order_items SET quantity = :quantity WHERE order_item_id = :order_item_id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute(['quantity' => $new_quantity, 'order_item_id' => $order_item_id]);

    // Calculate the new total price for the updated item
    $itemQuery = "SELECT quantity, price FROM order_items WHERE order_item_id = :order_item_id";
    $stmt = $conn->prepare($itemQuery);
    $stmt->execute(['order_item_id' => $order_item_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    $newTotalPrice = $item['quantity'] * $item['price'];

    // Calculate the new total cart amount for the user
    $cartTotalQuery = "SELECT SUM(quantity * price) AS total_cart_amount FROM order_items WHERE on_cart = 1";
    $stmt = $conn->prepare($cartTotalQuery);
    $stmt->execute();
    $cartTotal = $stmt->fetch(PDO::FETCH_ASSOC)['total_cart_amount'];

    $conn->commit();

    echo json_encode(['success' => true, 'newTotalCartAmount' => $cartTotal]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
