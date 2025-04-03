<?php
header('Content-Type: application/json');
include 'database.php';

$postID = $_GET['postID'] ?? null;
if (!$postID) {
    echo json_encode(['error' => 'postID is required']);
    exit;
}

$userIP = $_SERVER['REMOTE_ADDR'];

$countQuery = "SELECT COUNT(*) AS likeCount FROM likes WHERE postID = ?";
$stmt = mysqli_prepare($connection, $countQuery);
mysqli_stmt_bind_param($stmt, "i", $postID);
mysqli_stmt_execute($stmt);
$countResult = mysqli_stmt_get_result($stmt);
$countRow = mysqli_fetch_assoc($countResult);

$checkQuery = "SELECT 1 FROM likes WHERE postID = ? AND userIP = ?";
$stmt = mysqli_prepare($connection, $checkQuery);
mysqli_stmt_bind_param($stmt, "is", $postID, $userIP);
mysqli_stmt_execute($stmt);
$liked = mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;

echo json_encode([
    'likeCount' => (int)$countRow['likeCount'],
    'liked' => $liked
]);
?>