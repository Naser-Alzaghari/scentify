<?php
class CouponManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCoupons() {
        $stmt = $this->pdo->query("SELECT * FROM coupons WHERE is_deleted = 0");
        return $stmt->fetchAll();
    }

    public function addCoupon($code, $discount, $expirationDate, $limit, $status) {
        $stmt = $this->pdo->prepare("INSERT INTO coupons (coupon_code, discount_percentage, expiration_date, usage_limit, coupon_status) 
                                     VALUES (:code, :discount, :expirationDate, :limit, :status)");
        $stmt->execute([
            'code' => $code,
            'discount' => $discount,
            'expirationDate' => $expirationDate,
            'limit' => $limit,
            'status' => $status
        ]);
        return $stmt->rowCount();
    }

    public function editCoupon($id, $code, $discount, $expirationDate, $limit, $status) {
        $stmt = $this->pdo->prepare("UPDATE coupons SET coupon_code = :code, discount_percentage = :discount, expiration_date = :expirationDate, 
                                     usage_limit = :limit, coupon_status = :status WHERE coupon_id = :id");
        $stmt->execute([
            'code' => $code,
            'discount' => $discount,
            'expirationDate' => $expirationDate,
            'limit' => $limit,
            'status' => $status,
            'id' => $id
        ]);
        return $stmt->rowCount();
    }

    public function softDeleteCoupon($id) {
        $stmt = $this->pdo->prepare("UPDATE coupons SET is_deleted = 1 WHERE coupon_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public function updateExpiredCoupons() {
        $stmt = $this->pdo->prepare("UPDATE coupons SET coupon_status = 0 WHERE expiration_date < CURDATE() AND coupon_status = 1");
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
