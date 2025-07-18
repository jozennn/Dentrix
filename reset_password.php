<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include('dbcon.php');

// Check if email session exists
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Session expired. Please register again.'); window.location.href = 'register.php';</script>";
    exit();
}

// Handle password reset
if (isset($_POST['reset_password'])) {
    $entered_otp = $_POST['otp_code'];
    $new_password = $_POST['new_password'];
    $email = $_SESSION['email'];

    if ($entered_otp == $_SESSION['otp']) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update = mysqli_query($con, "UPDATE users SET password='$hashed_password' WHERE email='$email'");

        if ($update) {
            unset($_SESSION['otp']);    // Remove OTP from session
            unset($_SESSION['email']);  // Remove email from session
            $success_message = "Password successfully updated!";
        } else {
            $error_message = "Failed to update password.";
        }
    } else {
        $error_message = "Incorrect OTP. Please try again.";
    }
}

// Handle resend OTP
if (isset($_GET['resend_otp'])) {
    if (!isset($_SESSION['email']) || !isset($_SESSION['otp'])) {
        // No email or OTP found in session, deny resend
        header("Location: login.php");
        exit();
    }

    $email = $_SESSION['email'];
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = 'akosinicolas1@gmail.com';
    $mail->Password = 'mdipvwgwjbcinszl';

    $mail->setFrom('akosinicolas1@gmail.com', 'Password Recovery');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Password Recovery OTP";
    $mail->Body = "<h3>Your new OTP is: <b>$otp</b></h3>";

    if ($mail->send()) {
        $success_message = "OTP has been resent to your email!";
    } else {
        $error_message = "Failed to resend OTP. Please try again.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reset Password - Enter OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/reset_password.css">
</head>
<body>

<div class="navbar">
    DR. CHEUNG DENTAL CLINIC
</div>

<div class="container">
    <div class="card">
        <h2>Reset Your Password</h2>

        <?php if (!empty($success_message)) { ?>
            <div class="success"><?php echo $success_message; ?></div>
            <?php if (strpos($success_message, 'updated') !== false) { ?>
                <a href="login.php" class="btn">Go to Login</a>
            <?php } ?>
        <?php } ?>

        <?php if (!empty($error_message)) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>

        <?php if (empty($success_message) || strpos($success_message, 'OTP') !== false) { ?>
        <form method="post">
            <div class="form-group">
                <label for="otp_code">OTP Code</label>
                <input type="text" name="otp_code" id="otp_code" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>

            <input type="submit" name="reset_password" value="Reset Password" class="btn">
        </form>

        <!-- Resend OTP as link -->
        <a href="?resend_otp=1" class="resend-link">Resend OTP</a>

        <?php } ?>

    </div>
</div>
</body>
</html>
