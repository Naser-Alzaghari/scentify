<?php
class Category {
    private $conn;
    private $table = 'categories';

    public function __construct($db) {
        $this->conn = $db;
    }

    // التحقق مما إذا كانت الفئة موجودة بناءً على الاسم
    public function existsByName($name, $id = 0) {
        // الاستعلام للتحقق من الاسم مع استبعاد المعرف الحالي في حالة التعديل
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE category_name = :name";
        if ($id > 0) {
            $query .= " AND category_id != :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        if ($id > 0) {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function validateImage($file) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2 ميجابايت

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            return "Invalid image format. Allowed formats are: " . implode(', ', $allowedExtensions);
        }

        if ($file['size'] > $maxFileSize) {
            return "Image size exceeds the maximum allowed size of 2MB.";
        }

        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            return "File is not an image.";
        }

        return true;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_deleted = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriesCount() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM " . $this->table . " WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

    public function create($name, $image) {
        if ($this->existsByName($name)) {
            return "Category with this name already exists.";
        }

        $query = "INSERT INTO " . $this->table . " (category_name, image, created_at, updated_at) VALUES (:category_name, :image, :created_at, :updated_at)";
        $stmt = $this->conn->prepare($query);

        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;

        $stmt->bindParam(':category_name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function update($id, $name, $image = null) {
        if ($this->existsByName($name, $id)) {
            return "Category with this name already exists.";
        }

        $query = "UPDATE " . $this->table . " SET category_name = :category_name, updated_at = :updated_at";
        
        if ($image) {
            $query .= ", image = :image";
        }
        
        $query .= " WHERE category_id = :id";
        
        $stmt = $this->conn->prepare($query);
        $updated_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':category_name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
        
        if ($image) {
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function softDelete($id) {
        $query = "UPDATE " . $this->table . " SET is_deleted = 1 WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
