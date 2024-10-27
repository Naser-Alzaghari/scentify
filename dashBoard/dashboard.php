<?php
// dashboard.php

require_once 'config.php'; // Include your PDO connection settings

class Dashboard
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get number of clients (customers)
    public function getNumberOfClients()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total_clients FROM users WHERE role = 'user'");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_clients'];
    }

    // Get order details (number of orders by month)
    public function getOrderDetails()
    {
        $stmt = $this->pdo->query("SELECT MONTH(created_at) AS month, COUNT(order_id) AS total_orders FROM orders GROUP BY MONTH(created_at)");
        $orderDetails = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orderDetails[] = $row['total_orders'];
        }
        return $orderDetails;
    }

    // Get sales report (total sales by month)
    public function getSalesReport()
    {
        $stmt = $this->pdo->query("SELECT MONTH(created_at) AS month, SUM(total_amount) AS total_sales FROM orders WHERE order_status = 'completed' GROUP BY MONTH(created_at)");
        $salesReport = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $salesReport[] = [
                'month' => $row['month'],
                'total_sales' => $row['total_sales']
            ];
        }
        return $salesReport;
    }

    // Get user countries (distribution of users by country)
    public function getUserCountries()
    {
        $stmt = $this->pdo->query("SELECT address, COUNT(user_id) AS total FROM users GROUP BY address");
        $userCountries = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userCountries[] = [
                'country' => $row['address'],
                'total' => $row['total']
            ];
        }
        return $userCountries;
    }

    // Get top products (top 3 products by quantity sold)
    public function getTopProducts()
    {
        $stmt = $this->pdo->query("SELECT p.product_name, SUM(oi.quantity) AS total_sold
                                   FROM order_items oi
                                   JOIN products p ON oi.product_id = p.product_id
                                   GROUP BY p.product_id
                                   ORDER BY total_sold DESC
                                   LIMIT 3");
        $topProducts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $topProducts[] = [
                'product_name' => $row['product_name'],
                'total_sold' => $row['total_sold']
            ];
        }
        return $topProducts;
    }

    // Fetch all data needed for the dashboard
    public function getDashboardData()
    {
        return [
            'clients' => $this->getNumberOfClients(),
            'orderDetails' => ['total_orders' => $this->getOrderDetails()],
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

    // Send the response back as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
