<?php
session_start();
require 'database.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data from database
$sql = "SELECT FirstName, LastName, Email, Mobile, ProfilePhoto, bio FROM users WHERE UserID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Set default profile photo if not uploaded
$photo = !empty($user['ProfilePhoto']) ? 'uploads/' . $user['ProfilePhoto'] : 'uploads/default.jpg';

// Check if the form is being submitted
if (isset($_POST['update_profile'])) {
    $newFirstName = $_POST['first_name'];
    $newLastName = $_POST['last_name'];
    $newEmail = $_POST['email'];
    $newMobile = $_POST['mobile'];
    $newBio = $_POST['bio'];

    // Handle photo upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $photoName = uniqid() . '_' . $_FILES['profile_photo']['name'];
        $photoTmp = $_FILES['profile_photo']['tmp_name'];

        // Delete old photo if it exists and is not the default
        if (!empty($user['ProfilePhoto']) && $user['ProfilePhoto'] !== 'default.jpg') {
            $oldPhotoPath = 'uploads/' . $user['ProfilePhoto'];
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath); // Delete old file
            }
        }

        // Upload new photo
        if (is_uploaded_file($photoTmp)) {
            $uploadPath = "uploads/" . $photoName;
            if (move_uploaded_file($photoTmp, $uploadPath)) {
                echo "File uploaded successfully: " . $uploadPath . "<br>";
            } else {
                echo "Error: Failed to move uploaded file.<br>";
            }
        } else {
            echo "File upload failed.<br>";
        }
    } else {
        $photoName = $user['ProfilePhoto']; // Keep old photo if no new file is uploaded
    }

    // Update the database with the new data
    $updateSql = "UPDATE users SET FirstName=?, LastName=?, Email=?, Mobile=?, Bio=?, ProfilePhoto=? WHERE UserID=?";
    $stmt = $connection->prepare($updateSql);
    $stmt->bind_param("ssssssi", $newFirstName, $newLastName, $newEmail, $newMobile, $newBio, $photoName, $user_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully!<br>";
        // Redirect to the profile page after saving
        header("Location: profilePage.php?update=success");
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error . "<br>";
    }
}
?>
