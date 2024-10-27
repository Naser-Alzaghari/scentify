<?php
include_once 'config.php';
include_once 'Category.php';

if (isset($_POST['id'])) {
    $categoryId = $_POST['id'];
    $category = new Category($pdo);

    // استدعاء وظيفة الحذف من الفئة
    $result = $category->softDelete($categoryId);

    if ($result) {
        header('Location: manage-category.php');
        exit;
    } else {
        echo "Error deleting category.";
    }
} else {
    echo "Invalid request.";
}
?>
