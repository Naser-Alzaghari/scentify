<?php
require_once 'config.php';

$database = new Database();
$pdo = $database->getConnection();

class Dashboard
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get the total number of clients
    public function getNumberOfClients()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total_clients FROM users WHERE role = 'user'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total_clients'] : 0;
    }

    // Get order details by month (number of orders per month)
    public function getOrderDetails()
    {
        $stmt = $this->pdo->query("SELECT MONTH(created_at) AS month, COUNT(order_id) AS total_orders FROM orders GROUP BY MONTH(created_at)");
        $orderDetails = array_fill(0, 12, 0); // Initialize array for 12 months

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orderDetails[(int)$row['month'] - 1] = (int)$row['total_orders'];
        }

        return $orderDetails;
    }

    // Get sales report by month (total sales per month)
    public function getSalesReport()
    {
        $stmt = $this->pdo->query("SELECT MONTH(created_at) AS month, IFNULL(SUM(total_amount), 0) AS total_sales FROM orders WHERE order_status = 'completed' GROUP BY MONTH(created_at)");
        $salesReport = array_fill(0, 12, 0); // Initialize array for 12 months

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $salesReport[(int)$row['month'] - 1] = (float)$row['total_sales'];
        }

        return $salesReport;
    }

    // Get the distribution of users by country
    public function getUserCountries()
    {
        $stmt = $this->pdo->query("SELECT address AS country, COUNT(user_id) AS total FROM users GROUP BY address");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get top 3 products by quantity sold
    public function getTopProducts()
    {
        $stmt = $this->pdo->query("SELECT p.product_name, SUM(oi.quantity) AS total_sold
                                   FROM order_items oi
                                   JOIN products p ON oi.product_id = p.product_id
                                   GROUP BY p.product_id
                                   ORDER BY total_sold DESC
                                   LIMIT 3");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Fetch all data needed for the dashboard
    public function getDashboardData()
    {
        return [
            'clients' => $this->getNumberOfClients(),
            'orderDetails' => $this->getOrderDetails(),
            'salesReport' => $this->getSalesReport(),
            'userCountries' => $this->getUserCountries(),
            'topProducts' => $this->getTopProducts()
        ];
    }
}

// Instantiate the Dashboard class and get the data
try {
    $dashboard = new Dashboard($pdo);
    $data = $dashboard->getDashboardData();

    // Set header for JSON response and output the data
    header('Content-Type: application/json');
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
