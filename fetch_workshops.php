<?php
session_start();
require "database.php"; // Adjust this to your DB connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$userID = $_SESSION['user_id'];  // Get the logged-in user ID

// Fetch the workshops along with the favorite status
$query = "
    SELECT w.WorkshopID, w.Title, w.ShortDes, w.imageURL, w.Price, 
           IF(f.UserID IS NOT NULL, 1, 0) AS isFavorite
    FROM workshop w
     JOIN favorites f ON w.WorkshopID = f.WorkshopID AND f.UserID = ?
";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$workshops = [];

while ($row = $result->fetch_assoc()) {
    $workshops[] = $row;
}

echo json_encode(['status' => 'success', 'workshops' => $workshops]);
?>
