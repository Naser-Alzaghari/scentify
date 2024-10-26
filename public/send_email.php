<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require "../vendor/phpmailer/phpmailer/src/Exception.php";
require "../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../vendor/phpmailer/phpmailer/src/SMTP.php";

$name = $_POST["full_name"];
$email = $_POST["email_address"];
$subject = $_POST["subject"];
$message = $_POST["Message"];

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = "naseralzaghari90@gmail.com";
$mail->Password = "xaxhebprakhgavhq";
$mail->SMTPSecure = "ssl";
$mail->Port = 465;

$mail->setFrom("naseralzaghari90@gmail.com");
$mail->addAddress("naseralzaghari90@gmail.com");
$mail->isHTML = true;
$mail->Subject = "Scentify: $subject";
$mail->Body = "Name: $name\r\n
Email: $email\r\n
Message: $message\r\n
This message was sent from your website Scentify.";

try {
    $mail->send();
    $_SESSION["mail"] = true;
} catch(error $e){
    $_SESSION["mail"] = false;
}


header("Location: contact_us.php");
?>