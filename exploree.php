<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'database.php';

$loggedIn = isset($_SESSION['user_id']);

// Query to fetch all posts with user info
$sql = "SELECT 
            p.PostID,
            u.UserID,
            u.FirstName,
            u.LastName,
            u.ProfilePhoto,
            p.images,
            p.comment AS PostComment,
            p.post_date
        FROM 
            Posts p
        JOIN 
            Users u ON p.UserID = u.UserID
        ORDER BY 
            p.post_date DESC";

$result = $connection->query($sql);

// Function: check if user already liked a post
function userLiked($connection, $userID, $postID) {
    $stmt = $connection->prepare("SELECT 1 FROM likes WHERE userID = ? AND postID = ?");
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

// Function: add like
function likePost($connection, $userID, $postID) {
    if (!$userID || !$postID) return;

    $stmt = $connection->prepare("INSERT IGNORE INTO likes (userID, postID, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
}

// Function: remove like
function unlikePost($connection, $userID, $postID) {
    $stmt = $connection->prepare("DELETE FROM likes WHERE userID = ? AND postID = ?");
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
}

// Handle all AJAX POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo 'Not logged in';
        exit;
    }

    $userID = $_SESSION['user_id'];

    if (isset($_POST['action'], $_POST['postID'])) {
        $postID = intval($_POST['postID']);
        $action = $_POST['action'];

        if ($action === 'like') {
            likePost($connection, $userID, $postID);
            echo json_encode(['status' => 'liked']);
            exit;
        } elseif ($action === 'unlike') {
            unlikePost($connection, $userID, $postID);
            echo json_encode(['status' => 'unliked']);
            exit;
        } elseif ($action === 'check_like') {
            $liked = userLiked($connection, $userID, $postID);
            echo json_encode(['status' => $liked ? 'liked' : 'unliked']);
            exit;
        } elseif ($action === 'submit_comment') {
            $commentText = trim($_POST['comment']);
            if ($commentText !== '') {
                $stmt = $connection->prepare("INSERT INTO comments (PostID, UserID, CommentText, CreatedAt) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("iis", $postID, $userID, $commentText);
                $stmt->execute();
                echo json_encode(['status' => 'commented']);
                exit;
            }
        } elseif ($action === 'load_comments') {
            $sql = "SELECT c.CommentID, c.CommentText, c.UserID, u.FirstName, u.LastName, u.ProfilePhoto
                    FROM comments c
                    JOIN users u ON c.UserID = u.UserID
                    WHERE c.PostID = ?
                    ORDER BY c.CreatedAt DESC";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $postID);
            $stmt->execute();
            $result = $stmt->get_result();

            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = [
                    'commentID' => $row['CommentID'],
                    'userID' => $row['UserID'],
                    'userName' => $row['FirstName'] . ' ' . $row['LastName'],
                    'userPhoto' => "uploads/" . $row['ProfilePhoto'],
                    'commentText' => $row['CommentText'],
                    'isOwnComment' => $row['UserID'] == $userID
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($comments);
            exit;
        }
    }

    // Handle deletion
    if (isset($_POST['action']) && $_POST['action'] === 'delete_comment' && isset($_POST['commentID'])) {
        $commentID = intval($_POST['commentID']);
        $stmt = $connection->prepare("DELETE FROM comments WHERE CommentID = ? AND UserID = ?");
        $stmt->bind_param("ii", $commentID, $userID);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'deleted']);
        } else {
            http_response_code(403);
            echo json_encode(['status' => 'not authorized']);
        }
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore</title>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
   <!-- Masonry Layout -->
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

  <!-- AOS and Micromodal -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>


    <style>
     /* الهيدر */
header{
    position:relative;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: transparent;
}

.logo {
    margin-top:-520px;
    margin-left:-20px;
    position:absolute;
    width:90px;
}

.logo img {
    height: auto;
}

/* روابط سطح المكتب */
.desktop-nav {
    position: absolute;
    top: 25px;
    right: 40px;
    display: flex;
    gap: 20px;
    font-weight: bold; 
    margin-right:50px;
    margin-top:-10px;
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

/******************************************new code*/
 .banner-video {
    position: absolute;
    top: 34%;
    left: 50%;
    width: auto;
    height: 100%;
    min-width: 100%;
    transform: translate(-50%, -50%) scale(0.85);
    object-fit: cover;
    z-index: -1;

}
header{
    position: relative; width: 100%; height: 560px;  padding-top: 30px;
}

/* POSTS DISPLAY******/
body{
    overflow-x: hidden ;
}

.grid {
    max-width: 100%;
    width:100%;
    margin-left:10px;
  
}

.grid-item {
    width: 290px;
    margin-bottom: 10px;
   opacity: 0;
  transition: opacity 0.6s ease 
}
.grid-item.visible {
  opacity: 1;
}

img {
    width: 100%;
    border-radius: 10px;
    object-fit: cover;
}


/*modal*/
.modal {
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.4s ease;
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.6);
  z-index: 9999;
   flex-grow: 0;
   backdrop-filter: blur(2px);
  -webkit-backdrop-filter: blur(2px);
}

.modal.show {
  opacity: 1;
  pointer-events: auto;
}


.modal-card {
  background: white;
  border-radius: 12px;
  max-width: 500px;
  width:500px;
  max-height: 110vh; 
  height:480px;
  padding: 15px 20px;      
  overflow-y: auto;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
  position: relative;
}

.image-carousel {
  position: relative;
}

.carousel-inner {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
}

.post-card {
  position: relative;
  cursor: pointer;
  overflow: hidden;
  border-radius: 12px;
  transition: transform 0.35s ease, box-shadow 0.35s ease;
  z-index: 1;
}

.post-card:hover {
  transform: translateY(-6px) scale(1.03);
  box-shadow: 0 16px 32px rgba(0, 0, 0, 0.25);
  z-index: 5;
}

/* Dim image slightly on hover */
.post-card img {
  display: block;
  width: 100%;
  height: auto;
  transition: opacity 0.35s ease, transform 0.35s ease;
  will-change: transform, opacity;
}

.post-card:hover img {
  opacity: 0.85;
  transform: scale(1.05);
}

/* Optional: subtle overlay */
.post-card::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.15), transparent);
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 12px;
}

