<?php
session_start();
include('dbcon.php');

if (isset($_POST["verify"])) {
    if (!isset($_SESSION['email'])) {
        echo "<script>alert('Session expired. Please register again.'); window.location.href = 'register.php';</script>";
        exit;
    }

    $session_email = $_SESSION['email'];
    $user_input_otp = trim($_POST['otp_code']);

    // Check if OTP exists in database
    $check_otp = $con->prepare("SELECT * FROM users WHERE email = ? AND otp = ?");
    $check_otp->bind_param("ss", $session_email, $user_input_otp);
    $check_otp->execute();
    $otp_result = $check_otp->get_result(); // Corrected variable name

    if ($otp_result->num_rows === 0) {
        $error = "Invalid OTP. Please try again.";
    } else {
        $update = $con->prepare("UPDATE users SET verify_status = 'Active' WHERE email = ?");
        $update->bind_param("s", $session_email);

        if ($update->execute()) {
            $clear_otp = $con->prepare("UPDATE users SET otp = NULL WHERE email = ?");
            if (!$clear_otp) {
                die("Prepare failed (update): (" . $con->errno . ") " . $con->error);
            }
            $clear_otp->bind_param("s", $session_email);
            $clear_otp->execute();

            unset($_SESSION['otp']);
            echo "<script>alert('Your account has been verified! You may now log in.'); window.location.href = 'login.php';</script>";
            exit;
        } else {
            $error = "Database error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Custom Styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/verification.css">
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">DR. CHEUNG DENTAL CLINIC</a>
    </div>
</nav>

<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">Enter OTP</div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form action="verification.php" method="POST">
                            <div class="form-group">
                                <label for="otp">OTP Code</label>
                                <input type="text" id="otp" class="form-control" name="otp_code" required autofocus>
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" class="btn btn-primary btn-block" value="Verify" name="verify">
                            </div>
                        </form>
                    </div>
                </div>
                <p class="text-center mt-3">Didn't receive OTP? <a href="resend_otp.php">Resend</a></p>
            </div>
        </div>
    </div>
</main>

</body>
</html>