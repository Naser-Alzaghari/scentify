<?php
require 'config.php';

// Create an instance of Database and get the PDO connection
$db = new Database();
$pdo = $db->getConnection();

if (isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    
    // Prepare the SQL statement using named parameters for clarity and security
    $stmt = $pdo->prepare("UPDATE orders SET order_status = :status WHERE order_id = :order_id");
    
    // Execute the statement with an associative array of named parameters
    if ($stmt->execute([':status' => 'completed', ':order_id' => $orderId])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    
} else {
    // Handle case where 'order_id' is not set
    echo json_encode(['status' => 'error', 'message' => 'Order ID is missing']);
}
?>
