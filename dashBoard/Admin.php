<?php
class Admin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // دالة لاسترجاع بيانات المستخدم بناءً على user_id
    public function getUserInfo($user_id) {
        $query = "SELECT first_name, last_name, email, phone_number, address, profile_picture, role FROM users WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($user['profile_picture'])) {
            $user['profile_picture'] = "../public/assets/img/gallery/" . $user['profile_picture'];
        }

        return $user;
    }

    // دالة لتحديث بيانات المستخدم
    public function updateUserProfile($data) {
        $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number, address = :address";
        
        if (!empty($data['profile_picture'])) {
            $query .= ", profile_picture = :profile_picture";
        }

        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }

        $query .= " WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone_number', $data['phone']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':user_id', $data['user_id']);

        if (!empty($data['profile_picture'])) {
            $stmt->bindParam(':profile_picture', $data['profile_picture']);
        }

        if (!empty($data['password'])) {
            $stmt->bindParam(':password', $data['password']);
        }

        return $stmt->execute();
    }
}
?>
