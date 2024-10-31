<?php
class OrderManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch requests with specified number and override
    public function getOrders($limit, $offset) {
      // Check that limit and offset are integers to avoid bugs or attacks
        $limit = (int) $limit;
        $offset = (int) $offset;

        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE is_deleted = 0 LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrderCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM orders WHERE is_deleted = 0");
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
