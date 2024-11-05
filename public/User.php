<?php
// session_start();
class User {
    private $conn;
    private $table_name = "users";
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $address;
    public $phone_number;
    public $dob;

    public function getUserInfo($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
    
        if (!$this->isAgeValid()) {
            return "age up 16";
        }
        if (!$this->isPasswordValid()) {
            return "password not strong ";
        }

        if ($this->isEmailExist()) {
            return "email Exist before";
        }

        // تشفير كلمة السر
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // إعداد استعلام SQL
        $query = "INSERT INTO " . $this->table_name . " (first_name, last_name, email, password, address, phone_number, birth_of_date)
                  VALUES (:first_name, :last_name, :email, :password, :address, :phone_number, :birth_of_date)";
        
        // تحضير الاستعلام
        $stmt = $this->conn->prepare($query);

        // ربط القيم بالمتغيرات
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":birth_of_date", $this->dob);

        // تنفيذ الاستعلام
        if ($stmt->execute()) {
            $_SESSION["success"]='success';
            return "Your account has been successfully registered. Please log in to continue.";
        } else {
            return "Account registration failed.";
        }
    }

    private function isAgeValid() {
        $dob = new DateTime($this->dob);
        $age = $dob->diff(new DateTime())->y;
        return $age >= 16;
    }

    private function isPasswordValid() {
        
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $this->password);
    }

    private function isEmailExist() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function login($email, $password) {
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
           
            // تحقق من كلمة المرور
            if (password_verify($password, $row['password'])) {
                return ".succes login";
                
            } else {
                return "The password or email is incorrect ";
            }
        } else {
            return "The password or email is incorrect";
        }
    }
    
    
}
?>
