<?php
session_start();
include ('./includes/include.php');
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
            if ($row['role'] !== 'user') {
                $_SESSION['error'] = "User not found.";
                header("Location: LoginPage.php");
                exit();
            }
            // هون كانت  عندي المشكله
            $_SESSION['user_id'] = $row["user_id"];
             
            if(isset($_SESSION['product_id'])){
                $product_id = $_SESSION['product_id'];
                unset($_SESSION['product_id']);
                header("Location: product_page.php?product_id=$product_id");
                exit();
            }
            
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
