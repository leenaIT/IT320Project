<?php
session_start();
require "database.php"; // Database connection

header('Content-Type: application/json');

$response = ["status" => "error", "posts" => []];

$query = "SELECT posts.PostID, posts.UserID, posts.images, posts.comment, posts.post_date, 
                 users.FirstName, users.ProfilePhoto 
          FROM posts 
          JOIN users ON posts.UserID = users.UserID 
          ORDER BY posts.post_date DESC";

$result = $connection->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Decode JSON images
        $images = json_decode($row['images'], true) ?: [];

        // Ensure each image path includes the "uploads/" directory
        $images = array_map(function($img) {
            return "uploads/" . basename($img); // Prepend uploads path
        }, $images);
        $images = json_decode($row['images'], true) ?: [];
$images = array_map(fn($img) => "uploads/" . basename($img), $images);


        // Construct post data
        $response['posts'][] = [
            "postId" => $row['PostID'],
            "userID" => $row['UserID'],
            "userName" => $row['FirstName'],
            "userProfilePic" => "uploads/" . basename($row['ProfilePhoto']), // Ensure profile pic has path
            "images" => $images,
            "comment" => $row['comment'],
            "postDate" => $row['post_date']
        ];
    }
    $response["status"] = "success";
} else {
    $response["status"] = "success"; // No posts found is not an error
    $response["message"] = "No posts available.";
}

echo json_encode($response);
?>