.post-card:hover::after {
  opacity: 1;
}
/* FadeUp animation for grid items */
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Make the grid item visible with fade-up animation */
.grid-item {
  opacity: 0;
  transition: opacity 0.9s ease;
}

/* Apply the fade-up animation when the item becomes visible */
.grid-item.visible {
  animation: fadeUp 0.9s ease forwards;
}

/* Arrow Styles  */
.prev-btn, .next-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  font-size: 14px;
  padding: 10px;
  cursor: pointer;
  border-radius: 50%;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.prev-btn:hover, .next-btn:hover {
  background-color: rgba(0, 0, 0, 0.8);
  transform: scale(1.1);
}

.prev-btn:before {
  content: '\2190';
}

.next-btn:before {
  content: '\2192';
}

.prev-btn {
  left: 10px;
}

.next-btn {
  right: 10px;
}
.post-image {
  display: none;
  width:250px;
  transition: opacity 0.3s ease;
   max-width: 90vw;
  max-height: 65vh;
  object-fit: contain;
}

.post-image.active {
  display: block;
  opacity: 1;
}
.modal-close {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 28px;
  font-weight: bold;
  color: #fff;
  cursor: pointer;
  z-index: 1000;
  transition: color 0.3s ease;
  color:#333;
}

.modal-close:hover {
  color: darkgray;
}

/* Profile and content */
.profile {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 15px;
    position: relative;
}

.user-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.comment-section {
  margin-top: 10px;
}

.explore-quote {
  font-size: 1.6rem;
  font-weight: 600;
  text-align: center;
  margin-top: 20px;
  font-family: 'Georgia', serif;
  background: linear-gradient(90deg, #ffd700, #ffcc00, #ffbf00);
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
  -webkit-text-fill-color: transparent;
  text-shadow: 1px 1px 10px rgba(255, 215, 0, 0.5);
  opacity: 0;
  transform: translateY(20px);
  animation: fadeSlideUp 3s ease-out forwards, pulseGlow 4s ease-in-out infinite;
  margin-top:-90px;
  margin-bottom:40px;
}

@keyframes fadeSlideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulseGlow {
  0%, 100% {
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.7), 0 0 20px rgba(255, 200, 0, 0.5);
  }
  50% {
    text-shadow: 0 0 14px rgba(255, 230, 100, 0.9), 0 0 28px rgba(255, 200, 0, 0.7);
  }
}
/***** heart button ****/
.heart-icon {
      position: absolute;
  right:0; /* Move the icon to the left side of the modal */
    font-size: 24px;
    color: darkgrey;
    cursor: pointer;
    transition: transform 0.3s ease, color 0.3s ease;
}

