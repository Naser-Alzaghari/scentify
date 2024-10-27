<?php
require 'config.php';
require 'UserManager.php';

if (isset($_GET['user_id'])) {
    $userManager = new UserManager($pdo);
    
    // Soft Delete المستخدم
    $user_id = $_GET['user_id'];
    $userManager->softDeleteUser($user_id);

    echo json_encode(['success' => true]);
}
?>
