<?php
    session_start();
    $_SESSION['user_id']=1;
    $_SESSION['user_name']="naser";
    include "conn.php";
    header("location: index.php");
?>