.heart-icon.liked {
  color: #e63946;
}
.comments-container {
  margin-top: 1.5rem;
  max-height: 300px;
  overflow-y: auto;
  padding-right: 10px;
}

.comment-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  padding: 12px 16px;
  margin-bottom: 12px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  transition: all 0.3s ease;
}

.comment-card:hover {
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.comment-card img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.comment-card div {
  flex: 1;
}

.comment-card strong {
  font-size: 15px;
  color: #333;
}

.comment-card span {
  display: block;
  margin-top: 4px;
  font-size: 14px;
  color: #555;
}

/* Add comment box */
.add-comment-box {
  display: flex;
  align-items: center;
  background: rgba(0, 0, 0, 0.05);
  border-radius: 25px;
  padding: 10px 16px;
  margin-top: 20px;
  border: 1px solid rgba(0, 0, 0, 0.08);
}

.add-comment-box input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 10px;
  font-size: 14px;
  outline: none;
}

.add-comment-box button {
  background-color: #ff4d6d;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 50%;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.add-comment-box button:hover {
  background-color: #e8435f;
}

.add-comment-box i {
  font-size: 16px;
}
.delete-comment {
    width: 20px !important;
    height: 20px !important;
    cursor: pointer;
    float: right;
    margin-left: 8px;
    transition: transform 0.2s;
    object-fit: contain;
}

.delete-comment:hover {
    transform: scale(1.2); /* optional hover effect */
}
@keyframes fadeInComment {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.comment-container {
  animation: fadeInComment 0.4s ease forwards;
}

 @media (max-width: 768px) {
/* 🔶 Footer */
  footer {
    flex-direction: column;
    align-items: center;
    padding: 1em;
  }

  .footer-left-1,
  .footer-center-1,
  .footer-right-1 {
    width: 100%;
    margin-bottom: 1em;
    padding: 0.5em 0;
  }

  .contact-info-1 {
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
  }

  .contact-item-1 {
    flex: 0 0 auto;
    margin: 0 5px;
  }

  .footer-logo-1 {
    width: 80px;
    margin: 10px 0;
  }

  .social-icons-1 {
    margin: 15px 0;
  }

  /* 🔶 Navigation */
  .desktop-nav {
    display: none;
  }

  .hamburger {
    display: flex;
  }
  
  .logo{
      width:40px;
      margin-bottom:-320px;
    
  }
  
 .banner-video {
    min-width: 100%;
    width: 100%;
    height: auto;
    object-fit: contain;
    top: 0;
    left: 0;
    transform: none;
  }

  header {
    height: auto; /* so the video height adapts naturally */
    aspect-ratio: 14/7; /* or set a fixed height like 250px */
    position: relative;
    margin-top:-8px;
    margin-left:-8px;
  }
 

.grid-item {
    width: 130px;
    margin-bottom: 10px;
}
 
.explore-quote{
    font-size: 0.8em;
    margin-top:-30px;
}
.modal-card{
    width:350px;
}
 }/* end media*/

</style>

<body>
    
  <!-- Video Header with Overlaid Navigation -->
<header >
  <!-- Background Video -->
 <video autoplay muted loop playsinline class="banner-video">
  <source src="workshops/banner.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
<div class="logo">
        <img src="workshops/logo.png" alt="logo">
    </div>
  
  <!-- Navigation Content -->
  <div>
    <!-- اللوقو في الوسط -->
    

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
            <a href="exploree.php">Explore</a>
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
  </div>
</header>

  <p class="explore-quote">Wander where inspiration lives — your next story starts here.</p>

 <div class="grid" >
    <?php while ($row = $result->fetch_assoc()): ?>
        <?php $images = json_decode($row['images']); ?>
        <div class="grid-item post-card" 
     data-postid="<?php echo $row['PostID']; ?>"
     data-liked="<?php echo userLiked($connection, $_SESSION['user_id'], $row['PostID']) ? '1' : '0'; ?>"
     data-images='<?php echo json_encode($images); ?>'
     data-name="<?php echo $row['FirstName'] . ' ' . $row['LastName']; ?>"
     data-comment="<?php echo htmlspecialchars($row['PostComment']); ?>"
     data-photo="uploads/<?php echo $row['ProfilePhoto']; ?>">

            
    <img src="<?php echo $images[0]; ?>" alt="Post" class="main-image" />
</div>
    <?php endwhile; ?>
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
  
<!-- Modal View -->
<div class="modal" id="post-modal">
    <div class="modal-overlay">
        <div class="modal-card">
            <span class="modal-close" id="modal-close">&times;</span>
            <div class="image-carousel">
                <div class="carousel-inner" id="modal-carousel"></div>
                <button class="prev-btn" id="modal-prev"></button>
                <button class="next-btn" id="modal-next"></button>
            </div>
            <div class="card-content">
                <div class="profile">
                    <img id="modal-user-photo" class="user-circle">
                    <p><strong id="modal-user-name"></strong></p>
                    <i class="heart-icon fas fa-heart" id="modal-heart" data-post-id=""></i> <!-- data-post-id will be set dynamically -->
                </div>

                <div class="comment-section">
                    <p class="experience-text" id="modal-comment"></p>
                </div>
                
                <div class="comments-container" id="modal-comments-list"></div>

                <div class="add-comment-box">
                    <input type="text" id="modal-comment-input" placeholder="Add a comment..." />
                    <button id="submit-comment"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>



    
  <script>
function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
}

