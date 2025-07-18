<?php
require 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    if (!empty($title) && !empty($description) && !empty($image)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $stmt = $con->prepare("INSERT INTO carousel (image, title, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $image, $title, $description);
            if ($stmt->execute()) {
                header("Location: admin.php");
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