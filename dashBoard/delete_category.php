<?php
include_once 'config.php';
include_once 'Category.php';

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $categoryId = intval($_POST['id']);


    $db = new Database();
    $pdo = $db->getConnection();


    $category = new Category(db: $pdo);

    
    $result = $category->softDelete($categoryId);

    if ($result) {
       
        header('Location: manage-category.php');
        exit;
    } else {
        echo "Error deleting category.";
    }
} else {
    echo "Invalid request. Please provide a valid category ID.";
}
?>
