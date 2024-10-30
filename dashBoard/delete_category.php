<?php
include_once 'config.php';
include_once 'Category.php';

// التحقق من وجود معرف الفئة في الطلب
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $categoryId = intval($_POST['id']);

    // إنشاء كائن اتصال قاعدة البيانات
    $db = new Database();
    $pdo = $db->getConnection();

    // إنشاء كائن الفئة
    $category = new Category(db: $pdo);

    // استدعاء وظيفة الحذف من الفئة
    $result = $category->softDelete($categoryId);

    if ($result) {
        // إعادة توجيه المستخدم في حال نجاح الحذف
        header('Location: manage-category.php');
        exit;
    } else {
        echo "Error deleting category.";
    }
} else {
    echo "Invalid request. Please provide a valid category ID.";
}
?>
