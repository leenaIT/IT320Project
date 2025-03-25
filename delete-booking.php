<?php
include 'database.php'; // Include your database connection

$data = json_decode(file_get_contents("php://input"), true);
$BookingID = $data['BookingID'];

if (isset($BookingID)) {
    // SQL to delete the booking from the database
    $query = "DELETE FROM booking WHERE BookingID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $BookingID);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete booking']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
}

$connection->close();
?>
