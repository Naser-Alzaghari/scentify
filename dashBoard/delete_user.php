<?php
require 'config.php';
require 'UserManager.php';

// التحقق من وجود معرف المستخدم في الطلب والتحقق من أنه رقم صحيح
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    // إنشاء كائن الاتصال بقاعدة البيانات
    $db = new Database();
    $pdo = $db->getConnection();

    // إنشاء كائن UserManager
    $userManager = new UserManager($pdo);

    // Soft Delete المستخدم
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
