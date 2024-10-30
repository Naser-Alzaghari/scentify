<?php
class CouponManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // إحضار جميع الكوبونات غير المحذوفة
    public function getAllCoupons() {
        $stmt = $this->pdo->query("SELECT * FROM coupons WHERE is_deleted = 0");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // إحضار عدد الكوبونات غير المحذوفة
    public function getCouponsCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM coupons WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // إضافة كوبون جديد
    public function addCoupon($code, $discount, $expirationDate, $limit, $status) {
        $stmt = $this->pdo->prepare("INSERT INTO coupons (coupon_code, discount_percentage, expiration_date, usage_limit, coupon_status) 
                                     VALUES (:code, :discount, :expirationDate, :limit, :status)");
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':discount', $discount, PDO::PARAM_INT);
        $stmt->bindParam(':expirationDate', $expirationDate, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    // تعديل الكوبون
    public function editCoupon($id, $code, $discount, $expirationDate, $limit, $status) {
        $stmt = $this->pdo->prepare("UPDATE coupons SET coupon_code = :code, discount_percentage = :discount, expiration_date = :expirationDate, 
                                     usage_limit = :limit, coupon_status = :status WHERE coupon_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':discount', $discount, PDO::PARAM_INT);
        $stmt->bindParam(':expirationDate', $expirationDate, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    // حذف كوبون بشكل ناعم (soft delete)
    public function softDeleteCoupon($id) {
        $stmt = $this->pdo->prepare("UPDATE coupons SET is_deleted = 1 WHERE coupon_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // تحديث حالة الكوبونات المنتهية الصلاحية
    public function updateExpiredCoupons() {
        $stmt = $this->pdo->prepare("UPDATE coupons SET coupon_status = 0 WHERE expiration_date < CURDATE() AND coupon_status = 1");
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
