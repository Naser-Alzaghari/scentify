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
        $query = "SELECT COUNT(*) FROM categories WHERE category_name = :name";
        if ($id > 0) {
            $query .= " AND category_id != :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        if ($id > 0) {
            $stmt->bindParam(':id', $id);
        }
        $stmt->execute();

        // إذا كانت النتيجة أكبر من صفر، فهذا يعني أن الاسم موجود
        return $stmt->fetchColumn() > 0;
    }

    // التحقق من صحة الصورة
    public function validateImage($file) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2 ميجابايت

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // التحقق من أن الامتداد مسموح به
        if (!in_array($fileExtension, $allowedExtensions)) {
            return "Invalid image format. Allowed formats are: " . implode(', ', $allowedExtensions);
        }

        // التحقق من حجم الملف
        if ($file['size'] > $maxFileSize) {
            return "Image size exceeds the maximum allowed size of 2MB.";
        }

        // التحقق من كون الملف صورة
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            return "File is not an image.";
        }

        return true;
    }

    // إحضار جميع الفئات التي لم يتم حذفها
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_deleted = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // إنشاء فئة جديدة
    public function create($name, $image) {
        if ($this->existsByName($name)) {
            return "Category with this name already exists.";
        }

        $query = "INSERT INTO " . $this->table . " (category_name, image, created_at, updated_at) VALUES (:category_name, :image, :created_at, :updated_at)";
        $stmt = $this->conn->prepare($query);

        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;

        $stmt->bindParam(':category_name', $name);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':updated_at', $updated_at);

        return $stmt->execute();
    }

    // تحديث فئة
    public function update($id, $name, $image = null) {
        if ($this->existsByName($name, $id)) {
            return "Category with this name already exists.";
        }
    
        // إعداد الاستعلام لتحديث الفئة
        $query = "UPDATE " . $this->table . " SET category_name = :category_name, updated_at = :updated_at";
        
        // إضافة تحديث الصورة إذا تم تمرير صورة جديدة
        if ($image) {
            $query .= ", image = :image";
        }
        
        $query .= " WHERE category_id = :id";
        
        $stmt = $this->conn->prepare($query);
        $updated_at = date('Y-m-d H:i:s');
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':category_name', $name);
        $stmt->bindParam(':updated_at', $updated_at);
        
        if ($image) {
            $stmt->bindParam(':image', $image);
        }
    
        return $stmt->execute();
    }

    // حذف فئة بشكل ناعم (soft delete)
    public function softDelete($id) {
        $query = "UPDATE " . $this->table . " SET is_deleted = 1 WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
