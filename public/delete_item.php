<?php
include 'conn.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$orderItemId = $input['order_item_id'] ?? null;

$response = ['success' => false];

if ($orderItemId) {
    // Delete item from the order_items table
    $deleteQuery = "DELETE FROM order_items WHERE order_item_id = :order_item_id";
    $stmt = $conn->prepare($deleteQuery);
    
    if ($stmt->execute(['order_item_id' => $orderItemId])) {
        // Recalculate the total cart amount after deletion
        $totalQuery = "SELECT SUM(oi.quantity * p.price) AS totalAmount 
                       FROM order_items oi 
                       JOIN products p ON oi.product_id = p.product_id
                       WHERE oi.on_cart = 1";
        $totalStmt = $conn->prepare($totalQuery);
        $totalStmt->execute();
        $totalAmount = $totalStmt->fetchColumn();
        
        $response['success'] = true;
        $response['newTotalCartAmount'] = $totalAmount ?? 0.00; // Return 0.00 if null
    }
}

echo json_encode($response);
?>
