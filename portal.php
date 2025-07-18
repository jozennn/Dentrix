
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Portal</title>
    <link rel="stylesheet" href="css/login-reg.css">
</head>
<body>
<header>
    <div class="logo">Login Portal</div>
    <nav>
        <a href="homepageout.php">Home</a>
    </nav>
</header>
<main>
    <h1 class="welcome-message">Welcome to the Portal</h1>
    <div class="right">
        <div class="signup-form">
            <h2>Select Login Type</h2>
            <button onclick="location.href='admin-login.php'">Admin Login</button>
            <br><br>
            <button onclick="location.href='customer-login.php'">Customer Login</button>
        </div>
    </div>
</main>
</body>
</html>
