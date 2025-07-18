<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include('dbcon.php');

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    $sql = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    $fetch = mysqli_fetch_assoc($sql);

    if (mysqli_num_rows($sql) <= 0) {
        $error_message = "Sorry, no such email exists.";
    } elseif ($fetch["verify_status"] == 0) {
        echo "<script>alert('Please verify your account first.'); window.location.href='verification.php';</script>";
        exit;
    } else {
        $otp = rand(100000, 999999);
        $_SESSION['email'] = $email;
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
        $mail->Body = "<h3>Your OTP is: <b>$otp</b></h3>";

        if (!$mail->send()) {
            $error_message = "Failed to send OTP. Try again.";
        } else {
            echo "<script>alert('Recovery OTP sent to your email!'); window.location.href = 'reset_password.php';</script>";
            
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Password Recovery - Send OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/recover_psw.css">
</head>
<body>

<div class="navbar">
    DR. CHEUNG DENTAL CLINIC
</div>

<div class="container">
    <div class="card">
        <h2>Recover Password</h2>

        <?php if (!empty($error_message)) echo "<div class='error'>$error_message</div>"; ?>

        <form method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
            </div>
            <input type="submit" name="send_otp" value="Send OTP" class="btn">
        </form>
    </div>
</div>

</body>
</html>
