<?php
session_start();
include ('Database.php');
include ('User.php');

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$loginResult = ""; 

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $loginResult = $user->login($email, $password);
    
    if (strpos($loginResult, 'succes login') !== false) {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // هون كانت  عندي المشكله
            $_SESSION['user_id'] = $row["user_id"]; 
            

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "User not found.";
            header("Location: LoginPage.php");
            exit();
        }
    } else {
        $_SESSION['error'] = $loginResult;

        header("Location: LoginPage.php");
        exit();
    }
}
?>