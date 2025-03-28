<?php
header('Content-Type: application/json');
include 'database.php';

$query = "SELECT posts.*, users.Firstname, users.LastName FROM posts 
          JOIN users ON posts.userID = users.userID 
          ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);

$posts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
