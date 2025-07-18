<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dr. Cheung Dental Clinic</title>
    <link rel="stylesheet" href="css/login-reg.css">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .input-group {
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding-right: 2.5rem;
        }

        .input-group .toggle-password {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">DR. CHEUNG DENTAL CLINIC</div>
        <nav>
            <a href="homepageout.php">Go Back to Homepage</a>
            <a href="register.php">Sign up</a>
            <a href="portal.php">Login</a>
        </nav>
    </header>

    <main>
        <div class="left">
            <h1>Create New Account</h1>
        </div>

        <div class="right">
            <?php
            if (isset($_SESSION['status'])) {
                echo "<h4 style='color: red; text-align: center;'>" . $_SESSION['status'] . "</h4>";
                unset($_SESSION['status']);
            }
            ?>

               <form action="code.php" method="POST" class="signup-form">
                <h2>Sign up</h2>
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="tel" name="phone" placeholder="Phone Number" required>
                <input type="email" name="email" placeholder="Email Address" required>

                <!-- Hidden User Type Input -->
                <input type="hidden" name="type" value="customer">

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                </div>

                <div class="input-group">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                    <i class="bi bi-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                </div>

                <button type="submit" name="reg_btn">Continue</button>
                <p>Already have an account? <a href="customer-login.php">Login</a></p>
            </form>
        </div>
    </main>

    <script>
        // Function to toggle password visibility
        function setupTogglePassword(toggleId, inputId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);

            toggle.addEventListener('mousedown', function(e) {
                e.preventDefault(); // Prevents focus loss
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
                input.focus(); // Ensures the input retains focus
            });
        }

        setupTogglePassword('togglePassword', 'password');
        setupTogglePassword('toggleConfirmPassword', 'confirm_password');
    </script>
</body>

</html>