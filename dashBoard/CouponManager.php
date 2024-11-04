<?php
class CouponManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

   
    public function getAllCoupons() {
        $stmt = $this->pdo->query("SELECT * FROM coupons WHERE is_deleted = 0");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 
    public function getCouponsCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM coupons WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

 
    public function addCoupon($code, $discount, $expirationDate, $status) {
        $stmt = $this->pdo->prepare("INSERT INTO coupons (coupon_code, discount_percentage, expiration_date, coupon_status) 
                                     VALUES (:code, :discount, :expirationDate, :status)");
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':discount', $discount, PDO::PARAM_INT);
        $stmt->bindParam(':expirationDate', $expirationDate, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }


    public function editCoupon($id, $code, $discount, $expirationDate, $status) {
        $stmt = $this->pdo->prepare("UPDATE coupons SET coupon_code = :code, discount_percentage = :discount, expiration_date = :expirationDate, 
                                      coupon_status = :status WHERE coupon_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':discount', $discount, PDO::PARAM_INT);
        $stmt->bindParam(':expirationDate', $expirationDate, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

 
    public function softDeleteCoupon($id) {
        $stmt = $this->pdo->prepare("UPDATE coupons SET is_deleted = 1 WHERE coupon_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateExpiredCoupons() {
        $stmt = $this->pdo->prepare("UPDATE coupons SET coupon_status = 0 WHERE expiration_date < CURDATE() AND coupon_status = 1");
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
