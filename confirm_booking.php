<?php
session_start();
require 'database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please log in first.'
    ]);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);
$workshopID = isset($data['workshopID']) ? intval($data['workshopID']) : 0;
$scheduleID = isset($data['scheduleID']) ? intval($data['scheduleID']) : 0;

if ($workshopID <= 0 || $scheduleID <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid workshop or schedule ID.'
    ]);
    exit;
}

$userID = $_SESSION['user_id'];
$randomBID = mt_rand(1000000000, 9999999999);
$bookingDate = date("Y-m-d H:i:s");


$check_stmt = $connection->prepare("
    SELECT BookingID FROM booking 
    WHERE UserID = ? AND WorkshopID = ? AND ScheduleID = ?
");
$check_stmt->bind_param("iii", $userID, $workshopID, $scheduleID);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode([
        'status' => 'already_booked',
        'message' => 'You have already booked this workshop for the selected time.'
    ]);
    exit;
}


$stmt = $connection->prepare("
    INSERT INTO booking (BookingDate, UserID, WorkshopID, ScheduleID, BID)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("siiis", $bookingDate, $userID, $workshopID, $scheduleID, $randomBID);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'BID' => $randomBID
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to confirm booking.'
    ]);
}

$stmt->close();
$connection->close();
?>
