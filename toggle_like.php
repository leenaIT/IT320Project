<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $postID = $input['postID'] ?? null;
    
    if (!$postID) {
        echo json_encode(['error' => 'postID is required']);
        exit;
    }

    $userIP = $_SERVER['REMOTE_ADDR'];

    // التحقق من وجود الإعجاب
    $checkQuery = "SELECT * FROM likes WHERE postID = ? AND userIP = ?";
    $stmt = mysqli_prepare($connection, $checkQuery);
    mysqli_stmt_bind_param($stmt, "is", $postID, $userIP);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // إزالة الإعجاب
        $deleteQuery = "DELETE FROM likes WHERE postID = ? AND userIP = ?";
        $stmt = mysqli_prepare($connection, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "is", $postID, $userIP);
        mysqli_stmt_execute($stmt);
        $status = "unliked";
    } else {
        // إضافة إعجاب
        $insertQuery = "INSERT INTO likes (postID, userIP) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $insertQuery);
        mysqli_stmt_bind_param($stmt, "is", $postID, $userIP);
        mysqli_stmt_execute($stmt);
        $status = "liked";
    }

    // جلب العدد الجديد للإعجابات
    $countQuery = "SELECT COUNT(*) AS total FROM likes WHERE postID = ?";
    $stmt = mysqli_prepare($connection, $countQuery);
    mysqli_stmt_bind_param($stmt, "i", $postID);
    mysqli_stmt_execute($stmt);
    $countResult = mysqli_stmt_get_result($stmt);
    $countRow = mysqli_fetch_assoc($countResult);
    
    echo json_encode([
        'status' => $status,
        'likeCount' => (int)$countRow['total'],
        'liked' => $status === 'liked' // إضافة هذه القيمة للاستجابة
    ]);
}
?>
