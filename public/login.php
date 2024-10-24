<?php
    session_start();
    $_SESSION['user_id']=1;
    header("location: index.php");
?>