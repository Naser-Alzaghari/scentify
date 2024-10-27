<?php
class UserManager {
    private $pdo;

    // Constructor to establish database connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Validate user data
    public function validateUserData($first_name, $last_name, $email, $phone_number, $birth_date, $address, $role, $password = null) {
        $errors = [];

        // First Name validation (only alphabetic, 2-50 chars)
        if (empty($first_name) || !preg_match('/^[a-zA-Z]{2,50}$/', $first_name)) {
            $errors[] = "First Name must be alphabetic and between 2 and 50 characters long.";
        }

        // Last Name validation (only alphabetic, 2-50 chars)
        if (empty($last_name) || !preg_match('/^[a-zA-Z]{2,50}$/', $last_name)) {
            $errors[] = "Last Name must be alphabetic and between 2 and 100 characters long.";
        }

        // Email validation (valid format)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Phone Number validation (up to 20 digits)
        if (!preg_match('/^\d{8,20}$/', $phone_number)) {
            $errors[] = "Phone Number must be between 8 and 20 digits.";
        }

        // Password validation (if provided)
        if ($password && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            $errors[] = "Password must be at least 8 characters long, with an uppercase letter, lowercase letter, number, and special character.";
        }

        // Birth Date validation (must be a valid date, and the user must be older than 16)
        if (empty($birth_date) || !strtotime($birth_date)) {
            $errors[] = "Invalid birth date. Please provide a valid date.";
        } else {
            $birthDate = new DateTime($birth_date);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;

            if ($age <= 16) {
                $errors[] = "User must be older than 16 years old.";
            }
        }

        // Address validation (not empty and not exceeding 255 characters)
        if (empty($address) || strlen($address) > 255) {
            $errors[] = "Address is required and must not exceed 255 characters.";
        }

        // Role validation
        if (!in_array($role, ['user', 'admin', 'super_admin'])) {
            $errors[] = "Invalid role provided.";
        }

        return $errors; // Return the array of error messages (empty if no errors)
    }

    // Add a new user
    public function addUser($first_name, $last_name, $email, $phone_number, $birth_date, $address, $role, $password) {
        // Validate user data
        $errors = $this->validateUserData($first_name, $last_name, $email, $phone_number, $birth_date, $address, $role, $password);
        if (!empty($errors)) {
            return $errors; // Return validation errors
        }

        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user
        $stmt = $this->pdo->prepare("INSERT INTO users (first_name, last_name, email, phone_number, birth_of_date, address, role, password, is_deleted, created_at, updated_at) 
                                     VALUES (:first_name, :last_name, :email, :phone_number, :birth_date, :address, :role, :password_hash, 0, NOW(), NOW())");
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone_number' => $phone_number,
            ':birth_date' => $birth_date,
            ':address' => $address,
            ':role' => $role,
            ':password_hash' => $password_hash,
        ]);

        return true; // Return true if successful
    }

    // Update an existing user
    public function updateUser($user_id, $first_name, $last_name, $email, $phone_number, $birth_date, $address, $role) {
        // Validate user data
        $errors = $this->validateUserData($first_name, $last_name, $email, $phone_number, $birth_date, $address, $role);
        if (!empty($errors)) {
            return $errors; // Return validation errors
        }

        // Update user data based on the user ID
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET first_name = :first_name, 
                last_name = :last_name, 
                email = :email, 
                phone_number = :phone_number, 
                birth_of_date = :birth_date, 
                address = :address, 
                role = :role, 
                updated_at = NOW() 
            WHERE user_id = :user_id
        ");
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone_number' => $phone_number,
            ':birth_date' => $birth_date,
            ':address' => $address,
            ':role' => $role,
            ':user_id' => $user_id
        ]);

        return true; // Return true if successful
    }

    // Soft delete a user (set is_deleted to 1)
    public function softDeleteUser($user_id) {
        $stmt = $this->pdo->prepare("UPDATE users SET is_deleted = 1, updated_at = NOW() WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
    }

    // Get all active users (is_deleted = 0)
    public function getUsers($currentUserRole) {
        // Super admins can see all users, other admins see only users (not other admins)
        if ($currentUserRole === 'super_admin') {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE is_deleted = 0");
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE is_deleted = 0 AND role = 'user'");
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get a user by ID
    public function getUserById($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch();
    }
}
