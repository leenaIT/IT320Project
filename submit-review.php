<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'database.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$bookingID = $data['bookingID'];
$rating = $data['rating'];
$comment = !empty($data['comment']) ? $data['comment'] : '';
$userID = $_SESSION['user_id'];
$workshopID = intval($data['workshopID']);

if (empty($bookingID) || empty($rating)) {
    echo json_encode(['success' => false, 'message' => 'Booking ID and rating are required.']);
    exit();
}

$query = "INSERT INTO review (BookingID, UserID, Rating, Comment,WorkshopID) VALUES (?, ?, ?, ?,?)";
$stmt = $connection->prepare($query);
$stmt->bind_param("iiiss", $bookingID, $userID, $rating, $comment,$workshopID);

if ($stmt->execute()) {
    $newReviewID = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'reviewID' => $newReviewID, // Send back the ReviewID
        'message' => 'Your review has been submitted successfully!'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit your review.']);
}

$stmt->close();
$connection->close();
?>
