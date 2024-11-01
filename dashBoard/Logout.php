<?php
session_start();
unset($_SESSION["admin_user_id"]);
unset($_SESSION["user_name"]);
header('location:../public/AdminLoginPage.php');
?>