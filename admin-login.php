
<?php
session_start();
require 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/login-reg.css">
</head>
<body>
<header>
    <div class="logo">DR. CHEUNG DENTAL CLINIC</div>
    <nav><a href="portal.php">Back to Portal</a></nav>
</header>
<main>
    <h1 class="welcome-message">Admin Access</h1>
    <div class="right">
        <form method="POST" class="signup-form">
            <h2>Admin Login</h2>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
    </div>
</main>
</body>
</html>
