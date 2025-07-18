<?php
require 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;

    if (!empty($id)) {
        $stmt = $con->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
        echo $stmt->execute() ? 'success' : 'error';
    } else {
        echo 'invalid';
    }
}
?>