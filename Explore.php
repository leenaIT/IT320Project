<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include 'database.php';

// إذا كان الطلب لجميع المنشورات (بدون postID محددة)
if (!isset($_GET['postID'])) {
    $query = "SELECT posts.*, users.FirstName, users.LastName, 
              (SELECT COUNT(*) FROM likes WHERE likes.postID = posts.PostID) AS likeCount
              FROM posts 
              JOIN users ON posts.UserID = users.UserID
              ORDER BY posts.post_date DESC";
    
    $result = mysqli_query($connection, $query);
    $posts = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
    
    echo json_encode($posts);
    exit;
}

// إذا كان الطلب لمنشور محدد
$postID = (int)$_GET['postID'];
$query = "SELECT posts.*, users.FirstName, users.LastName, 
          (SELECT COUNT(*) FROM likes WHERE likes.postID = posts.PostID) AS likeCount
          FROM posts 
          JOIN users ON posts.UserID = users.UserID
          WHERE posts.PostID = ?";
          
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $postID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$post = mysqli_fetch_assoc($result);
echo json_encode($post ?: ['error' => 'Post not found']);
?>