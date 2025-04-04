<?php
session_start();
require "database.php"; // Adjust this to your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST["postId"];
    $newComment = $_POST["comment"];

    $stmt = $connection->prepare("UPDATE posts SET comment = ? WHERE PostID = ?");
    $stmt->bind_param("si", $newComment, $postId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
