<?php
// Include database connection
require_once 'db_connection.php'; // Adjust to your database connection file

// Get JSON data from the client
$data = json_decode(file_get_contents('php://input'), true);

// Extract data from the request
$bookingID = $data['bookingID'];
$rating = $data['rating'];
$comment = !empty($data['comment']) ? $data['comment'] : '';  // Handle empty comment

// Assuming you already have the logged-in user ID from session
session_start();
$userID = $_SESSION['user_id']; // Assuming user ID is stored in session

// Validate inputs
if (empty($bookingID) || empty($rating)) {
    echo json_encode(['success' => false, 'message' => 'Booking ID and rating are required.']);
    exit();
}

// Update the review in the database
$query = "UPDATE reviews SET Rating = ?, Comment = ? WHERE BookingID = ? AND UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("isii", $rating, $comment, $bookingID, $userID);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Your review has been updated successfully!',
        'bookingID' => $bookingID,
        'rating' => $rating,
        'comment' => $comment
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update your review. Please try again.']);
}

$stmt->close();
$conn->close();
?>
