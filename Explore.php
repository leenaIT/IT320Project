<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include 'database.php';

$userIP = $_SERVER['REMOTE_ADDR'];

$query = "SELECT posts.*, users.FirstName, users.LastName, 
          (SELECT COUNT(*) FROM likes WHERE likes.postID = posts.PostID) AS likeCount,
          EXISTS(SELECT 1 FROM likes WHERE likes.postID = posts.PostID AND likes.userIP = ?) AS liked
          FROM posts 
          JOIN users ON posts.UserID = users.UserID
          ORDER BY posts.post_date DESC";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $userIP);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

echo json_encode($posts);
?>