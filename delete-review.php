<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'database.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$reviewID = $data['reviewID'];

if (empty($reviewID)) {
    echo json_encode(['success' => false, 'message' => 'Missing Review ID.']);
    exit();
}

$query = "DELETE FROM review WHERE ReviewID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $reviewID);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Your review has been deleted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete review.']);
}

$stmt->close();
$connection->close();
?>
