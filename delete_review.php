<?php
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['review_id']);

    $sql = "DELETE FROM reviews WHERE review_id = $id";
    if (mysqli_query($con, $sql)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>