<?php
include 'dbcon.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in by checking the session
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to submit a review.";
        exit();
    }

    // Get the user input
    $user_id = $_SESSION['user_id']; // User ID from session
    $review = mysqli_real_escape_string($con, $_POST['review']); // Review text from the form
    $rating = intval($_POST['rating']); // Rating from the form

    // Check if the review and rating are valid
    if (empty($review) || $rating < 1 || $rating > 5) {
        echo "Please provide a valid review and rating.";
        exit();
    }

    // SQL query to insert the review
    $sql = "INSERT INTO reviews (user_id, rating, review) VALUES ('$user_id', '$rating', '$review')";

    // Execute the query and check for success
    if (mysqli_query($con, $sql)) {
        // Redirect to user profile page after successful review submission
        header('Location: user.php');
        exit();
    } else {
        // If an error occurs, display it
        echo "Error: " . mysqli_error($con);
    }
}
?>