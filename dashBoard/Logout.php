<?php
session_start();
unset($_SESSION["admin_user_id"]);
header('location:../public/AdminLoginPage.php');
?>