// GRID 
function initMasonry() {
    const grid = document.querySelector('.grid');
    if (window.innerWidth > 768) {
        new Masonry(grid, {
            itemSelector: '.grid-item',
            columnWidth: 300,
            gutter: 10
        });
    } else {
        new Masonry(grid, {
            itemSelector: '.grid-item',
            percentPosition: true,
            gutter: 10
        });
    }
}
window.addEventListener('load', initMasonry);
window.addEventListener('resize', () => location.reload());

// GRID ANIMATION
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.grid-item').forEach(item => observer.observe(item));

let currentImages = [];
let currentIndex = 0;

document.querySelectorAll('.post-card').forEach(card => {
    card.addEventListener('click', () => {
        currentImages = JSON.parse(card.dataset.images);
        currentIndex = 0;
        const carousel = document.getElementById("modal-carousel");
        carousel.innerHTML = currentImages.map((img, index) => `
            <img src="${img}" class="post-image ${index === 0 ? 'active' : ''}">
        `).join("");

        document.getElementById("modal-user-photo").src = card.dataset.photo;
        document.getElementById("modal-user-name").textContent = card.dataset.name;
        document.getElementById("modal-comment").textContent = card.dataset.comment;

        document.getElementById("modal-prev").style.display = currentImages.length > 1 ? "block" : "none";
        document.getElementById("modal-next").style.display = currentImages.length > 1 ? "block" : "none";

        const postId = card.getAttribute('data-postid');
        const liked = card.getAttribute('data-liked') === '1';
        const heartIcon = document.querySelector('#modal-heart');
        heartIcon.setAttribute('data-post-id', postId);
        heartIcon.classList.toggle('liked', liked);

        loadComments(postId);
        loadPostDetails(postId);

        document.getElementById("post-modal").classList.add("show");
    });
});

document.getElementById("modal-prev").addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
    updateCarousel();
});

document.getElementById("modal-next").addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % currentImages.length;
    updateCarousel();
});

function updateCarousel() {
    document.querySelectorAll(".post-image").forEach((img, i) => {
        img.classList.toggle("active", i === currentIndex);
    });
}

document.querySelector(".modal-overlay").addEventListener("click", e => {
    if (e.target.classList.contains("modal-overlay")) {
        document.getElementById("post-modal").classList.remove("show");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("post-modal");
    const closeBtn = document.getElementById("modal-close");
    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            modal.classList.remove("show");
        });
    }

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.classList.remove("show");
        }
    });
});

