<?php
include 'conn.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$order_item_id = $input['order_item_id'];
$new_quantity = $input['quantity'];
$order_id = $input['order_id'];
$newTotalPrice = $input['newTotalPrice'];

try {
    // $conn->beginTransaction();

    // Update quantity in `order_items`
    $updateQuery = "UPDATE order_items SET quantity = :quantity, price = :price WHERE order_item_id = :order_item_id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute(['quantity' => $new_quantity, 'order_item_id' => $order_item_id, 'price' => $newTotalPrice]);
    $stmt = null;

    // Calculate the new total cart amount for the user
    $cartTotalQuery = "SELECT (oi.quantity * p.price) AS total_cart_amount FROM order_items oi, products p WHERE order_id = :order_id AND p.product_id = oi.product_id";
    $stmt = $conn->prepare($cartTotalQuery);
    $stmt->bindParam('order_id', $order_id);
    $stmt->execute();
    $cartTotal = array_sum(array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'total_cart_amount'));
    $stmt = null;

    $updateTotalAmountSql = "UPDATE orders SET total_amount = :cartTotal WHERE order_id = :order_id";
    $stmt = $conn->prepare($updateTotalAmountSql);
    $stmt->bindParam(':cartTotal', $cartTotal);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $stmt = null;
    // $conn->commit();

    echo json_encode(['success' => true, 'newTotalCartAmount' => $cartTotal]);
} catch (Exception $e) {
    // $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
