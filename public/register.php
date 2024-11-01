<?php
session_start();
include ('./includes/include.php');
include ('User.php');

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
if (isset($_POST['registration'])) {
    $user->first_name = $_POST['FName'];
    $user->last_name = $_POST['Lname'];
    $user->email = $_POST['Email'];
    $user->password = $_POST['pws'];
    $user->address = $_POST['Add'];
    $user->phone_number = $_POST['Phone'];
    
    $user->dob = $_POST['dob'];

    
    if ($_POST['pws'] !== $_POST['pwer']) {
        $_SESSION['error'] = "Your password should be at least 8 characters long and must include at least one uppercase letter, one lowercase letter, one number, and one special character (e.g., !@#$%^&*).";
        header("Location: LoginPage.php"); // إعادة التوجيه إلى صفحة التسجيل
        exit();
    }

    // حاول تسجيل المستخدم
    $registrationResult = $user->register();
    if (strpos($registrationResult, 'Your account has been successfully registered. Please log in to continue.') !== false) {
        $_SESSION['success'] = $registrationResult;
        header("Location: LoginPage.php"); 
        exit();
    } else {
        $_SESSION['error'] = $registrationResult;
        header("Location: LoginPage.php"); 
        exit();
    }
}
?>
