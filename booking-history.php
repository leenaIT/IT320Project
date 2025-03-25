<?php
include 'database.php'; // Include your database connection

session_start();
$UserID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

if ($UserID) {
    // Fetch completed bookings for the logged-in user
    $query = "SELECT b.BookingID, b.WorkshopID, b.BookingDate, w.Title, r.ReviewID, r.Rating, r.Comment 
              FROM bookings b
              JOIN workshops w ON b.WorkshopID = w.WorkshopID
              LEFT JOIN reviews r ON r.WorkshopID = w.WorkshopID AND r.UserID = ?
              WHERE b.UserID = ? AND b.Status = 'Completed'";

    $stmt = $connection->prepare($query);
    $stmt->bind_param('ii', $UserID, $UserID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($booking = $result->fetch_assoc()) {
            echo '<div class="booking-item" id="booking-' . $booking['BookingID'] . '">';
            echo '<h3>' . $booking['Title'] . '</h3>';
            echo '<p><strong>Booking Date:</strong> ' . $booking['BookingDate'] . '</p>';

            // Check if the user has already reviewed this booking
            if ($booking['ReviewID']) {
                // Display the existing review
                echo '<p><strong>Rating:</strong> ' . str_repeat('&#9733;', $booking['Rating']) . '</p>';
                echo '<p><strong>Review:</strong> ' . $booking['Comment'] . '</p>';
            } else {
                // Display the review submission form
                echo '<button class="review-btn" data-workshopid="' . $booking['WorkshopID'] . '" data-bookingid="' . $booking['BookingID'] . '">Submit Review</button>';
                echo '<div class="review-form" id="review-form-' . $booking['BookingID'] . '" style="display:none;">';
                echo '<label for="rating">Rating:</label>';
                echo '<div class="stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo '<input type="radio" name="rating' . $booking['BookingID'] . '" value="' . $i . '" id="star' . $booking['BookingID'] . '-' . $i . '"><label for="star' . $booking['BookingID'] . '-' . $i . '">&#9733;</label>';
                }
                echo '</div>';
                echo '<textarea id="review-comment-' . $booking['BookingID'] . '" placeholder="Write your review..."></textarea>';
                echo '<button class="submit-review-btn" data-bookingid="' . $booking['BookingID'] . '" data-workshopid="' . $booking['WorkshopID'] . '">Submit</button>';
                echo '</div>';
            }

            echo '</div>';
        }
    } else {
        echo '<p>No completed bookings found.</p>';
    }

    $stmt->close();
}

$connection->close();
?>
