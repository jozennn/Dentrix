<?php
// filepath: c:\xampp\htdocs\Delubyo\update_about.php
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $content = mysqli_real_escape_string($con, $_POST['content']);

    $sql = "UPDATE about SET title = '$title', content = '$content' WHERE about_id = 1";
    if (mysqli_query($con, $sql)) {
        echo "<script>
                alert('About section updated successfully!');
                window.location.href = 'admin.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>