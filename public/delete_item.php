<?php
include 'conn.php';
if (!isset($_SESSION)){
    session_start(); // Make sure the session is started
}

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$orderItemId = $input['order_item_id'] ?? null;

$response = ['success' => false];

if ($orderItemId) {
    // Prepare the delete query
    $deleteQuery = "DELETE FROM order_items WHERE order_item_id = :order_item_id";
    $stmt = $conn->prepare($deleteQuery);

    if ($stmt->execute(['order_item_id' => $orderItemId])) {
        // Recalculate the total cart amount after deletion
        $totalQuery = "SELECT SUM(oi.quantity * p.price) AS totalAmount 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.product_id 
                        JOIN orders o ON oi.order_id = o.order_id 
                        WHERE oi.on_cart = 1 
                        AND o.user_id = :user_id";

        // Prepare the total amount query with parameter binding
        $totalStmt = $conn->prepare($totalQuery);
        $totalStmt->execute(['user_id' => $_SESSION['user_id']]);
        $totalAmount = $totalStmt->fetchColumn();

        $response['success'] = true;
        $response['newTotalCartAmount'] = $totalAmount;
    }
}

echo json_encode($response);
?>