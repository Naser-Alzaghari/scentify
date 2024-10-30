<?php
class OrderManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // جلب الطلبات مع تحديد العدد والتجاوز
    public function getOrders($limit, $offset) {
        // التحقق من أن limit و offset هي أعداد صحيحة لتجنب الأخطاء أو الهجمات
        $limit = (int) $limit;
        $offset = (int) $offset;

        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE is_deleted = 0 LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        // إعادة النتائج كمصفوفة مرتبطة
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // جلب عدد الطلبات الكلي غير المحذوفة
    public function getOrderCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM orders WHERE is_deleted = 0");
        
        // إعادة النتيجة كمصفوفة مرتبطة واختيار العدد
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
