<?php
session_start();
include 'db_connection.php'; // Include your DB connection here

// Get user_id from the session
$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['product_id'];

// Get the wishlist_id for the user
$query = "SELECT wishlist_id FROM wishlists WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
