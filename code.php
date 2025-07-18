<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendemail_verify($name, $email, $otp)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'akosinicolas1@gmail.com';
        $mail->Password   = 'mdipvwgwjbcinszl'; // app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('akosinicolas1@gmail.com', 'Palobog');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Your Verification Code";
        $mail->Body    = "
            <p>Dear $name,</p>
            <h3>Your OTP code is <strong>$otp</strong></h3>
            <p>Use this code to verify your email.</p>
            <br><br>
            <p>Regards,<br><strong>Dr. Cheung Dental Clinic</strong></p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['reg_btn'])) {
    $name     = $_POST['name'];
    $phone    = $_POST['phone'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $type = 'customer'; // ✅ Capture the user type (Admin or Customer)

    // Check for existing email
    $check_query = $con->prepare("SELECT id FROM users WHERE email = ?");
    $check_query->bind_param("s", $email);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        $_SESSION['status'] = "Email is already registered.";
        header("Location: register.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $otp = rand(100000, 999999); // generate 6-digit OTP

    // Insert into DB
    $insert_query = $con->prepare("INSERT INTO users (name, phone, email, password, otp, verify_status, type) VALUES (?, ?, ?, ?, ?, 0, ?)");
    $insert_query->bind_param("ssssss", $name, $phone, $email, $hashedPassword, $otp, $type);

    if ($insert_query->execute()) {
        $_SESSION['email'] = $email;
        $_SESSION['otp'] = $otp;
        $_SESSION['name'] = $name;

        if (sendemail_verify($name, $email, $otp)) {
            header("Location: verification.php");
        } else {
            $_SESSION['status'] = "Failed to send verification email.";
            header("Location: register.php");
        }
        exit();
    } else {
        $_SESSION['status'] = "Something went wrong. Please try again.";
        header("Location: register.php");
        exit();
    }
}
?>
