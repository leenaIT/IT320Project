<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "database.php"; // Ensure this file is correctly included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "error: no session";
        exit();
    }

    if (!isset($_POST['workshopID'])) {
        echo "error: missing workshopID";
        exit();
    }

    $userID = $_SESSION['user_id']; 
    $workshopID = intval($_POST['workshopID']);

    if ($workshopID === 0) {
        echo "error: invalid workshopID";
        exit();
    }

    // Check database connection
    if (!$connection) {
        echo "error: database connection failed";
        exit();
    }

    // Check if the workshop is already in favorites
    $stmt = $connection->prepare("SELECT * FROM favorites WHERE UserID = ? AND WorkshopID = ?");
    if (!$stmt) {
        echo "error: prepare failed - " . $connection->error;
        exit();
    }
    
    $stmt->bind_param("ii", $userID, $workshopID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Remove from favorites
        $stmt = $connection->prepare("DELETE FROM favorites WHERE UserID = ? AND WorkshopID = ?");
        if (!$stmt) {
            echo "error: delete prepare failed - " . $connection->error;
            exit();
        }
        $stmt->bind_param("ii", $userID, $workshopID);
        if ($stmt->execute()) {
            echo "removed";
        } else {
            echo "error: delete failed - " . $stmt->error;
        }
    } else {
        // Add to favorites
        $stmt = $connection->prepare("INSERT INTO favorites (UserID, WorkshopID) VALUES (?, ?)");
        if (!$stmt) {
            echo "error: insert prepare failed - " . $connection->error;
            exit();
        }
        $stmt->bind_param("ii", $userID, $workshopID);
        if ($stmt->execute()) {
            echo "added";
        } else {
            echo "error: insert failed - " . $stmt->error;
        }
    }
}
?>
