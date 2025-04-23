<?php
session_start();
ini_set('display_errors',1);
require 'database.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$loggedIn = isset($_SESSION['user_id']);
// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data from database
$sql = "SELECT FirstName, LastName, Email, Mobile, ProfilePhoto,bio FROM users WHERE UserID = ?";
$stmt1 = $connection->prepare($sql);
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$user = $result1->fetch_assoc();

// Set default profile photo if not uploaded
$photo = !empty($user['ProfilePhoto']) ? 'uploads/' . $user['ProfilePhoto'] : 'uploads/default.jpg';

// Fetch last 4 bookings for the user with workshop title
$sql2 = "
    SELECT w.Title, w.imageURL, s.Date AS BookingDate
    FROM booking b
    JOIN workshop w ON b.WorkshopID = w.WorkshopID
    JOIN workshop_schedule s ON b.ScheduleID = s.ScheduleID
    WHERE b.UserID = ?
    ORDER BY s.Date DESC
    LIMIT 5
"; // Limiting to last 4 bookings
$stmt2 = $connection->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$bookings = $result2->fetch_all(MYSQLI_ASSOC);


// Query to get bookings for the specific user, including workshop details
$query = "
    SELECT b.BookingID, b.BID, b.UserID, b.WorkshopID, 
           w.imageURL, w.Category, w.Location, w.Type, w.Price, w.Title,
           s.Date, s.StartTime, s.EndTime
    FROM booking b
    JOIN workshop w ON b.WorkshopID = w.WorkshopID
    JOIN workshop_schedule s ON b.ScheduleID = s.ScheduleID
    WHERE b.UserID = ?
    ORDER BY s.Date DESC";

$stmt3 = $connection->prepare($query);
$stmt3->bind_param('i', $user_id);  // Bind the userId to the query
$stmt3->execute();
$result3 = $stmt3->get_result();


//Separate bookings into upcoming and completed
$upcomingBookings = [];
$completedBookings = [];

$currentDateTime = date('Y-m-d H:i:s');

