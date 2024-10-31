<?php
require 'config.php'; 
require 'UserManager.php';
session_start();

header('Content-Type: application/json'); 

// Create an instance of Database and get the PDO connection
$db = new Database();
$pdo = $db->getConnection();

$userManager = new UserManager($pdo);

// Check if 'user_role' exists in the session to avoid an undefined array key warning
if (isset($_SESSION['user_role'])) {
    $currentUserRole = $_SESSION['user_role'];
} else {
    // Handle the case when the role is not set, you may want to redirect to the login page or set a default
    $currentUserRole = 'user'; // Or handle appropriately
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read data from the form
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birth_of_date = $_POST['birth_of_date'];
    $address = $_POST['address'];
    $role = ($currentUserRole === 'super_admin') ? $_POST['role'] : 'user'; // Only super admin can update role

    // Update user and validate data
    $result = $userManager->updateUser($user_id, $first_name, $last_name, $email, $phone_number, $birth_of_date, $address, $role);

    if ($result === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'errors' => $result]);
    }
}
?>
