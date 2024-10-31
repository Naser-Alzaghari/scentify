<?php
session_start();
include('User.php');
include('./includes/include.php');
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$loginResult = ""; 

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // محاولة تسجيل الدخول
    $loginResult = $user->login($email, $password);
    
    if (strpos($loginResult, 'succes login') !== false) {
        // جلب بيانات المستخدم بناءً على البريد الإلكتروني
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // تخزين معلومات المستخدم في الجلسة
            $_SESSION['user_id'] = $row["user_id"];
            $_SESSION['user_role'] = $row["role"];
            $_SESSION['user_name'] = $row["first_name"] . ' ' . $row["last_name"]; // تخزين الاسم الكامل

            // التحقق من صلاحيات المستخدم
            if ($row['role'] === 'admin' || $row['role'] === 'super_admin') {
                header("Location: ../dashBoard/index.php");
                exit();
            } else {
                $_SESSION['error'] = "Access restricted to admin and super admin only.";
                header("Location: AdminLoginPage.php");
                exit();
            }
        } else {
            // المستخدم غير موجود
            $_SESSION['error'] = "User not found.";
            header("Location: AdminLoginPage.php");
            exit();
        }
    } else {
        // خطأ في تسجيل الدخول
        $_SESSION['error'] = $loginResult;
        header("Location: AdminLoginPage.php");
        exit();
    }
}
?>
