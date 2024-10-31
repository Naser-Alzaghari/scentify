<?php
require 'config.php';

class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addProduct($name, $description, $price, $stock, $category, $image) {
        $stmt = $this->pdo->prepare("INSERT INTO products (product_name, product_description, price, stock_quantity, category_id, product_image, created_at) 
                                     VALUES (:name, :description, :price, :stock, :category, :image, NOW())");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function updateProduct($id, $name, $description, $price, $stock, $category, $image) {
       
        if (!$this->productExists($id)) {
            throw new Exception("Product not found.");
        }

        $stmt = $this->pdo->prepare("UPDATE products SET product_name = :name, product_description = :description, price = :price, 
                                     stock_quantity = :stock, category_id = :category, product_image = :image, updated_at = NOW() 
                                     WHERE product_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function deleteProduct($id) {

        if (!$this->productExists($id)) {
            throw new Exception("Product not found.");
        }

        $stmt = $this->pdo->prepare("UPDATE products SET is_deleted = 1 WHERE product_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getProducts($limit, $offset) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_deleted = 0 LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM products WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
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

    // دالة للتحقق من وجود المنتج
    private function productExists($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE product_id = :id AND is_deleted = 0");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>
