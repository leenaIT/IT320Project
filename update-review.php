<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'database.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$reviewID = $data['reviewID'];
$updatedRating = $data['rating'];
$updatedComment = !empty($data['comment']) ? $data['comment'] : NULL; // Allow NULL if no comment is provided

if (empty($reviewID) || empty($updatedRating)) {
    echo json_encode(['success' => false, 'message' => 'Review ID and rating are required.']);
    exit();
}

$query = "UPDATE review SET Rating = ?, Comment = ? WHERE ReviewID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("isi", $updatedRating, $updatedComment, $reviewID);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Your review has been updated successfully!']);
} else {
    error_log("MySQL Error: " . $stmt->error);  // Log MySQL error for debugging
    echo json_encode(['success' => false, 'message' => 'Failed to update review.']);
}

$stmt->close();
$connection->close();
?>
