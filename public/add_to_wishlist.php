<?php
session_start();
include 'db_connection.php'; // Include your DB connection here

// Get user_id from the session
$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['product_id'];

// Check if the wishlist already exists for the user
$query = "INSERT INTO wishlists (user_id, created_at, updated_at) VALUES (?, NOW(), NOW()) ON DUPLICATE KEY UPDATE updated_at = NOW()";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();

$wishlistId = $conn->insert_id; // Get the last inserted wishlist ID

// Insert the item into wishlist_items
$query = "INSERT INTO wishlist_items (wishlist_id, product_id, added_at) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $wishlistId, $productId);
$stmt->execute();

$response = ['success' => true];
echo json_encode($response);
?>
