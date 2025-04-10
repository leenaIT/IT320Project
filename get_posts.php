<?php
ini_set('display_errors',1);
session_start();
require "database.php"; // Database connection

header('Content-Type: application/json');

$response = ["status" => "error", "posts" => []];

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$userID = $_SESSION['user_id']; // Get the logged-in user's ID

$query = "SELECT posts.PostID, posts.UserID, posts.images, posts.comment, posts.post_date, 
                 users.FirstName, users.ProfilePhoto 
          FROM posts 
          JOIN users ON posts.UserID = users.UserID 
          WHERE posts.UserID = ?  
          ORDER BY posts.post_date DESC";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $userID); // Bind the logged-in user's ID to the query
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Decode JSON images
        $images = json_decode($row['images'], true) ?: [];

        // Ensure each image path includes the "uploads/" directory
        $images = array_map(function($img) {
            return "uploads/" . basename($img); // Prepend uploads path
        }, $images);

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
