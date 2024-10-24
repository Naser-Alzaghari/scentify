<?php
    $servername = "localhost";
    $username = "root";
    $passwords = "";
    try{
        $conn = new PDO("mysql:host=$servername;dbname=scentify", $username, $passwords);
    } catch (PDOException $err) {
        echo "error:".$err->getMessage();
    }
?>