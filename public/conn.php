<?php
    $servername = "localhost";
    $username = "root";
    $passwords = "";
    try{
        $conn = new PDO("mysql:host=$servername;dbname=perfume_shop", $username, $passwords);
    } catch (PDOException $err) {
        echo "error:".$err->getMessage();
    }
?>