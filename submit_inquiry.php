<?php
session_start(); // Ensure session is started

include('dbcon.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch the user's details (name, email, phone) from the database
$sql = "SELECT name, email, phone FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone);
$stmt->fetch();
$stmt->close();

// Check if user details are found
if (!$email || !$name || !$phone) {
    echo "User details not found.";
    exit();
}

// Process form submission if method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];

    // Insert the inquiry into the database
    $stmt = $con->prepare("INSERT INTO inquiries (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Inquiry submitted successfully!'); window.location.href='user.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
