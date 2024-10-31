<?php
require 'config.php';
require 'UserManager.php';

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $db = new Database();
    $pdo = $db->getConnection();

    $userManager = new UserManager($pdo);

    $user_id = intval($_GET['user_id']);
    $result = $userManager->softDeleteUser($user_id);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting user.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
}
?>