// HEART BUTTON FUNCTIONALITY
function checkIfLiked(postID) {
    fetch(window.location.href, {
        method: 'POST',
        body: new URLSearchParams({
            action: 'check_like',
            postID: postID
        })
    })
    .then(response => response.json())
    .then(data => {
        const heart = document.getElementById("modal-heart");
        heart.classList.toggle('liked', data.status === 'liked');
    })
    .catch(error => console.error('Error checking like status:', error));
}

function loadPostDetails(postID) {
    checkIfLiked(postID);
}

document.querySelectorAll('.heart-icon').forEach(icon => {
    icon.addEventListener('click', () => {
        const postId = icon.getAttribute('data-post-id');
        const action = icon.classList.contains('liked') ? 'unlike' : 'like';

        const formData = new FormData();
        formData.append('action', action);
        formData.append('postID', postId);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            icon.classList.toggle('liked', data.status === 'liked');

            const modalHeart = document.getElementById('modal-heart');
            if (modalHeart && modalHeart.getAttribute('data-post-id') === postId) {
                modalHeart.classList.toggle('liked', data.status === 'liked');
            }

            if (data.status === 'liked') {
                icon.classList.add('animate__animated', 'animate__tada');
                setTimeout(() => icon.classList.remove('animate__tada'), 700);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// COMMENTS SECTION
function loadComments(postId) {
    console.log('Loading comments for post:', postId);

    fetch(window.location.href, {
        method: 'POST',
        body: new URLSearchParams({
            action: 'load_comments',
            postID: postId
        })
    })
    .then(res => res.json())
    .then(comments => {
        const container = document.getElementById('modal-comments-list');
        container.innerHTML = '';
        comments.forEach(c => {
            const commentCard = document.createElement('div');
          commentCard.className = 'comment-card comment comment-container';
            commentCard.setAttribute('data-comment-id', c.commentID);

            const img = document.createElement('img');
            img.src = c.userPhoto;
            img.alt = 'User Photo';

            const infoDiv = document.createElement('div');
            infoDiv.innerHTML = `
                <strong>${c.userName}</strong><br/>
                <span>${c.commentText}</span>
            `;

           if (c.isOwnComment) {
          
                const deleteIcon = document.createElement('img');
deleteIcon.src = 'workshops/trash-btn.png';
deleteIcon.className = 'delete-comment';

                infoDiv.appendChild(deleteIcon);
            }

            commentCard.appendChild(img);
            commentCard.appendChild(infoDiv);

            img.onload = () => container.appendChild(commentCard);
        });
    })
    .catch(err => {
        console.error('Error loading comments:', err);
    });
}



document.getElementById('submit-comment').addEventListener('click', () => {
    const input = document.getElementById('modal-comment-input');
    const postId = document.getElementById('modal-heart').getAttribute('data-post-id');
    const commentText = input.value;

    if (commentText.trim() !== '') {
        fetch(window.location.href, {
            method: 'POST',
            body: new URLSearchParams({
                action: 'submit_comment',
                postID: postId,
                comment: commentText
            })
        })
        .then(res => {
            if (res.ok) {
                input.value = '';
                loadComments(postId);
            } else {
                console.error('Failed to submit comment.');
            }
        })
        .catch(err => console.error('Error submitting comment:', err));
    }
});

// 🔴 DELETE COMMENT HANDLER
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-comment')) {
        const commentDiv = e.target.closest('.comment');
        const commentID = commentDiv.getAttribute('data-comment-id');

        fetch(window.location.href, {
            method: 'POST',
            body: new URLSearchParams({
                action: 'delete_comment',
                commentID: commentID
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'deleted') {
                commentDiv.remove();
            } else {
                alert('You can only delete your own comment.');
            }
        })
        .catch(err => console.error('Error deleting comment:', err));
    }
});

document.querySelectorAll('.post-card').forEach(card => {
    card.addEventListener('click', () => {
        const postId = card.getAttribute('data-postid');
        const liked = card.getAttribute('data-liked') === '1';
        const heartIcon = document.querySelector('#modal-heart');

        heartIcon.classList.toggle('liked', liked);
        heartIcon.setAttribute('data-post-id', postId);
    });
});
</script>


    </body>
</html>