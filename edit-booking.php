<?php
include 'database.php'; // Include your database connection

file_put_contents("debug.log", "Received: " . file_get_contents("php://input") . "\n", FILE_APPEND);

$data = json_decode(file_get_contents("php://input"), true);
$BookingID = isset($data['BookingID']) ? intval($data['BookingID']) : null;
$BookingDateTime = isset($data['BookingDateTime']) ? $data['BookingDateTime'] : null;

if ($BookingID && $BookingDateTime) {
    // Check if the date is in the future
    if (strtotime($BookingDateTime) < time()) {
        echo json_encode(['success' => false, 'message' => 'Cannot select a past date & time.']);
        exit;
    }

    // SQL to update the booking date
    $query = "UPDATE booking SET BookingDate = ? WHERE BookingID = ?";
    $stmt = $connection->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $connection->error]);
        exit;
    }

    $stmt->bind_param('si', $BookingDateTime, $BookingID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking date updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking date']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid booking data']);
}

$connection->close();
?>
