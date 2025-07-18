<?php
session_start();
include('dbcon.php');

if (isset($_GET['id'])) { // <-- change to $_GET
    $id = $_GET['id'];

    $delete_query = $con->prepare("DELETE FROM test WHERE id = ?");
    $delete_query->bind_param("i", $id);

    if ($delete_query->execute()) {
        $_SESSION['status'] = "Account deleted successfully.";
    } else {
        $_SESSION['status'] = "Failed to delete account. Try again.";
    }
} else {
    $_SESSION['status'] = "Invalid access.";
}

header("Location: acc.php");
exit();
?>
