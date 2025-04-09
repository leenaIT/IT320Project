<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $bookingID = isset($_GET['BookingID']) ? intval($_GET['BookingID']) : null;

    if (!$bookingID) {
        echo json_encode(['success' => false, 'message' => 'Booking ID missing']);
        exit;
    }

    // Get WorkshopID from booking
    $stmt = $connection->prepare("SELECT WorkshopID FROM booking WHERE BookingID = ?");
    $stmt->bind_param('i', $bookingID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Invalid Booking']);
        exit;
    }

    $workshopID = $row['WorkshopID'];

    // Get available schedules for the workshop
    $stmt = $connection->prepare("SELECT ScheduleID, Date, StartTime, EndTime FROM workshop_schedule WHERE WorkshopID = ?");
    $stmt->bind_param('i', $workshopID);
    $stmt->execute();
    $schedules = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode(['success' => true, 'schedules' => $schedules]);
    exit;
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Fetch schedules if requested
    if (isset($data['fetchSchedules']) && $data['fetchSchedules'] === true) {
        $bookingID = $data['BookingID'] ?? null;

        if (!$bookingID) {
            echo json_encode(['success' => false, 'message' => 'Booking ID missing']);
            exit;
        }

        // Get WorkshopID from booking
        $stmt = $connection->prepare("SELECT WorkshopID FROM booking WHERE BookingID = ?");
        $stmt->bind_param('i', $bookingID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'Invalid Booking']);
            exit;
        }

        $workshopID = $row['WorkshopID'];

        // Get available schedules for the workshop
        $stmt = $connection->prepare("SELECT ScheduleID, Date, StartTime,Day, EndTime FROM workshop_schedule WHERE WorkshopID = ?");
        $stmt->bind_param('i', $workshopID);
        $stmt->execute();
        $schedules = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        echo json_encode(['success' => true, 'schedules' => $schedules]);
        exit;
    }

    // Update booking schedule
    $bookingID = $data['BookingID'] ?? null;
    $scheduleID = $data['ScheduleID'] ?? null;

    if (!$bookingID || !$scheduleID) {
        echo json_encode(['success' => false, 'message' => 'Missing data']);
        exit;
    }

    $stmt = $connection->prepare("UPDATE booking SET ScheduleID = ? WHERE BookingID = ?");
    $stmt->bind_param('ii', $scheduleID, $bookingID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }

    $stmt->close();
}
?>
