<?php
require 'database.php';


// Get survey answers
$activity = isset($_POST['activity']) ? $connection->real_escape_string($_POST['activity']) : '';
$workshop_type = isset($_POST['workshop_type']) ? $connection->real_escape_string($_POST['workshop_type']) : '';
$time_preference = isset($_POST['time_preference']) ? $connection->real_escape_string($_POST['time_preference']) : '';
$day_preference = isset($_POST['day_preference']) ? $connection->real_escape_string($_POST['day_preference']) : '';

// Build SQL query
$query = "SELECT w.WorkshopID, w.Title, w.ShortDes, w.Location, w.Price, w.ImageURL
          FROM workshop w
          JOIN workshop_schedule ws ON w.WorkshopID = ws.WorkshopID
          WHERE 1=1";

if ($activity) {
    $query .= " AND w.Category = '$activity'";
}

if ($workshop_type) {
    $type = ($workshop_type === 'group') ? 'in-person' : 'online';
    $query .= " AND w.Type = '$type'";
}

if ($time_preference) {
    if ($time_preference === 'morning') {
        $query .= " AND TIME(ws.StartTime) < '12:00:00'";
    } else {
        $query .= " AND TIME(ws.StartTime) >= '12:00:00'";
    }
}

if ($day_preference) {
    if ($day_preference === 'weekdays') {
        $query .= " AND ws.Day IN ('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')";
    } else {
        $query .= " AND ws.Day IN ('Saturday', 'Sunday')";
    }
}

$query .= " GROUP BY w.WorkshopID"; // Avoid duplicate workshops if multiple schedules exist

$result = $connection->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="grid-item">';
        echo '<div class="tag">' . htmlspecialchars($row['Location']) . '</div>';
        echo '<img src="' . htmlspecialchars($row['ImageURL']) . '" alt="' . htmlspecialchars($row['Title']) . '">';
        echo '<h3>' . htmlspecialchars($row['Title']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['ShortDes']) . '</p>';
        echo '<div class="details">';
        echo '<a href="booking.php?workshopID=' . $row['WorkshopID'] . '" class="more-btn">More details</a>';
        echo '<span class="price">';
        echo htmlspecialchars($row['Price']);
        echo '<img src="workshops/riyal.png" alt="SAR" class="riyal-icon">';
        echo '</span>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="no-results">No workshops found for the selected criteria.</div>';
}

$connection->close();
?>