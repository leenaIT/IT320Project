<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'database.php'; // Ensure this connects to your database

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT FirstName, ProfilePhoto FROM users WHERE UserID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $profile_picture = !empty($row['ProfilePhoto']) ? "uploads/" . $row['ProfilePhoto'] : "uploads/default.png";
    
    echo json_encode(["success" => true, "name" => $row['FirstName'], "ProfilePhoto" => $profile_picture]);
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}
?>
