<?php
session_start();
include('dbcon.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$user_email = $_SESSION['email'];

// Fetch user data
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $con->prepare($query);

if (!$stmt) {
    die('Prepare failed: ' . $con->error);
}

$stmt->bind_param('s', $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update user profile
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $type = $_POST['type'];

    $updateQuery = "UPDATE users SET name = ?, phone = ?, email = ?, type = ? WHERE email = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param('sssss', $name, $phone, $email, $type, $user_email);

    if ($updateStmt->execute()) {
        $_SESSION['email'] = $email; // Update session with new email
        $_SESSION['status'] = "Profile updated successfully!";
        header('Location: profile.php');
        exit();
    } else {
        $_SESSION['status'] = "Failed to update profile.";
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Dr. Cheung Dental Clinic</title>
    <link rel="stylesheet" href="css/profilestyle.css">
</head>
<body>
<header>
    <div class="logo">DR. CHEUNG DENTAL CLINIC</div>
    <nav class="profile-nav">
        <?php
            
            $home_link = (strtolower($user['type']) === 'admin') ? 'admin.php' : 'user.php';
        ?>
        <a href="<?= $home_link ?>" class="btn-home">Home</a>
        <a href="#" onclick="confirmLogout()" class="btn-logout">Log out</a>
    </nav>
</header>

<main>
    <div class="profile-container">
        <h2>My Profile</h2>

        <?php
        if (isset($_SESSION['status'])) {
            echo "<p class='status'>" . $_SESSION['status'] . "</p>";
            unset($_SESSION['status']);
        }
        ?>

        <div class="profile-info">
            <div class="info-box">
                <label><strong>Name:</strong></label>
                <input type="text" value="<?= htmlspecialchars($user['name']) ?>" readonly>
            </div>
            <div class="info-box">
                <label><strong>Phone:</strong></label>
                <input type="text" value="<?= htmlspecialchars($user['phone']) ?>" readonly>
            </div>
            <div class="info-box">
                <label><strong>Email:</strong></label>
                <input type="text" value="<?= htmlspecialchars($user['email']) ?>" readonly>
            </div>
            <div class="info-box">
                <label><strong>Account Type:</strong></label>
                <input type="text" value="<?= htmlspecialchars(ucfirst($user['type'])) ?>" readonly>
            </div>

            <button class="btn" onclick="openPopup()">Update Profile</button>
        </div>

        <!-- Popup Form -->
        <div id="popupForm" class="popup-form">
            <div class="form-content">
                <span class="close-btn" onclick="closePopup()">&times;</span>
                <h3>Edit Profile</h3>
                <form action="profile.php" method="POST" class="edit-form">
                    <label>Full Name:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

                    <label>Phone Number:</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

                    <label>Email Address:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                    <label>Account Type:</label>
                    <input type="text" name="type" readonly value="<?= htmlspecialchars($user['type']) ?>" required>

                    <button type="submit" name="update_profile">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function openPopup() {
        document.getElementById('popupForm').style.display = 'flex'; // Change to flex for center alignment
    }
    function closePopup() {
        document.getElementById('popupForm').style.display = 'none';
    }
    function confirmLogout() {
      var confirmation = confirm("Are you sure you want to log out?");
      if (confirmation) {
        window.location.href = 'logout.php';
      }
    }
</script>

</body>
</html>
