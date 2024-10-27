<?php
class OrderManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getOrders() {
        $stmt = $this->pdo->query("SELECT * FROM orders WHERE is_deleted = 0");
        return $stmt->fetchAll();
    }
}
?>
