<?php
require 'dbcon.php';
session_start();

// Log the logout action before clearing the session
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    $details = "User logged out with Email: " . $_SESSION['email'];
    logAction($_SESSION['user_id'], 2, $con, $details); // 2 = Logout action_id
}

// Clear all session variables and destroy the session
session_unset();
session_destroy();

header("Location: login.php");
exit(); 