<?php
require 'dbcon.php'; // DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $image = $_FILES['image']['name'] ?? '';

    if (!empty($name) && is_numeric($price) && !empty($image)) {
        // Handle image upload
        $target_dir = "uploads/"; // Correct directory for uploads
        $target_file = $target_dir . basename($image);

        // Check if the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert service into the database
            $stmt = $con->prepare("INSERT INTO services (name, price, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $name, $price, $image);
            if ($stmt->execute()) {
                header("Location: appoint_admin.php"); // Redirect back to appoint_admin.php
                exit();
            } else {
                echo 'Database error: ' . $stmt->error;
            }
        } else {
            echo 'Error uploading image.';
        }
    } else {
        echo 'Invalid input.';
    }
}
?>