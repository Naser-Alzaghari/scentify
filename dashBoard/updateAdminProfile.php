<?php
session_start();
include 'config.php';
include 'ProfileController.php';

if (!isset($_SESSION['admin_user_id'])) {
    header("Location: LoginAdmin.php");
    exit;
}

$user_id = $_SESSION['admin_user_id'];
$database = new Database();
$db = $database->getConnection();
$profileController = new ProfileController($db);

$data = [
    'first_name' => $_POST['firstName'],
    'last_name' => $_POST['lastName'],
    'email' => $_POST['email'],
    'phone' => $_POST['phone'],
    'address' => $_POST['address'],
    'password' => $_POST['password']
];

// معالجة تحديث البيانات
$result = $profileController->updateProfile($user_id, $data, $_FILES);

if ($result === true) {
    header("Location: adminProfile.php?success=1");
} else {
    header("Location: adminProfile.php?error=" . $result);
}
exit;
?>
