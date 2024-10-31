<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../dashboard/config.php'; 
require_once '../dashboard/ContactMessage.php'; // Call the message class
require "../vendor/phpmailer/phpmailer/src/Exception.php";
require "../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../vendor/phpmailer/phpmailer/src/SMTP.php";

// Check if all values ​​are present in POST
if (isset($_POST["full_name"], $_POST["email_address"], $_POST["subject"], $_POST["Message"])) {
    $name = htmlspecialchars($_POST["full_name"]);
    $email = filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["Message"]);

    // Verify email
    if ($email) {
        $mail = new PHPMailer(true);

        try {
            // إعدادات SMTP
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "naseralzaghari90@gmail.com";
            $mail->Password = "xaxhebprakhgavhq";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use secure encryption
            $mail->Port = 465;

          //Sending and receiving mail settings
            $mail->setFrom("naseralzaghari90@gmail.com", "Scentify Website");
            $mail->addAddress("naseralzaghari90@gmail.com"); // The email address to which the message will be sent

            //Email content settings
            $mail->isHTML(true);
            $mail->Subject = "Scentify: $subject";
            $mail->Body = "<p><strong>Name:</strong> $name</p>
                           <p><strong>Email:</strong> $email</p>
                           <p><strong>Message:</strong> $message</p>
                           <p>This message was sent from your website Scentify.</p>";
            $mail->AltBody = "Name: $name\nEmail: $email\nMessage: $message\nThis message was sent from your website Scentify.";

         // Send mail
            $mail->send();
            
            // Store the message in the database
            $dbConnection = (new Database())->getConnection();
            $contactMessage = new ContactMessage($dbConnection);
            $contactMessage->saveMessage($name, $email, $subject, $message);

            $_SESSION["mail"] = true;
        } catch (Exception $e) {
            $_SESSION["mail"] = false;
        }
    } else {
        // If the email is invalid
        $_SESSION["mail"] = false;
    }
} else {
    // If the values ​​are not sent
    $_SESSION["mail"] = false;
}

header("Location: contact_us.php");
exit();
?>