<?php
include_once 'config.php';
include_once 'Category.php';

$category = new Category($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0; // Ensure ID is an integer
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $image = '';

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

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

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
        $success = $category->update($id, $name, $image);
    } else {
        $success = $category->create($name, $image);
    }

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Category saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save category.']);
    }
    exit();
}
