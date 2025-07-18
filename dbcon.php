<?php
$con = mysqli_connect("localhost", "root", "", "delubyo2");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

function logAction($userId, $actionId, $con, $details = null) {
    $stmt = $con->prepare("INSERT INTO audit_trail (user_id, action_id, details, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $userId, $actionId, $details);
    if (!$stmt->execute()) {
        error_log("Failed to log action: " . $stmt->error); // Log errors for debugging
    }
    $stmt->close();
}

$user_id = $_SESSION['id'] ?? null;
$user_name = '';

if ($user_id) {
    $stmt = $con->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($fetched_name);
    if ($stmt->fetch()) {
        $user_name = $fetched_name;
    }
    $stmt->close();
}


?>