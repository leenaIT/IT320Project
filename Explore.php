<?php
ini_set('display_errors',1);
ob_start();
session_start();

$loggedIn = isset($_SESSION['user_id']);



if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
    header('Content-Type: application/json');
    
    // تأكد من وجود ملف الاتصال بقاعدة البيانات
    if (!file_exists('database.php')) {
        echo json_encode(['error' => 'Database configuration file missing']);
        exit;
    }
    
    include 'database.php';
    
    // تحقق من نجاح الاتصال
    if (!$connection) {
        echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
        exit;
    }

    $userIP = $_SERVER['REMOTE_ADDR'];

 $query = "SELECT posts.*, users.FirstName, users.LastName, 
          (SELECT COUNT(*) FROM likes WHERE likes.postID = posts.PostID) AS likeCount,
          EXISTS(SELECT 1 FROM likes WHERE likes.postID = posts.PostID AND likes.userIP = ?) AS liked
          FROM posts 
          LEFT JOIN users ON posts.UserID = users.UserID
          ORDER BY posts.post_date DESC";
    
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        echo json_encode(['error' => 'Prepare failed: ' . mysqli_error($connection)]);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $userIP);
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode(['error' => 'Execute failed: ' . mysqli_stmt_error($stmt)]);
        exit;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        echo json_encode(['error' => 'Get result failed: ' . mysqli_error($connection)]);
        exit;
    }

    $posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }

    echo json_encode($posts);
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Page</title>
    <link rel="stylesheet" href="styles2.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>

     /* الهيدر */
header {
    position:relative;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: transparent;
    z-index: 1;
}

.logo {
    flex: 0;
    margin-right: auto;
}

.logo img {
    height: 80px;
    width: 80px;
}

/* روابط سطح المكتب */
.desktop-nav {
    position: absolute;
    top: 25px;
    right: 40px;
    display: flex;
    gap: 20px;
    font-weight: bold;
    
}

/* روابط سطح المكتب */
.desktop-nav a,
.language-switch {
    text-decoration: none;
    color: #FF9D23;
    font-size: 20px;
    padding: 8px 15px;
    transition: 0.3s;
    border-radius: 4px;
    background: transparent;
    border: none;
}

.language-switch:hover {
    background-color: rgba(255, 157, 35, 0.1);
}
/* زر الهامبرغر */
.hamburger {
    display: none;
    cursor: pointer;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    width: 45px;
    height: 45px;
    background: white;
    border: 2px solid #FF9D23;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 8px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: 0.3s;
}

.hamburger-line {
    width: 22px;
    height: 2.5px;
    background: #FF9D23;
    margin: 3px 0;
    border-radius: 2px;
    transition: 0.3s;
}

.hamburger.active {
    background: #FF9D23;
}

.hamburger.active .hamburger-line {
    background: white;
}

.hamburger.active .hamburger-line:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.hamburger.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.hamburger.active .hamburger-line:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* قائمة الجوال */
.mobile-nav-container {
    position: fixed;
    top: 0;
    right: -100%;
    width: 280px;
    height: 100vh;
    background: #fffefc;
    z-index: 999;
    transition: 0.5s;
    padding: 80px 30px;
    box-shadow: -5px 0 20px rgba(0, 0, 0, 0.2);
}

.mobile-nav-container.show {
    right: 0;
}

