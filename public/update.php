<?php
include "./includes/include.php";
include ('User.php');

session_start();
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['update'])) {
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        $query = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone, address = :address";

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", password = :password";
        }

        $query .= " WHERE user_id = :user_id";
        $stmt = $db->prepare($query);

        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":user_id", $user_id);

        if (!empty($password)) {
            $stmt->bindParam(":password", $hashed_password);
        }

        if ($stmt->execute()) {
            $_SESSION['message'] = "User information has been updated successfully.";
            header("Location: userProfile.php"); 
            exit();
        } else {
            $_SESSION['error'] = "Failed to update user information.";
        }
    }
} else {
    echo "User not logged in.";
    exit;
}
?>