while ($booking = $result3->fetch_assoc()) {
    $bookingDateTime = $booking['Date'] . ' ' . $booking['StartTime'];
    if ($bookingDateTime < $currentDateTime) {
        $completedBookings[] = $booking;
    } else {
        $upcomingBookings[] = $booking;
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page </title>
    <link rel="stylesheet" href="styles2.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style> 
    body {
        font-family: Arial, sans-serif;
        margin: 0 auto;
        padding: 0;
        background: #FFFDF0;}

button{ background: none;
border:none;}
  /*header*/
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


.profile-picture {
            width: 117px;
            height: 117px;
            border-radius: 50%;
            position: absolute;
            top: 35px;
            left: 75px;
            border: 2px solid black;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url(<?php echo $photo ?>);
       }
       
      /***********************************************************************************************************************/ 
       @media (max-width: 768px) {
  /* 🔶 Booking History */
  .modal1{

  z-index:1000;
  }
  .empty-message{
      margin-left:80px;
  }
  .booking-item {
    flex-direction: column;
    margin-bottom: 20px;
    padding-bottom: 15px;
  }
.booking-info strong, .workshop-info strong {
  display: flex;               /* Make the parent container a flex container */
  flex-direction: column;      /* Arrange child elements in a column */
  align-items: flex-start; 
text-align:left;
}
.workshop-info {
    margin-left:-60px;
}
.swal2-popup {
    width: 330px; /* Set a specific width */
    height: auto; /* Let the height adjust to the content */
    padding: 20px;
}
  .orange-box {
    margin-top: 30px;
  }

  /* 🔶 Profile Header */
  .content {
    display: flex;
    flex-direction:column
}
  .profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    text-align: center;
    width: 100%;
  }

  .profile-picture {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    position: absolute;
    top:50px;
    left: 17%;
    transform: translateX(-50%);
    border: 2px solid black;
    background-color: white;
  
  }
.detail-title1, .detail-title2{
    font-size:20px;
    margin-left:-225px;
margin-right:60px;

}
::-webkit-scrollbar {
    width: 2px; /* Set the width of the scrollbar */
    height: 2px; /* Set the height of the scrollbar (for horizontal scrolling) */
}
::-webkit-scrollbar-thumb {
    background-color: #f4b42b; /* Yellowish color for the thumb */
    border-radius: 10px;
    border: 1px solid #ccc; /* Add a light border to the thumb */
}
.details{
    margin-left:-210px;
    gap:1px;
    width:220px;
    overflow-x:auto;
}
/* For labels */
.detail-label {
    font-weight: bold;
    font-size: 12px;
    margin-top: 100px;
    margin-left:40px;
}

/* For answers */
.detail-answer {
    font-size:10px ;
    font-weight: normal;
    color: rgba(151, 150, 150, 0.696);
    margin-top: 10px;
    display: block;
      margin-left:40px;
}
  .edit-btn1 {
      width:15px;
      height:15px;
    margin-top: -45px;
    margin-right:-15px;
  }
  .bio {
    position: relative;
    margin-top: -30px;
    width: 40%;
    max-width: 400px;
      z-index:-1;
      margin-left:7px;
      height:230px;
        overflow-y: auto;
        overflow-x:auto;
    max-height: 250px;
  }
  .bio h3{
      margin-top:30px;
      margin-left:-15px;
      font-size:16px;
  }
.modal-content {
    min-width:300px;
    left:20%;
}
.current-picture{
  border: 1px solid #ccc; 
}
/* 🔶 booking timeline */
.bookings {
    width:100%;
    height:100px;
}
#openBookingModal{
    font-size:10px; 
    margin-top:-40px;
     pointer-events: auto !important;
     z-index: 99;
     position:relative;
}
.booking-image{
margin-top:-60px;
margin-left:-30px;
width:400px;
position: absolute;

}
.event-photo1, .event-photo2, .event-photo3, .event-photo4, .event-photo5 {
    width: 50px;
    height: 50px;  
}

.event-photo1 {
    top: 56px; 
    left: 43px; 
}
.event-photo2 {
    top: 135px; 
    left: 110px; 
}
.event-photo3 {
    top: 56px;
    left:182px; 
}
.event-photo4 {
    top: 135px; 
    left: 255px;
}
.event-photo5 {
    top: 56px;
    left: 323px;
}
.event1,.event2,.event3,.event4,.event5{
    font-size:9px;
}
.event1 p , .event2 p , .event3 p , .event4 p, .event5 p{
    font-size:6px;
}
.event1{
top: 56px; 
left: 100px;
}
.event2{
bottom: 25px;
left: 165px;
}

.event3{
top: 56px; 
left: 240px;
} 

.event4{
bottom: 25px;
left: 310px;
}

.event5{
top: 56px; 
left: 380px;
} 

  /* 🔶 Experience Section */
  .experiences {
    padding: 5px;
    margin: 0 10px;
  }

  .experiences h3 {
    font-size: 16px;
  }

  .add-post-btn2 {
    width: 30px;
    height: 30px;
    margin-left: 10px;
  }

  .add-post-btn2 img {
    width: 20px;
    height: 20px;
  }

  #posts-container {
    margin-left: 10px;
    gap: 1em;
    width: 100%;
    padding: 1em 0;
  }

  .card {
    width: 50% !important;
    min-width: unset !important;
    padding: 10px;
    box-sizing: border-box;
  }

  .card-content {
    font-size: 20px;
    padding: 10px;

  }

  .card p strong {
    margin-left: 10px;
    font-size: 14px;
  }

  .post-name {
    font-size: 12px;
    letter-spacing: 1px;
  }

  .post-btn {
    gap: 15px;
  }

  .edit-btn img,
  .delete-btn img {
    width: 18px;
    height: 18px;
  }

  .tooltip {
    font-size: 9px;
    padding: 4px 8px;
    bottom: -25px;
  }
/** wihslist item **/
.empty-wishlist-message{
    margin-left:10px;}
.empty-wishlist-message p{
    font-size:10px;
}
.empty-wishlist-message h3{
    font-size:16px;
}
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
.logo img{
    width:50px;
    height:50px;
}
/**singout****/
.signout {
    position:absolute;
    margin-left:-135px;
    margin-top:0px;
    
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
            <a href="form.php">Survey</a>
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
        <a href="form.php">Survey</a>
        <a href="findcategory.php">Category</a>
    </nav>
</header>
    <section class="profile-header">
<!-- Sign Out Button -->
<div class="signout">
<a href="signout.php"><button class="signout">Sign Out</button></a>
</div>
    <div class="user-info">
        
        <div class="profile-picture"> </div>
       
            <p>
                <span class="detail-title1">User <br></span>
                <span class="detail-title2">Profile</span>
            </p>
             <div class="details">
            <p>
                <span class="detail-label">First Name</span><br>
                <span class="detail-answer"><?php echo htmlspecialchars(ucwords($user['FirstName'])); ?></span>
            </p>
            <p>
                <span class="detail-label">Last Name</span><br>
                <span class="detail-answer"><?php echo htmlspecialchars(ucwords($user['LastName'])); ?></span>
            </p>
            <p>
                <span class="detail-label">Email</span><br>
                <span class="detail-answer"><?php echo htmlspecialchars($user['Email']); ?></span>
            </p>
            <p>
                <span class="detail-label">Mobile</span><br>
                <span class="detail-answer"><?php echo htmlspecialchars($user['Mobile']); ?></span>
            </p>
        </div>
    </div>
    <!-- Edit Profile Button -->
<form action="edit_profile.php" method="GET">
    <button type="button" id="editButton" class="edit-btn1">
        <img src="workshops/edit-btn.png" alt="edit icon" class="edit-btn1">
        <span class="tooltip">Edit Profile</span>
    </button>
</form>
</section>


    
    <section class="content">
       <div class="bio">
    <h3>BIO</h3>
    <?php if (!empty($user['bio'])): ?>
        <p><?php echo htmlspecialchars($user['bio']); ?></p>
    <?php else: ?>
        <p style="font-size:12px;">No bio available.</p> <!-- Optional message if bio is empty -->
    <?php endif; ?>
</div>

       <div class="bookings">
   
           <button id="openBookingModal">  View Bookings </button>

    
    <div class="timeline">
        <img src="workshops/timeline.png" alt="timeline pic" class="booking-image">
        
  

       <?php
$eventCount = 1;

foreach ($bookings as $booking) {
    // Fix slashes for web
    $correctedPath = str_replace("\\", "/", $booking['imageURL']);
    $imageUrl = htmlspecialchars($correctedPath); // Safe for HTML

    echo '<div class="event' . $eventCount . '">';
    echo     '<span>' . htmlspecialchars($booking['Title']) . '</span>';
echo '<p>' . date('d M Y', strtotime($booking['BookingDate'])) . '</p>';
    echo '</div>';

       echo '<div class="event-photo' . $eventCount . '" style="background-image: url(\'' . $imageUrl . '\');"></div>';


    $eventCount++;
}
?>


    </div>
</div>
      </section>
    
      <!-- Modal Background -->
<div id="bookingModal" class="modal1">
  <!-- Modal Content -->
  <div class="modal-content1">
    <!-- Close Button -->
    <span class="close1">&times;</span>
    
    <!-- Tabs for ALL, Upcoming, and Completed bookings -->
    <div class="tabs">
      <button class="tablinks" onclick="openTab(event, 'All')">ALL</button>
      <button class="tablinks" onclick="openTab(event, 'Upcoming')">Upcoming</button>
      <button class="tablinks" onclick="openTab(event, 'Completed')">Completed</button>
    </div>
 <!-- Modal Content for All bookings -->
<div id="All" class="tabcontent">
  <?php if (!empty($upcomingBookings) || !empty($completedBookings)): ?>
    <?php foreach (array_merge($upcomingBookings, $completedBookings) as $booking): ?>
      <div class="booking-item" id="<?php echo $booking['BookingID']; ?>">

        <div class="booking-actions">
          <img src="workshops/edit-btn.png" class="edit-booking" data-id="<?php echo $booking['BookingID']; ?>">
          <img src="workshops/trash-btn.png" class="delete-booking" alt='delete' onclick="deleteBooking(<?php echo $booking['BookingID']; ?>)">
        </div>

        <img src="<?php echo $booking['imageURL']; ?>" alt="Workshop Image" style="width:100px; height:100px;">
        
        <div class='booking-info'>
          <p><strong>Booking ID:</strong> <?php echo $booking['BID']; ?></p>
          <p><strong>Workshop Title:</strong> <?php echo $booking['Title']; ?></p>
          <p><strong>Date:</strong><p class='booking-date'> <?php echo date('d M Y', strtotime($booking['Date'])); ?> </p></p>
          <p><strong>Time:</strong> <p class='booking-time'><?php echo date('H:i', strtotime($booking['StartTime'])) . ' - ' . date('H:i', strtotime($booking['EndTime'])); ?> </p></p>
        </div>

        <div class="workshop-info">
          <p><strong>Category:</strong> <?php echo $booking['Category']; ?></p>
          <p><strong>Location:</strong> <?php echo $booking['Location']; ?></p>
          <p><strong>Type [in-person/online]:</strong> <?php echo $booking['Type']; ?></p>


        <!-- New Section: Maps or Link -->
        <div class="access-info">
            <p><strong>Access:</strong></p>
          <?php
            $type = strtolower($booking['Type']);
            if ($type === 'online') {
              echo "<p>Zoom Link: <a href='#'>https://zoom.us/meeting8889-link</a></p>";
            } elseif ($type === 'in-person') {
              echo "<img src='workshops/map-placeholder.png' alt='Workshop Location Map' style='width:70px; height:auto; margin-left:90px; margin-top:-10px;'>";
            } elseif ($type === 'both') {
              echo "<p>Zoom Link: <a href='#'>https://zoom.us/meeting8889-link</a></p>";
              echo "<img src='workshops/map-placeholder.png' alt='Workshop Location Map' style='width:70px; height:auto; margin-left:90px; '>";
            }
          ?>
        </div>
  </div>
        <div class='orange-box'>
          <p class="totalPrice"><strong>Total Price: </strong> 
            <img src='workshops/riyal.png' alt='currency pic'><?php echo $booking['Price']; ?>
          </p>
          <a href="booking.php?workshopID=<?php echo $booking['WorkshopID']; ?>" class='rebook-btn'>Re-Book</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="empty-message">No bookings found.</p>
  <?php endif; ?>
</div>


<!-- Modal Content for Upcoming bookings -->
<div id="Upcoming" class="tabcontent">
  <?php if (!empty($upcomingBookings)): ?>
    <?php foreach ($upcomingBookings as $booking): ?>
<div class="booking-item" id="<?php echo $booking['BookingID']; ?>">
          
          <div class="booking-actions">
   <img src="workshops/edit-btn.png" class="edit-booking" data-id="<?php echo $booking['BookingID']; ?>">
        <img src="workshops/trash-btn.png" class="delete-booking" alt='delete' onclick="deleteBooking(<?php echo $booking['BookingID']; ?>)">
    </div>
          
        <img src="<?php echo $booking['imageURL']; ?>" alt="Workshop Image" style="width:100px; height:100px;">
        <div class='booking-info'>
          <p><strong>Booking ID:</strong> <?php echo $booking['BID']; ?></p>
          <p><strong> Workshop Title:</strong> <?php echo $booking['Title']; ?></p>
<p><strong>Date:</strong><p class='booking-date'> <?php echo date('d M Y', strtotime($booking['Date'])); ?> </p></p>
        <p><strong>Time:</strong> <p class='booking-time'><?php echo date('H:i', strtotime($booking['StartTime'])) . ' - ' . date('H:i', strtotime($booking['EndTime'])); ?> </p></p>
    </div>
        <div class="workshop-info">
          <p><strong>Category:</strong> <?php echo $booking['Category']; ?></p>
          <p><strong>Location:</strong> <?php echo $booking['Location']; ?></p>
          <p><strong>Type [in-person/online]:</strong> <?php echo $booking['Type']; ?></p>


        <!-- New Section: Maps or Link -->
        <div class="access-info">
            <p><strong>Access:</strong></p>
          <?php
            $type = strtolower($booking['Type']);
            if ($type === 'online') {
              echo "<p>Zoom Link: <a href='#'>https://zoom.us/meeting8889-link</a></p>";
            } elseif ($type === 'in-person') {
              echo "<img src='workshops/map-placeholder.png' alt='Workshop Location Map' style='width:70px; height:auto; margin-left:90px; margin-top:-10px;'>";
            } elseif ($type === 'both') {
              echo "<p>Zoom Link: <a href='#'>https://zoom.us/meeting8889-link</a></p>";
              echo "<img src='workshops/map-placeholder.png' alt='Workshop Location Map' style='width:70px; height:auto; margin-left:90px;'>";
            }
          ?>
        </div>
  </div>
        <div class='orange-box'>
          <p class="totalPrice"><strong> Total Price: </strong> 
            <img src='workshops/riyal.png' alt='currency pic'><?php echo $booking['Price']; ?>
          </p>
          <a href="booking.php?workshopID=<?php echo $booking['WorkshopID']; ?>" class='rebook-btn'>Re-Book </a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="empty-message">No upcoming bookings.</p>
  <?php endif; ?>
</div>
<!-- Completed bookings section -->
<div id="Completed" class="tabcontent">
    <?php if (!empty($completedBookings)): ?>
        <?php foreach ($completedBookings as $booking): ?>
            <div class="booking-item" id="booking-<?php echo $booking['BookingID']; ?>">
                

                <img src="<?php echo $booking['imageURL']; ?>" alt="Workshop Image" style="width:100px; height:100px;">
                <div class="booking-info">
                    <p><strong>Booking ID:</strong> <?php echo $booking['BID']; ?></p>
                    <p><strong>Workshop Title:</strong> <?php echo $booking['Title']; ?></p>
                   <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($booking['Date'])); ?></p>
<p><strong>Time:</strong> <?php echo date('H:i', strtotime($booking['StartTime'])) . ' - ' . date('H:i', strtotime($booking['EndTime'])); ?></p>

                </div>
                <div class="workshop-info">
          <p><strong>Category:</strong> <?php echo $booking['Category']; ?></p>
          <p><strong>Location:</strong> <?php echo $booking['Location']; ?></p>
          <p><strong>Type [in-person/online]:</strong> <?php echo $booking['Type']; ?></p>


        <!-- New Section: Maps or Link -->
        <div class="access-info">
            <p><strong>Access:</strong></p>
          <?php
            $type = strtolower($booking['Type']);
            if ($type === 'online') {
              echo "<p>Zoom Link: <a href='#'>https://zoom.us/meeting8889-link</a></p>";
            } elseif ($type === 'in-person') {
              echo "<img src='workshops/map-placeholder.png' alt='Workshop Location Map' style='width:70px; height:auto; margin-left:90px; margin-top:-10px;'>";
            } elseif ($type === 'both') {
              echo "<p>Zoom Link: <a href='#'>https://zoom.us/meeting8889-link</a></p>";
              echo "<img src='workshops/map-placeholder.png' alt='Workshop Location Map' style='width:70px; height:auto; margin-left:90px;'>";
            }
          ?>
        </div>
  </div>
                <div class="orange-box">
                    <p class="totalPrice"><strong>Total Price:</strong> 
                        <img src="workshops/riyal.png" alt="currency pic"><?php echo $booking['Price']; ?>
                    </p>
                    <!-- Submit Review Button -->
                    <button id="review-btn-<?php echo $booking['BookingID']; ?>" class="review-btn" data-workshopid="<?php echo $booking['WorkshopID']; ?>" data-bookingid="<?php echo $booking['BookingID']; ?>">Submit Review</button>
          <a href="booking.php?workshopID=<?php echo $booking['WorkshopID']; ?>" class='rebook-btn'>Re-Book </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty-message">No completed bookings.</p>
    <?php endif; ?>
</div>

  </div>
</div>


        <div class="title-addbtn">
        <div class="experiences">
            <h3>My Experiences 
                <button class="add-post-btn2" id="addPostBtn">
                    <img src="workshops/add-post-btn.png" alt="Add Post">
                    <span class="tooltip">Add New Post</span>
                </button> <!-- Add button for adding a new post -->
            </h3>  </div> </div>
           

        <section class="bottom-section" >
    
            <div class="experience-cards" id="posts-container">

                    </div>
            
                
                  <div class="wishlist" id="workshops-container">
    <div class="wishlist-header">
        <h3>WISHLIST</h3> <!-- Title displayed once -->
    </div>
    <div class="wishlist-items">
        <!-- Dynamic content will be added here -->
    </div>
</div>



                    
                    

    </section>
   
    
    
    <!-- Modal for Edit Profile -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Profile</h2>

        <!-- Profile picture fixed under the heading -->
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
        <div class="profile-picture-container">
            <img id="currentProfilePic" src="<?php echo !empty($user['ProfilePhoto']) ? 'uploads/' . $user['ProfilePhoto'] : 'uploads/default.jpg'; ?>" alt="Profile Picture" class="current-picture">
            <input  style="margin-top:20px;" type="file" name="profile_photo" id="profilePhotoInput">
        </div>
            <!-- Form inputs for user information -->
        <div class="form-input">
            
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="first_name" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required><br>

                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="last_name" value="<?php echo htmlspecialchars($user['LastName']); ?>" required><br>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required><br>

                <label for="mobile">Mobile</label>
                <input type="tel" pattern="\+?[0-9]{1,4}[\s\-]?[0-9]+[\s\-]?[0-9]+[\s\-]?[0-9]+" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['Mobile']); ?>" required><br>

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio'] ?: ''); ?></textarea><br>

                <button type="submit" name="update_profile" id="updateProfile">Save Changes</button>
            </form>
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

<!-- First, load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Then, load jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Load other required libraries -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery Bar Rating -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/themes/fontawesome-stars.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/jquery.barrating.min.js"></script>

<!-- Your custom JavaScript file -->
<script src='profile-page.js'></script>

<script>

function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
}

</script>

</body>
</html>