.mobile-nav {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* زر اللغة في الجوال فقط */
.mobile-nav a,
.mobile-language-switch {
    color: #333;
    background-color: transparent !important;
    text-decoration: none;
    font-size: 18px;
    padding: 12px 15px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    font-weight: 500;
    border-bottom: 1px solid #FF9D23;
}

.mobile-nav a:hover,
.mobile-language-switch:hover {
    transform: translateX(8px);
    color: #FF9D23;
}

  /*footer*/
footer {
            margin-top: 2em;
            padding: 1em 2em;
            background-color: #fffefc;
            border-top: 2px solid #f9b013ec;
            color: #333;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .footer-left-1,
        .footer-center-1,
        .footer-right-1 {
            flex: 1;
            min-width: 250px;
            padding: 0.5em;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* وسط الفوتر */
        .footer-center-1 {
            justify-content: center;
        }

        .footer-logo-1 {
            width: 100px;
        }

        .contact-info-1 {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            align-items: center;
            gap: 20px;
            flex-wrap: nowrap;
            margin-top: 10px;
            width: 100%;
        }

        .contact-item-1 {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .single-line-1 {
            white-space: nowrap;
        }

        /* أيقونات السوشال ميديا */
        .social-icons-1 {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }

        .footer-bottom-1 {
            width: 100%;
            text-align: center;
            margin-top: 0.5em;
        }

        .footer-bottom-1 p {
            padding: 0.5em;
            background-color: #ffffff;
            font-size: 0.75em;
            color: #f9b013ec;
            border-top: 1px solid #ccc;
        }
        .icon-phone {
    display: inline-block !important; 
    width: 30px !important;
    height: 30px !important;
     position: relative !important;
    left: 0 !important;
    right: auto !important;
}

.icon-email {
    width: 42px !important;
    height: 42px !important;
    
}

.icon-location {
    width: 42px !important;
    height: 42px !important;
    margin-top: 6px !important;
}
.icon-facebook {
    width: 35px !important;
    height: 35px !important;
    margin-top: 6px !important;
}

.icon-twitter {
    width: 35px !important;
    height: 35px !important;
    margin-top: 6px !important;
}

.icon-instagram {
    width: 35px !important;
    height: 35px !important;
    margin-top: 6px !important;
}



*,
*::before,
*::after {
  box-sizing: border-box;
}


.menu {
    display: none;
    position: absolute;
    top: 60px;
    right: 20px;
    background: #353129b0;
    border-radius: 8px;
    padding: 10px;
    z-index: 5;
}

.menu.active {
    display: block;
}

.menu ul {
    list-style: none;
    padding: 0;
}

.menu ul li {
    padding: 10px;
}

.menu ul li a {
    color: white;
    text-decoration: none;
}

html[dir="rtl"] .menu ul li {
    text-align: right; 
}

html[dir="rtl"] .menu.active {
    right: auto;
    left: 20px;
}
        body {
            overflow-x: hidden;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .slider {
    position: relative; /* Needed for absolute positioning inside */
    width: 100%;
    height: 600px;
    box-sizing: border-box;
}

.slider video {
    position: absolute;
    top: -119px;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slider video.active {
    opacity: 1;
}

.search-container {
    position: absolute; /* <-- not fixed */
    top: 25%;            /* Center vertically in slider */
    left: 20px;          /* Center horizontally in slider */
    padding: 10px;
    max-width: 600px;
    width: 100%;
    z-index: 2; /* Above the video */
    text-align: center;
    box-sizing: border-box;
}

.search-container span {
    color: #fcfcfc;
    font-size: 50px;
    font-style: italic;
    text-align: left;
}

        .search-bar {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: box-shadow 0.3s ease;
            color: #333;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;

        }
        .search-bar:focus {
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.2);
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: -50px;
            max-width: 100%;
width: 100%;
box-sizing: border-box;

        }
        .posts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
            align-items: start;
            max-width: 100%;
width: 100%;
box-sizing: border-box;

        }
        @media (max-width: 500px) {
    .search-container span {
        font-size: 20px;
    }
    .search-bar {
        font-size: 14px;
        padding: 8px;
    }

    .create-post-box {
        width: 90%;
        padding: 20px;
    }

    .expanded-box {
        flex-direction: column;
        width: 90%;
    }

    .expanded-box img,
    .post-details {
        width: 100%;
        padding-left: 0;
    }
}

        .post-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .post-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }
        .post-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .post-card h3, .post-card p {
            padding: 10px;
            margin: 0;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            animation: fadeInOverlay 0.3s ease-in-out;
        }
        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .expanded-box {
            background: white;
            display: flex;
            flex-direction: row;
            padding: 20px;
            border-radius: 10px;
            width: 60%;
            max-width: 800px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease-in-out;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .expanded-box img {
            width: 50%;
            border-radius: 8px;
        }
        .post-details {
            padding-left: 20px;
            width: 50%;
        }
        .comment-box {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 10px;
            transition: box-shadow 0.3s ease;
        }
        .comment-box:focus {
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.2);
        }
        .close-btn {
            float: right;
            cursor: pointer;
            font-size: 20px;
            transition: color 0.3s ease;
        }
        .close-btn:hover {
            color: #ff0000;
        }
        .like-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #ccc;
            transition: color 0.3s ease;
        }
        .like-btn.liked {
            color: #ff0000;
        }
        .comment {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .comment img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .comment p {
            margin: 0;
        }
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #010d19;
            color: #fff;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            transition: background 0.3s ease;
        }
        .back-to-top:hover {
            background: #8b9fb4;
        }
        .create-post-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: #d39e65;
            color: #fff;
            padding: 15px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 24px;
            transition: background 0.3s ease;
        }
        .create-post-btn:hover {
            background: #c0d1c4;
        }
        .create-post-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        animation: fadeInOverlay 0.3s ease-in-out;
    }
    .create-post-box {
        background: rgba(5, 0, 0, 0.46);
        padding: 30px;
        border-radius: 15px;
        width: 60%;
        max-width: 500px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease-in-out;
    }
    .create-post-box h3 {
        margin-top: 0;
        text-align: center;
        color: #f5f2f2;
        font-size: 24px;
        margin-bottom: 20px;
    }
    .create-post-box input, .create-post-box textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }
    .create-post-box input:focus, .create-post-box textarea:focus {
        border-color: #ebbf62;
        outline: none;
    }
    .create-post-box textarea {
        resize: vertical;
        height: 120px;
    }
    .create-post-box button {
        width: 100%;
        padding: 12px;
        background: #eabc67;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s ease;
    }
    .create-post-box button:hover {
        background: #8c99a7;
    }
    .file-upload {
        margin-bottom: 15px;
    }
    .file-upload label {
        display: block;
        padding: 12px;
        background: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .file-upload label:hover {
        background: #e0e0e0;
    }
    .file-upload input[type="file"] {
        display: none;
    }
    .motivation-text {
        text-align: center;
        color: #f9f2f2;
        font-style: italic;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
    .search-container {
        top: 200px;
        flex-direction: column;
        padding: 8px;
    }

    .search-container span {
        font-size: 24px;
        margin-bottom: 10px;
        display: block;
        text-align: center;
    }

    .create-post-box {
        width: 95%;
        padding: 20px;
    }

    .expanded-box {
        flex-direction: column;
        width: 95%;
    }

    .expanded-box img,
    .post-details {
        width: 100%;
        padding-left: 0;
    }

    .post-card img {
        height: 150px;
    }

    .post-card h3, .post-card p {
        padding: 8px;
        font-size: 14px;
    }

    .nav-links {
        flex-direction: column;
        gap: 10px;
    }

    .nav-links li a {
        font-size: 16px;
    }

    .slider {
        height: 250px;
        top:-65px;
    }
}
@media (max-width: 500px) {
  .expanded-box {
    flex-direction: column;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
  }

  .expanded-box img {
    width: 100%;
    height: auto;
    border-radius: 8px 8px 0 0;
  }

  .post-details {
    width: 100%;
    padding: 10px;
  }

  .comment {
    flex-direction: row;
    align-items: center;
  }

  .comment img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
    margin-bottom: 0;
  }

  .comment p {
    flex: 1;
  }
}

