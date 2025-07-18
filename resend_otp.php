<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Session expired. Please register again.'); window.location.href = 'register.php';</script>";
    exit;
}

$email = $_SESSION['email'];
$new_otp = rand(100000, 999999);

//Update the old OTP sa test table
    $update = $con->prepare("UPDATE users SET otp = ? WHERE email = ?");
    $update->bind_param("is", $new_otp, $email);
    $update->execute();

if ($update->execute()) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'akosinicolas1@gmail.com'; 
        $mail->Password   = 'mdipvwgwjbcinszl';        // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email setup
        $mail->setFrom('akosinicolas1@gmail.com', 'Palobog');
        $mail->addAddress($email);
 
        $mail->isHTML(true);
        $mail->Subject = "Your New Verification Code";
        $mail->Body    = "
            <h>Your new OTP code is </h3><strong>$new_otp</strong>
            <p>Use this code to verify your email.</p>
            <br><br>
            <p>Regards,<br><strong>Dr. Cheung Dental Clinic</strong></p>
        ";
        // Send mail
        $mail->send();

        $_SESSION['otp'] = $new_otp;
        echo "<script>alert('A new OTP has been sent to your email.'); window.location.href = 'verification.php';</script>";

    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href = 'verification.php';</script>";
    }
} else {
    echo "<script>alert('Database error. Try again later.'); window.location.href = 'verification.php';</script>";
}
?> 