<?php
require 'Product.php';


$db = new Database();
$pdo = $db->getConnection();

$productManager = new Product($pdo);
$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';


    if (($action == 'delete' || $action == 'edit') && (!isset($_POST['productId']) || !is_numeric($_POST['productId']))) {
        $response['message'] = 'Invalid product ID.';
        echo json_encode($response);
        exit;
    }


    if ($action == 'delete') {
        $productId = (int)$_POST['productId'];
        $productManager->deleteProduct($productId);
        $response = ['status' => 'success', 'message' => 'Product deleted successfully!'];
        echo json_encode($response);
        exit;
    }

  
    $name = isset($_POST['productName']) ? trim($_POST['productName']) : '';
    $description = isset($_POST['productDescription']) ? trim($_POST['productDescription']) : '';
    $price = isset($_POST['productPrice']) ? (float)$_POST['productPrice'] : 0;
    $stock = isset($_POST['productStock']) ? (int)$_POST['productStock'] : 0;
    $category = isset($_POST['productCategory']) ? (int)$_POST['productCategory'] : 0;
    $image = isset($_FILES['productImage']) ? $_FILES['productImage'] : null;

    if (empty($name) || $price <= 0 || $stock < 0 || $category <= 0) {
        $response['message'] = 'Please fill in all required fields correctly.';
        echo json_encode($response);
        exit;
    }

    $imagePath = '';
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $validationResult = $productManager->validateImage($image);
        if ($validationResult !== true) {
            $response['message'] = $validationResult;
            echo json_encode($response);
            exit;
        }

        $uploadDir = '../public/assets/img/gallery/';
        if (!is_dir(filename: $uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uniqueImageName = uniqid() . '-' . basename($image['name']);
        $imagePath = $uploadDir . $uniqueImageName;
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $response['message'] = 'Failed to upload image.';
            echo json_encode($response);
            exit;
        }
    }

    if ($action == 'add') {
        $productManager->addProduct($name, $description, $price, $stock, $category, $uniqueImageName);
        $response = ['status' => 'success', 'message' => 'Product added successfully!'];
    } elseif ($action == 'edit') {
        $id = (int)$_POST['productId'];
        $productManager->updateProduct($id, $name, $description, $price, $stock, $category, $uniqueImageName);
        $response = ['status' => 'success', 'message' => 'Product updated successfully!'];
    }

    echo json_encode($response);
    exit;
}
?>