.like-btn.liked i {
    color: red !important;
}

.post-actions {
    padding: 10px;
    display: flex;
    align-items: center;
}

.like-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    color: #ccc; /* اللون الأساسي (غير معجب) */
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.like-btn.liked, 
.like-btn.liked i {
    color: red !important; /* اللون عند الإعجاب */
}

.like-btn:hover {
    color: #ff6b6b;
}

.like-count {
    font-size: 16px;
    color: #333;
}




.logo {
    flex: 0;
    margin-right: auto;
}


/* Desktop Navigation */
.desktop-nav {
    position: absolute;
    top: 25px;
    right: 40px;
    display: flex;
    gap: 20px;
    font-weight: bold;
}

.desktop-nav a,
.language-switch {
    text-decoration: none;
    color: #FF9D23;
    font-size: 20px;
    padding: 8px 15px;
    transition: 0.3s;
    border-radius: 4px;
    background: transparent;
    border: none;
}

.desktop-nav a:hover,
.language-switch:hover {
    background-color: rgba(255, 157, 35, 0.1);
}

/* Hamburger Menu */
.hamburger {
    display: none;
    cursor: pointer;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    width: 45px;
    height: 45px;
    background: white;
    border: 2px solid #FF9D23;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 8px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: 0.3s;
}

.hamburger-line {
    width: 22px;
    height: 2.5px;
    background: #FF9D23;
    margin: 3px 0;
    border-radius: 2px;
    transition: 0.3s;
}

