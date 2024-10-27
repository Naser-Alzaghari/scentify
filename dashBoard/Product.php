<?php
require 'config.php';

class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addProduct($name, $description, $price, $stock, $category, $image) {
        $stmt = $this->pdo->prepare("INSERT INTO products (product_name, product_description, price, stock_quantity, category_id, product_image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $description, $price, $stock, $category, $image]);
    }

    public function updateProduct($id, $name, $description, $price, $stock, $category, $image) {
        $stmt = $this->pdo->prepare("UPDATE products SET product_name = ?, product_description = ?, price = ?, stock_quantity = ?, category_id = ?, product_image = ?, updated_at = NOW() WHERE product_id = ?");
        $stmt->execute([$name, $description, $price, $stock, $category, $image, $id]);
    }

    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("UPDATE products SET is_deleted = 1 WHERE product_id = ?");
        $stmt->execute([$id]);
    }

    public function getProducts() {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validateImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Error uploading file.";
        }
        if ($file['size'] > $maxFileSize) {
            return "File size exceeds 2MB.";
        }
        if (!in_array($file['type'], $allowedTypes)) {
            return "Invalid file type. Only JPEG, PNG and GIF are allowed.";
        }
        return true;
    }
}
?>
