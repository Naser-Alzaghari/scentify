<?php
    class Database {
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "scentify";
        private $conn;
    
        public function __construct() {
            try {
                $this->conn = new PDO("mysql:host={$this->servername};dbname={$this->dbname}", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $err) {
                echo "Connection error: " . $err->getMessage();
                $this->conn = null;
            }
        }
    
        public function getConnection() {
            return $this->conn;
        }
    }
?>