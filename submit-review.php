<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require 'database.php'; 

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$userID = $_SESSION['user_id'];

// Get JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON data"]);
    exit();
}

// Extract data from request
$workshopID = isset($data['workshopID']) ? intval($data['workshopID']) : null;
$rating = isset($data['rating']) ? intval($data['rating']) : null;
$comment = isset($data['comment']) ? trim($data['comment']) : '';

if (!$workshopID || !$rating) {
    echo json_encode(["success" => false, "message" => "Workshop ID and rating are required."]);
    exit();
}

// Insert review into database
$query = "INSERT INTO review (WorkshopID, UserID, Rating, Comment) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL prepare failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("iiis", $workshopID, $userID, $rating, $comment);
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Your review has been submitted successfully!",
        "bookingID" => $data['bookingID'] ?? null,
        "rating" => $rating,
        "comment" => $comment
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
exit();
?>
