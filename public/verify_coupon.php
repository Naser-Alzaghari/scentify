<?php

include 'conn.php';

$coupon = $_GET['coupon'];

$sql = "SELECT coupon_id, discount_percentage, expiration_date, coupon_status, is_deleted FROM coupons WHERE coupon_code = :coupon_code";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':coupon_code', $coupon);
$stmt->execute();
$response_array;
if ( $stmt->rowCount() > 0 ) {
    $couponData = $stmt->fetch(PDO::FETCH_ASSOC);
    $expirationDate = new DateTime($couponData['expiration_date']);
    $currentDate = new DateTime();


    if ($currentDate < $expirationDate && $couponData['coupon_status'] && !$couponData['is_deleted']) {
        // Its valid.
        $response_array['status'] = "success";
        $response_array['is_valid'] = 1;
        $response_array['discount_percentage'] = $couponData['discount_percentage'];
    } else {
        // Its not valid.
        $response_array['status'] = "success";
        $response_array['is_valid'] = 0;
    }
    // verify page
    $lorem="dump";
} else {
    // It does not exist.
    $response_array['status'] = "success";
    $response_array['is_valid'] = 0;
}
echo json_encode($response_array);
exit();