.hamburger.active {
    background: #FF9D23;
}

.hamburger.active .hamburger-line {
    background: white;
}

.hamburger.active .hamburger-line:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.hamburger.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.hamburger.active .hamburger-line:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* Mobile Navigation */
.mobile-nav-container {
    position: fixed;
    top: 0;
    right: -100%;
    width: 280px;
    height: 100vh;
    background: #fffefc;
    z-index: 999;
    transition: 0.5s;
    padding: 80px 30px;
    box-shadow: -5px 0 20px rgba(0, 0, 0, 0.2);
}

.mobile-nav-container.show {
    right: 0;
}

.mobile-nav {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.mobile-nav a,
.mobile-language-switch {
    color: #333;
    background-color: transparent !important;
    text-decoration: none;
    font-size: 18px;
    padding: 12px 15px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    font-weight: 500;
    border-bottom: 1px solid #FF9D23;
}

.mobile-nav a:hover,
.mobile-language-switch:hover {
    transform: translateX(8px);
    color: #FF9D23;
}


/* ========== Footer (مطابق لصفحة البروفايل) ========== */
footer {
    margin-top: 2em;
    padding: 1em 2em;
    background-color: #fffefc;
    border-top: 2px solid #f9b013ec;
    color: #333;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    align-items: flex-start;
}

.footer-left-1,
.footer-center-1,
.footer-right-1 {
    flex: 1;
    min-width: 250px;
    padding: 0.5em;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.footer-center-1 {
    justify-content: center;
}

.footer-logo-1 {
    width: 100px;
}

.contact-info-1 {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    align-items: center;
    gap: 20px;
    flex-wrap: nowrap;
    margin-top: 10px;
    width: 100%;
}

.contact-item-1 {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.single-line-1 {
    white-space: nowrap;
}

.social-icons-1 {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 10px;
}

.footer-bottom-1 {
    width: 100%;
    text-align: center;
    margin-top: 0.5em;
}

.footer-bottom-1 p {
    padding: 0.5em;
    background-color: #ffffff;
    font-size: 0.75em;
    color: #f9b013ec;
    border-top: 1px solid #ccc;
}

.icon-phone {
    display: inline-block !important;
    width: 30px !important;
    height: 30px !important;
    position: relative !important;
    left: 0 !important;
    right: auto !important;
}

.icon-email {
    width: 42px !important;
    height: 42px !important;
}

.icon-location {
    width: 42px !important;
    height: 42px !important;
    margin-top: 6px !important;
}

.icon-facebook {
    width: 35px !important;
    height: 35px !important;
    margin-top: 6px !important;
}

.icon-twitter {
    width: 35px !important;
    height: 35px !important;
    margin-top: 6px !important;
}

.icon-instagram {
    width: 35px !important;
    height: 35px !important;
    margin-top: 6px !important;
}

/* ========== Responsive Styles ========== */
@media (max-width: 768px) {
    body {
        padding-top: 100px;
    }
    
    .container {
        width: 95%;
        padding: 20px;
        flex-direction: column;

    }
    
    .survey-form, .results {
        width: 100%;
        margin-right: 0;
    }
    
    .image-slider {
        width: 100%;
        height: 300px;
        margin-top: 20px;
        order: -1;
    }
    
    .options {
        flex-direction: column;
    }
    
    .workshops-grid {
        flex-direction: column;
    }
    
    .workshop-card {
        width: 100%;
        margin-bottom: 15px;
    }
    
    /* Header Mobile Styles */
    .desktop-nav {
        display: none;
    }
    
    .hamburger {
        display: flex;
    }
    
    .logo img {
       
        width: 50px;
        height: 50px;
        margin-top:-100px;
        position: absolute;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .container {
        width: 90%;
        padding: 30px;
    }
    
    .survey-form, .results {
        width: 55%;
    }
    
    .image-slider {
        width: 40%;
        height: 500px;
    }
    
    .options {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1025px) {
    .container {
        max-width: 1200px;
    }
}

    </style>
</head>


<body>

<header>
    <!-- اللوقو في الوسط -->
    <div class="logo">
        <img src="workshops/logo.png" alt="logo">
    </div>

    <!-- زر الهامبرغر -->
    <div class="hamburger" onclick="toggleMenu(this)">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </div>

    <!-- قائمة الجوال -->
    <div class="mobile-nav-container">
        <nav class="mobile-nav">
            <a href="homepage.php">Home</a>
            <a href="ProfilePage.php"><?php echo $loggedIn ? 'Profile' : 'Login'; ?></a>
            <a href="Explore.php">Explore</a>
            <a href="Survey.php">Survey</a>
            <a href="findcategory.php">Category</a>
           
        </nav>
    </div>

    <!-- قائمة سطح المكتب -->
    <nav class="desktop-nav">
        <a href="homepage.php">Home</a>
        <a href="<?php echo $loggedIn ? 'ProfilePage.php' : 'login.php'; ?>">
            <?php echo $loggedIn ? 'Profile' : 'Login'; ?>
        </a>
        <a href="Explore.php">Explore</a>
        <a href="Survey.php">Survey</a>
        <a href="findcategory.php">Category</a>
    </nav>
</header>



<div class="slider">
    <video class="active" autoplay muted loop>
        <source src="workshops/slider1.mp4" type="video/mp4">
    </video>
    <video autoplay muted loop>
        <source src="workshops/slider2.mp4" type="video/mp4">
    </video>
    <video autoplay muted loop>
        <source src="workshops/slider3.mp4" type="video/mp4">
    </video>

    <div class="search-container">
        <span>Discover Your Passion</span>
    </div>
</div>


<div class="container">
    <div class="posts" id="posts">
        <!-- البوستات تجي من JS -->
    </div>

    <div id="overlay" class="overlay" onclick="closePost()">
        <div class="expanded-box" onclick="event.stopPropagation();">
            <img id="expandedImage" src="" alt="Workshop Image">
            <div class="post-details">
                <span class="close-btn" onclick="closePost()">&times;</span>
                <h3 id="expandedTitle"></h3>

                <input type="hidden" id="postID">

                <div class="actions">
                    <button class="like-btn" onclick="toggleLike()">
                        <i class="fa fa-heart"></i> <span id="likeCount">0</span>
                    </button>
                    
                </div>


            </div>
        </div>
    </div>
</div>

<footer>
    <div class="footer-left-1">
        <h4>Get In Touch</h4>
        <div class="contact-info-1" id="contact-us">
    <div class="contact-item-1">
        <img src="workshops/phone.png" class="icon-phone">
        <span class="single-line-1">+996 58765 43210</span>
    </div>
    <div class="contact-item-1">
        <img src="workshops/mail.png" class="icon-email">
        <span class="single-line-1">mehar@gmail.com</span>
    </div>
    <div class="contact-item-1">
        <img src="workshops/location.png" class="icon-location">
        <span class="single-line-1">Saudi Arabia</span>
    </div>
</div>

    </div>

    <div class="footer-center-1">
        <a href="index.html">
            <img src="workshops/logo.png" alt="Logo" class="footer-logo-1 logo-toggle">
        </a>
    </div>

    <div class="footer-right-1" id="contact">
        <h4>Social Media</h4>
        <div class="social-icons-1">
            <img src="workshops/Facebook_icon_(black).svg" alt="Facebook" class="icon-facebook">
            <img src="workshops/X1.png" alt="Twitter" class="icon-twitter">
            <img src="workshops/CIS-A2K_Instagram_Icon_(Black).svg" alt="Instagram" class="icon-instagram">
        </div>
    </div>

    <!-- الخط السفلي -->
    <div class="footer-bottom-1">
        <p>© 2024 Website. All rights reserved.</p>
    </div>
</footer>

<script>
function loadPosts() {
    fetch("Explore.php", {
        headers: {
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        // Log the data to inspect the format
        console.log(data); // This should be an array of posts

        if (!Array.isArray(data)) {
            console.error("Expected an array of posts but received:", data);
            return; // Exit early if the data is not in the expected format
        }

        const postsContainer = document.getElementById("posts");
        postsContainer.innerHTML = ''; // Clear any existing posts

        const uniquePosts = [];
        const postIds = new Set();

        // Process each post in the array
        data.forEach(post => {
            if (!postIds.has(post.PostID)) {
                postIds.add(post.PostID);
                uniquePosts.push(post);
            }
        });

        uniquePosts.forEach(post => {
            const postCard = document.createElement("div");
            postCard.className = "post-card";

            // Decode the images string into an array (if it's a string)
            let images = [];
            try {
                images = JSON.parse(post.images); // Decode the images string into an array
            } catch (e) {
                console.error("Error decoding images:", e);
            }

            // Use only the first image from the array
            const firstImage = images.length > 0 ? images[0] : null;

            postCard.innerHTML = `
                <div class="post-images">
                    ${firstImage ? `<img src="${firstImage}" alt="Post Image">` : ''}
                </div>
                <h3>${post.comment}</h3>
                <p>Shared by ${post.FirstName || ''} ${post.LastName || ''}</p>
                <div class="post-actions">
                    <button class="like-btn" data-postid="${post.PostID}">
                    </button>
                </div>
            `;

            postsContainer.appendChild(postCard);

            const likeBtn = postCard.querySelector('.like-btn');
            likeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleLikeCard(this);
            });

            postCard.addEventListener('click', () => {
                // Just use the first image in the array for preview (optional)
                if (firstImage) {
                    openPost(firstImage, post.comment, post.PostID);
                }
            });
        });
    })
    .catch(error => {
        console.error("Error loading posts:", error);
    });
}



// دالة للتعامل مع الإعجاب من صفحة Explore
function toggleLike() {
    const postID = document.getElementById("postID").value;
    const likeBtn = document.querySelector(".like-btn");
    const likeIcon = likeBtn.querySelector("i");
    const likeCountSpan = document.getElementById("likeCount");

    fetch("toggle_like.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ postID: postID })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        
        // تحديث حالة الزر
        if (data.liked) {
            likeIcon.style.color = "red";
            likeBtn.classList.add("liked");
        } else {
            likeIcon.style.color = "#ccc";
            likeBtn.classList.remove("liked");
        }
        
        // تحديث العداد
        likeCountSpan.textContent = data.likeCount;
        
        // إعادة تحميل المنشورات لتحديث حالة الإعجاب في العرض الرئيسي
        loadPosts();
    })
    .catch(error => {
        console.error("Error toggling like:", error);
    });
}

// دالة لفتح البوست الموسع
function openPost(image, comment, postID) {
    document.getElementById("expandedImage").src = image;
    document.getElementById("expandedTitle").innerText = comment;
    document.getElementById("postID").value = postID;

    // جلب حالة الإعجاب والعدد
    fetch(`get_likes.php?postID=${postID}`)
        .then(res => res.json())
        .then(data => {
            const likeBtn = document.querySelector(".like-btn");
            const likeIcon = likeBtn.querySelector("i");
            const likeCountSpan = document.getElementById("likeCount");
            
            likeCountSpan.textContent = data.likeCount;
            
            if (data.liked) {
                likeIcon.style.color = "red";
                likeBtn.classList.add("liked");
            } else {
                likeIcon.style.color = "#ccc";
                likeBtn.classList.remove("liked");
            }
            
            // إزالة أي أحداث نقر سابقة وإضافة حدث جديد
            likeBtn.onclick = null; // إزالة المعالجات القديمة
            likeBtn.onclick = function(e) {
                e.stopPropagation();
                toggleLike();
            };
        })
        .catch(error => {
            console.error("Error fetching likes:", error);
        });

    document.getElementById("overlay").style.display = "flex";
}

// دالة لإغلاق البوست الموسع
function closePost() {
    document.getElementById("overlay").style.display = "none";
}

// تحميل المنشورات عند فتح الصفحة
document.addEventListener("DOMContentLoaded", loadPosts);


function toggleMenu(button) {
    button.classList.toggle('active');
    const mobileNav = document.querySelector('.mobile-nav-container');
    mobileNav.classList.toggle('show');
    
    // منع التمرير عند فتح القائمة الجانبية
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
    
    // إغلاق القائمة عند النقر خارجها
    if (button.classList.contains('active')) {
        document.addEventListener('click', closeMenuOnClickOutside);
    } else {
        document.removeEventListener('click', closeMenuOnClickOutside);
    }
}

function closeMenuOnClickOutside(e) {
    const hamburger = document.querySelector('.hamburger');
    const mobileNav = document.querySelector('.mobile-nav-container');
    
    if (!hamburger.contains(e.target) && !mobileNav.contains(e.target)) {
        hamburger.classList.remove('active');
        mobileNav.classList.remove('show');
        document.body.style.overflow = '';
        document.removeEventListener('click', closeMenuOnClickOutside);
    }
}

</script>



</body>
</html>

