<?php
include_once 'config.php';
include_once 'Category.php';

// إنشاء اتصال بقاعدة البيانات وإنشاء كائن الفئة
$db = new Database();
$pdo = $db->getConnection();
$category = new Category($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0; // Ensure ID is an integer
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $image = '';

    // التحقق من أن الاسم ليس فارغًا
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required.']);
        exit();
    }

    // تحقق من وجود فئة بنفس الاسم
    if ($category->existsByName($name, $id)) {
        echo json_encode(['success' => false, 'message' => 'Category name already exists.']);
        exit();
    }

    // تحقق من صحة الصورة وتحميلها إذا تم رفع صورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $validationResult = $category->validateImage($_FILES['image']);
        if ($validationResult !== true) {
            echo json_encode(['success' => false, 'message' => $validationResult]);
            exit();
        }

        $uploadDir = '../public/assets/img/gallery/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // استخدام basename لأمان أفضل عند تحديد اسم الملف
        $uniqueImageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $image = $uploadDir . $uniqueImageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            exit();
        }
    } else if ($id) {
        // إذا كان هناك معرّف وتم التعديل دون رفع صورة، جلب الصورة الحالية من قاعدة البيانات
        $existingCategory = $category->getById($id);
        $image = $existingCategory['image'];
    }

    // إذا كان هناك معرّف، قم بالتحديث وإلا قم بإدراج فئة جديدة
    if ($id) {
        $success = $category->update($id, $name, $uniqueImageName);
    } else {
        $success = $category->create($name, $uniqueImageName);
    }

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Category saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save category.']);
    }
    exit();
}
?>
