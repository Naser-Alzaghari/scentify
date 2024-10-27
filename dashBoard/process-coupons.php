<?php
require 'config.php';
require 'CouponManager.php';

$couponObj = new CouponManager($pdo);

// **Processing POST Requests**
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $added = $couponObj->addCoupon($_POST['couponCode'], $_POST['discountPercentage'], $_POST['expirationDate'], $_POST['usageLimit'], $_POST['couponStatus']);
        if ($added) {
            $response = ['status' => 'success', 'message' => 'Coupon added successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to add coupon.'];
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $updated = $couponObj->editCoupon($_POST['couponId'], $_POST['couponCode'], $_POST['discountPercentage'], $_POST['expirationDate'], $_POST['usageLimit'], $_POST['couponStatus']);
        if ($updated) {
            $response = ['status' => 'success', 'message' => 'Coupon updated successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to update coupon.'];
        }
    }
    echo json_encode($response);
    exit();
}

// **Soft Delete Handling**
if (isset($_GET['delete'])) {
    $deleted = $couponObj->softDeleteCoupon($_GET['delete']);
    if ($deleted) {
        $response = ['status' => 'success', 'message' => 'Coupon has been deleted.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to delete coupon.'];
    }
    echo json_encode($response);
    exit();
}
?>
