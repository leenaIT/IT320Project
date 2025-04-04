<?php
require 'database.php'; // Database connection

if (isset($_POST['postId'])) {
    $postId = $_POST['postId'];

    // Delete the post from the database
    $stmt = $connection->prepare("DELETE FROM posts WHERE PostID = ?");
    $stmt->bind_param('i', $postId);

    if ($stmt->execute()) {
        echo 'success';  // Return success message if deletion is successful
    } else {
        echo 'error';  // Return error message if deletion fails
    }

    $stmt->close();
} else {
    echo 'error';  // Return error if no postId is passed
}
?>
