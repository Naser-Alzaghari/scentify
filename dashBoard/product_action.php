<?php
require 'Product.php';

$productManager = new Product($pdo);
$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'delete') {
        $productId = $_POST['productId'];
        $productManager->deleteProduct($productId);
        $response = ['status' => 'success', 'message' => 'Product deleted successfully!'];
        echo json_encode($response);
        exit;
    }

    $name = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];
    $category = $_POST['productCategory'];
    $image = $_FILES['productImage'];

    // Validate the image
    $validationResult = $productManager->validateImage($image);
    if ($validationResult !== true) {
        $response['message'] = $validationResult;
    } else {
        $imagePath = "uploads/" . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);

        if ($action == 'add') {
            $productManager->addProduct($name, $description, $price, $stock, $category, $imagePath);
            $response = ['status' => 'success', 'message' => 'Product added successfully!'];
        } elseif ($action == 'edit') {
            $id = $_POST['productId'];
            $productManager->updateProduct($id, $name, $description, $price, $stock, $category, $imagePath);
            $response = ['status' => 'success', 'message' => 'Product updated successfully!'];
        }
    }

    echo json_encode($response);
    exit;
}
?>
