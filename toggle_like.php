<?php
session_start();
header('Content-Type: application/json');
include 'database.php';

$response = [];

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    $response['status'] = 'unauthorized';
    echo json_encode($response);
    exit;
}

$userID = $_SESSION['user_id'];
$postID = $_POST['postID'] ?? null;

if (!$postID) {
    $response['status'] = 'error';
    $response['message'] = 'postID is required';
    echo json_encode($response);
    exit;
}

// Check if like exists
$checkQuery = "SELECT * FROM likes WHERE userID = ? AND postID = ?";
$stmt = $connection->prepare($checkQuery);
$stmt->bind_param("ii", $userID, $postID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Delete the like
    $deleteQuery = "DELETE FROM likes WHERE userID = ? AND postID = ?";
    $stmt = $connection->prepare($deleteQuery);
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
    $response['status'] = 'unliked';
} else {
    // Add the like
    $insertQuery = "INSERT INTO likes (userID, postID) VALUES (?, ?)";
    $stmt = $connection->prepare($insertQuery);
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
    $response['status'] = 'liked';
}

// Count total likes
$countQuery = "SELECT COUNT(*) as total FROM likes WHERE postID = ?";
$stmt = $connection->prepare($countQuery);
$stmt->bind_param("i", $postID);
$stmt->execute();
$countResult = $stmt->get_result();
$countRow = $countResult->fetch_assoc();
$response['likeCount'] = $countRow['total'] ?? 0;

// Return the response
echo json_encode($response);
?>
