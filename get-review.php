<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'database.php';
session_start();

// Ensure the user is logged in by checking the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Ensure bookingID is passed correctly
if (!isset($_GET['bookingID'])) {
    echo json_encode(['success' => false, 'message' => 'Booking ID is required']);
    exit;
}

$bookingID = intval($_GET['bookingID']);
$userID = $_SESSION['user_id'];

$query = "SELECT ReviewID, Rating, Comment FROM review WHERE BookingID = ? AND UserID = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ii", $bookingID, $userID);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Query execution failed', 'error' => $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$review = $result->fetch_assoc();

if ($review) {
    echo json_encode([
        'success' => true,
        'reviewID' => $review['ReviewID'],
        'rating' => $review['Rating'],
        'comment' => $review['Comment']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No review found']);
}
?>
