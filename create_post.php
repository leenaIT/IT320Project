<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require "database.php"; // Include database connection

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = $_POST['comment'];
    $userId = $_SESSION['user_id']; // Assuming you're storing the user's ID in the session

    // Handle image uploads
    $imagePaths = [];
    $uploadDir = "uploads/";

    foreach ($_FILES as $key => $file) {
        if ($file['error'] == UPLOAD_ERR_OK) {
            $fileExt = pathinfo($file["name"], PATHINFO_EXTENSION);
            $uniqueFileName = uniqid('post_', true) . '.' . $fileExt;
            $targetFilePath = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                $imagePaths[] = $targetFilePath;
            }
        }
    }

    // Convert images array to JSON for database storage
    $imagesJson = json_encode($imagePaths);

    // Insert the post into the database
    $stmt = $connection->prepare("INSERT INTO posts (UserID, images, comment, post_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $userId, $imagesJson, $comment);

    if ($stmt->execute()) {
        // Fetch user info
        $stmt = $connection->prepare("SELECT FirstName, ProfilePhoto FROM users WHERE UserID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($userName, $userProfilePic);
        $stmt->fetch();

        echo json_encode([
            "status" => "success",
            "userName" => $userName,
            "userProfilePic" => "uploads/".$userProfilePic,
            "images" => $imagePaths,
            "postDate" => date('Y-m-d H:i:s')
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add post."]);
    }
}
error_log(print_r($_FILES, true)); // Log uploaded files to PHP error log

?>
