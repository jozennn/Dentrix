<?php 
session_start();
include('dbcon.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user details from the `users` table
    $query = $con->prepare("SELECT name, phone, email, type FROM users WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['status'] = "User not found.";
        header("Location: acc.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid access.";
    header("Location: acc.php");
    exit();
}

$message = '';

if (isset($_POST['update_btn'])) {
    $name     = $_POST['name'];
    $phone    = $_POST['phone'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $type     = $_POST['type'];

    $is_self_edit = ($_SESSION['user_id'] == $id); // Check if the logged-in user is editing themselves
    $original_type = $user['type']; // Original user type from database
    $force_logout = false;

    if ($is_self_edit && $original_type == 'Admin' && $type == 'Customer') {
        // Show JS confirm if changing own account from Admin to Customer
        echo "<script>
            if (!confirm('Warning: You are changing your own account from Admin to Customer. You will be logged out. Proceed?')) {
                window.history.back();
            }
        </script>";
        $force_logout = true;
    }

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $update_query = $con->prepare("UPDATE users SET name = ?, phone = ?, email = ?, password = ?, type = ? WHERE id = ?");
        $update_query->bind_param("sssssi", $name, $phone, $email, $hashedPassword, $type, $id);
    } else {
        $update_query = $con->prepare("UPDATE users SET name = ?, phone = ?, email = ?, type = ? WHERE id = ?");
        $update_query->bind_param("ssssi", $name, $phone, $email, $type, $id);
    }

    if ($update_query->execute()) {
        if ($force_logout) {
            session_destroy();
            echo "<script>
                alert('Account updated successfully. You have been logged out.');
                window.location.href = 'login.php';
                </script>";
            logAction($_SESSION['user_id'], 4, $con); // 4 = Update action_id in audit_actions
            exit();
        } 
        else {
            $details = "Edited account with ID: $id, Name: $name";
            logAction($_SESSION['user_id'], 4, $con, $details); // 4 = Update action_id
            $message = '<p style="color: green; text-align:center;">Account updated successfully. Redirecting...</p>';
            header("refresh:2;url=acc.php");
            exit();
        }
    } else {
        $message = '<p style="color: red; text-align:center;">Something went wrong. Try again.</p>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Account - Dr. Cheung Dental Clinic</title>
    <link rel="stylesheet" href="css/edit.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Edit Account</h2>

        <?php if (!empty($message)) echo '<div class="message">'.$message.'</div>'; ?>
        <form method="POST" action="">
            <label>Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <label>Email Address</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Password (leave blank to keep old password)</label>
            <div style="position: relative;">
                <input type="password" name="password" id="password" placeholder="Enter new password">
                <i class="bi bi-eye-slash" id="togglePassword" tabindex="-1" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
            </div>

            <label>User Type</label>
            <div class="radio-group">
                <label><input type="radio" name="type" value="Admin" <?php if ($user['type'] == 'Admin') echo 'checked'; ?>> Admin</label>
                <label><input type="radio" name="type" value="Customer" <?php if ($user['type'] == 'Customer') echo 'checked'; ?>> Customer</label>
            </div>

            <button type="submit" name="update_btn">Update Account</button>
        </form>

    </div>
</div>

<script>
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
</script>

</body>
</html>