<?php
require 'config.php';
require 'CouponManager.php';

// إنشاء كائن الاتصال بقاعدة البيانات
$db = new Database();
$pdo = $db->getConnection();

// إنشاء كائن CouponManager
$couponObj = new CouponManager($pdo);

// **معالجة طلبات POST**
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // التحقق من جميع المدخلات
    $couponCode = isset($_POST['couponCode']) ? trim($_POST['couponCode']) : '';
    $discountPercentage = isset($_POST['discountPercentage']) ? (int)$_POST['discountPercentage'] : 0;
    $expirationDate = isset($_POST['expirationDate']) ? $_POST['expirationDate'] : '';
    $usageLimit = isset($_POST['usageLimit']) ? (int)$_POST['usageLimit'] : 0;
    $couponStatus = isset($_POST['couponStatus']) ? (int)$_POST['couponStatus'] : 0;

    // التحقق من صيغة اسم الكوبون (يجب أن يكون 4 أحرف ورقمين فقط)
    if (!preg_match('/^[A-Za-z]{4}[0-9]{2}$/', $couponCode)) {
        echo json_encode(['status' => 'error', 'message' => 'Coupon code must consist of exactly 4 letters followed by 2 digits.']);
        exit();
    }

    if (empty($couponCode) || $discountPercentage <= 0 || $usageLimit <= 0 || empty($expirationDate)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required and must be valid.']);
        exit();
    }

    if ($action === 'add') {
        $added = $couponObj->addCoupon($couponCode, $discountPercentage, $expirationDate, $usageLimit, $couponStatus);
        if ($added) {
            $response = ['status' => 'success', 'message' => 'Coupon added successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to add coupon.'];
        }
    } elseif ($action === 'edit') {
        $couponId = isset($_POST['couponId']) ? (int)$_POST['couponId'] : 0;
        if ($couponId <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid coupon ID.']);
            exit();
        }
        $updated = $couponObj->editCoupon($couponId, $couponCode, $discountPercentage, $expirationDate, $usageLimit, $couponStatus);
        if ($updated) {
            $response = ['status' => 'success', 'message' => 'Coupon updated successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to update coupon.'];
        }
    }
    echo json_encode($response);
    exit();
}

// **معالجة الحذف الناعم**
if (isset($_GET['delete'])) {
    $couponId = (int)$_GET['delete'];
    if ($couponId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid coupon ID.']);
        exit();
    }
    $deleted = $couponObj->softDeleteCoupon($couponId);
    if ($deleted) {
        $response = ['status' => 'success', 'message' => 'Coupon has been deleted.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to delete coupon.'];
    }
    echo json_encode($response);
    exit();
}
?>
