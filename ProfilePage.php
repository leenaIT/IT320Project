<?php
session_start();
ini_set('display_errors',1);
require 'database.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: hoempage.php");
    exit();
}

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
$sql2 = "SELECT w.Title, w.imageURL, b.BookingDate FROM booking b
        JOIN workshop w ON b.WorkshopID = w.WorkshopID
        WHERE b.UserID = ? 
        ORDER BY b.BookingDate DESC LIMIT 5"; // Limiting to last 4 bookings
$stmt2 = $connection->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$bookings = $result2->fetch_all(MYSQLI_ASSOC);


// Query to get bookings for the specific user, including workshop details
$query = "
    SELECT b.BookingID,b.BID, b.BookingDate, b.UserID, b.WorkshopID, 
           w.ImageURL, w.Category, w.Location, w.Type, w.Price,w.Title 
    FROM booking b
    JOIN workshop w ON b.WorkshopID = w.WorkshopID
    WHERE b.UserID = ?
    ORDER BY b.BookingDate DESC";
$stmt3 = $connection->prepare($query);
$stmt3->bind_param('i', $user_id);  // Bind the userId to the query
$stmt3->execute();
$result3 = $stmt3->get_result();


// Separate bookings into upcoming and completed
$upcomingBookings = [];
$completedBookings = [];

$currentDateTime = date('Y-m-d H:i:s');

while ($booking = $result3->fetch_assoc()) {
    // Compare the current date with the booking's date
    if ($booking['BookingDate'] < $currentDateTime) {
        // If the booking date is in the past, mark it as completed
        $completedBookings[] = $booking;
    } else {
        // Otherwise, mark it as upcoming
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
    <link rel="stylesheet" href="header-footer2.css">
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

 header{
    margin-bottom: 60px;
  
 }

.footer{
    background-color: #FFFDF0;
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
    </style>
</head>

<body>
    <div class="header-container1">
    <!-- navbar html -->
     <header>
<div class="nav">
    <span><a href="homePage.php">Home</a></span>
    <span><a href="Explore.php">Explore</a></span>
    <span><a href="ProfilePage.php">Profile</a></span>
    <span><a href="findcategory.php">Category</a></span>
    

    <div> <span class="language-switch" onclick="toggleLanguage()">🌐 Language</span></div>
</div>
     </header> 
        </div>
   <section class="profile-header">
    <div class="user-info">
        <div class="profile-picture"> </div>
        <div class="details">
            <p>
                <span class="detail-title1">User <br></span>
                <span class="detail-title2">Profile</span>
            </p>
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
        <p>No bio available.</p> <!-- Optional message if bio is empty -->
    <?php endif; ?>
</div>

       <div class="bookings">
   
           <button id="openBookingModal">  View Bookings </button>

    
    <div class="timeline">
        <img src="workshops/timeline.png" alt="timeline pic" class="booking-image">
        
  

        <?php
        $eventCount = 1;
        foreach ($bookings as $booking) {
            $workshopImage = 'url(' . htmlspecialchars($booking['imageURL']) . ')';

            echo '<div class="event' . $eventCount . '">';
            echo '<span>' . htmlspecialchars($booking['Title']) . '</span>';
            echo '<p>' . date('d F Y', strtotime($booking['BookingDate'])) . '</p>';
            echo '</div>';

            echo '<div class="event-photo' . $eventCount . '" style="background-image: ' . $workshopImage . ';"></div>';
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
          
        <img src="<?php echo $booking['ImageURL']; ?>" alt="Workshop Image" style="width:100px; height:100px;">
        <div class='booking-info'>
          <p><strong>Booking ID:</strong> <?php echo $booking['BID']; ?></p>
          <p><strong> Workshop Title:</strong> <?php echo $booking['Title']; ?></p>
        <p><strong>Booking Date:</strong></p> <p class='booking-date'> <?php echo $booking['BookingDate']; ?> </p>
        </div>
        <div class="workshop-info">
          <p><strong>Category:</strong> <?php echo $booking['Category']; ?></p>
          <p><strong>Location:</strong> <?php echo $booking['Location']; ?></p>
          <p><strong> Type [in-person/online]:</strong> <?php echo $booking['Type']; ?></p>
        </div>
        <div class='orange-box'>
          <p class="totalPrice"><strong> Total Price: </strong> 
            <img src='workshops/riyal.png' alt='currency pic'><?php echo $booking['Price']; ?>
          </p>
          <button class='rebook-btn'>Book Now </button>
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
          
        <img src="<?php echo $booking['ImageURL']; ?>" alt="Workshop Image" style="width:100px; height:100px;">
        <div class='booking-info'>
          <p><strong>Booking ID:</strong> <?php echo $booking['BID']; ?></p>
          <p><strong> Workshop Title:</strong> <?php echo $booking['Title']; ?></p>
          <p><strong>Booking Date:</strong></p> <p class='booking-date'> <?php echo $booking['BookingDate']; ?> </p>
        </div>
        <div class="workshop-info">
          <p><strong>Category:</strong> <?php echo $booking['Category']; ?></p>
          <p><strong>Location:</strong> <?php echo $booking['Location']; ?></p>
          <p><strong> Type [in-person/online]:</strong> <?php echo $booking['Type']; ?></p>
        </div>
        <div class='orange-box'>
          <p class="totalPrice"><strong> Total Price: </strong> 
            <img src='workshops/riyal.png' alt='currency pic'><?php echo $booking['Price']; ?>
          </p>
          <button class='rebook-btn'>Book Now </button>
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
                <div class="booking-actions">
                    <img src="workshops/edit-btn.png" class="edit-booking" data-id="<?php echo $booking['BookingID']; ?>">
                    <img src="workshops/trash-btn.png" class="delete-booking" alt="delete" onclick="deleteBooking(<?php echo $booking['BookingID']; ?>)">
                </div>

                <img src="<?php echo $booking['ImageURL']; ?>" alt="Workshop Image" style="width:100px; height:100px;">
                <div class="booking-info">
                    <p><strong>Booking ID:</strong> <?php echo $booking['BID']; ?></p>
                    <p><strong>Workshop Title:</strong> <?php echo $booking['Title']; ?></p>
                    <p><strong>Booking Date:</strong></p>
                    <p class="booking-date"><?php echo $booking['BookingDate']; ?></p>
                </div>
                <div class="workshop-info">
                    <p><strong>Category:</strong> <?php echo $booking['Category']; ?></p>
                    <p><strong>Location:</strong> <?php echo $booking['Location']; ?></p>
                    <p><strong>Type [in-person/online]:</strong> <?php echo $booking['Type']; ?></p>
                </div>
                <div class="orange-box">
                    <p class="totalPrice"><strong>Total Price:</strong> 
                        <img src="workshops/riyal.png" alt="currency pic"><?php echo $booking['Price']; ?>
                    </p>
                    <!-- Submit Review Button -->
                    <div id="review-section-<?php echo $booking['BookingID']; ?>"></div> <!-- Review section -->
                    <button id="review-btn-<?php echo $booking['BookingID']; ?>" class="review-btn" data-workshopid="<?php echo $booking['WorkshopID']; ?>" data-bookingid="<?php echo $booking['BookingID']; ?>">Submit Review</button>
                    <button class="rebook-btn">Book Now</button>
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
                <button class="add-post-btn2">
                    <img src="workshops/add-post-btn.png" alt="Add Post">
                    <span class="tooltip">Add New Post</span>
                </button> <!-- Add button for adding a new post -->
            </h3>  </div> </div>
           

        <section class="bottom-section">
    
            <div class="experience-cards">



                <div class="card first-card" >
                    <div class="image-placeholder1"></div>
                    <div class="post-name"> Workshop 1</div>
                    <div class="card-content">
                        <div class="profile">
                            <div class="user-circle1"></div>
                            <p><strong>Reema</strong></p>
                        </div>
                        <p class="experience-text">The best workshop ever! I enjoyed it a lot, definitely would come again.</p>
                    </div>
                    <div class="post-btn">
                        <button class="edit-btn">
                            <img src="workshops/edit-btn.png" alt="Edit">
                            <span class="tooltip">  Edit Post </span>
                        </button>
                        <button class="delete-btn">
                            <img src="workshops/trash-btn.png" alt="Delete">
                            <span class="tooltip">  Delete Post </span>
                        </button>
                    </div>
                </div>
            
                
                <div class="card">
                    <div class="image-placeholder2"></div>
                    <div class="post-name"> Workshop 2</div>
                    <div class="card-content">
                        <div class="profile">
                            <i class="user-circle2"></i>
                            <p><strong>Reema</strong></p>
                        </div>
                        <p class="experience-text">An amazing experience, I learned a lot and had fun.The instructor is the best </p>
                    </div>
                    <div class="post-btn">
                        <button class="edit-btn">
                            <img src="workshops/edit-btn.png" alt="Edit">
                            <span class="tooltip">  Edit Post </span>
                        </button>
                        <button class="delete-btn">
                            <img src="workshops/trash-btn.png" alt="Delete">
                            <span class="tooltip">  Delete Post </span>
                        </button>
                    </div>
                </div>
    
                <div class="card">
                    <div class="image-placeholder3"></div>
                    <div class="post-name"> Workshop 3</div>
                    <div class="card-content">
                        <div class="profile">
                            <i class="user-circle3"></i>
                            <p><strong>Reema</strong></p>
                        </div>
                        <p class="experience-text">Highly recommend this Activity to everyone especially with your friends</p>
                    </div>
                    <div class="post-btn">
                        <button class="edit-btn">
                            <img src="workshops/edit-btn.png" alt="Edit">
                            <span class="tooltip">  Edit Post </span>
                        </button>
                        <button class="delete-btn">
                            <img src="workshops/trash-btn.png" alt="Delete">
                            <span class="tooltip">  Delete Post </span>
                        </button>
                    </div>
                </div>
    
            
                
                <div class="card fourth-card">
                    <div class="image-placeholder4"></div>
                    <div class="post-name"> Workshop 4</div>
                    <div class="card-content">
                        <div class="profile">
                            <i class="user-circle4"></i>
                            <p><strong>Reema</strong></p>
                        </div>
                        <p class="experience-text">such a unique experience! Everyone has to try this at least once </p>
                    </div>
                    <div class="post-btn">
                        <button class="edit-btn">
                            <img src="workshops/edit-btn.png" alt="Edit">
                            <span class="tooltip">  Edit Post </span>
                        </button>
                        <button class="delete-btn">
                            <img src="workshops/trash-btn.png" alt="Delete">
                            <span class="tooltip">  Delete Post </span>
                        </button>
                    </div>
                </div>
                
                <div class="card" >
                    <div class="image-placeholder5"></div>
                    <div class="post-name"> Workshop 5</div>
                    <div class="card-content">
                        <div class="profile">
                            <i class="user-circle4"></i>
                            <p><strong>Reema</strong></p>
                        </div>
                        <p class="experience-text"> what a better way to start  your weeknend than making your own candle scent </p>
                    </div>
                    <div class="post-btn">
                        <button class="edit-btn">
                            <img src="workshops/edit-btn.png" alt="Edit">
                            <span class="tooltip">  Edit Post </span>
                        </button>
                        <button class="delete-btn">
                            <img src="workshops/trash-btn.png" alt="Delete">
                            <span class="tooltip">  Delete Post </span>
                        </button>
                    </div>
                </div>
                
                <div class="card" >
                    <div class="image-placeholder6"></div>
                    <div class="post-name"> Workshop 6</div>
                    <div class="card-content">
                        <div class="profile">
                            <i class="user-circle4"></i>
                            <p><strong>Reema</strong></p>
                        </div>
                        <p class="experience-text">  this is was the first time i ever tried pottery even though i failed miserably at it , it wss soo much fun </p>
                    </div>
                    <div class="post-btn">
                        <button class="edit-btn">
                            <img src="workshops/edit-btn.png" alt="Edit">
                            <span class="tooltip">  Edit Post </span>
                        </button>
                        <button class="delete-btn">
                            <img src="workshops/trash-btn.png" alt="Delete">
                            <span class="tooltip">  Delete Post </span>
                        </button>
                    </div>
                </div>

                    </div>
                
                    <div class="wishlist">
                        <h3 style="text-align: center; margin-right: 9px;">WISHLIST</h3>
                        <div class="wishlist-items">
                            <!-- Start of wishlist item -->
                            <div class="wishlist-item">
                            
                                <div class="wishlist-img">
                                    <img src="workshops/image1.jpg" alt="Workshop Image">
                                </div>
                                <div class="wishlist-info">
                                    <p class="workshop-name">Candle Making</p>
                                    <div class="price-section">
                                        <img src="workshops/riyal.png" alt="SR" class="currency-icon">
                                        <p class="price">140</p>
                                    </div>
                                    <div class="wishlist-actions">
                                        <button class="book-now"> Book Now </button>
                                        <button class="wishlist-star">
                                            <img src="workshops/filled-star.png" alt="Favorite">
                                            <span class="tooltip">Remove Favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr class="wishlist-divider">
                    
                            <!-- 2nd wishlist -->
                            <div class="wishlist-item">
                                <div class="wishlist-img">
                                    <img src="workshops/top-view-attractive-woman-hands-drawing-amazing-picture-canvas-modern-cozy-art-workshop.jpg" alt="Workshop Image">
                                </div>
                                <div class="wishlist-info">
                                    <p class="workshop-name">Canvas Creations</p>
                                    <div class="price-section">
                                        <img src="workshops/riyal.png" alt="SR" class="currency-icon">
                                        <p class="price">80</p>
                                    </div>
                                    <div class="wishlist-actions">
                                        <button class="book-now"> Book Now </button>
                                        <button class="wishlist-star">
                                            <img src="workshops/filled-star.png" alt="Favorite">
                                            <span class="tooltip">Remove Favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr class="wishlist-divider">
                    
                            <!-- 3rd wishlist -->
                            <div class="wishlist-item">
                                <div class="wishlist-img">
                                    <img src="workshops/image1.jpg" alt="Workshop Image">
                                </div>
                                <div class="wishlist-info">
                                    <p class="workshop-name">The Art of Pottery</p>
                                    <div class="price-section">
                                        <img src="workshops/riyal.png" alt="SR" class="currency-icon">
                                        <p class="price">120</p>
                                    </div>
                                    <div class="wishlist-actions">
                                        <button class="book-now"> Book Now </button>
                                        <button class="wishlist-star">
                                            <img src="workshops/filled-star.png" alt="Favorite">
                                            <span class="tooltip">Remove Favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr class="wishlist-divider">
                    
                            <!-- 4th wishlist -->
                            <div class="wishlist-item">
                                <div class="wishlist-img">
                                    <img src="workshops/image4.jpg" alt="Workshop Image">
                                </div>
                                <div class="wishlist-info">
                                    <p class="workshop-name">Tarifa</p>
                                    <div class="price-section">
                                        <img src="workshops/riyal.png" alt="SR" class="currency-icon">
                                        <p class="price">450</p>
                                    </div>
                                    <div class="wishlist-actions">
                                        <button class="book-now"> Book Now </button>
                                        <button class="wishlist-star">
                                            <img src="workshops/filled-star.png" alt="Favorite">
                                            <span class="tooltip">Remove Favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr class="wishlist-divider">
                    
                            <!-- 5th wishlist -->
                            <div class="wishlist-item">
                                <div class="wishlist-img">
                                    <img src="workshops/workshop6.jpeg" alt="Workshop Image">
                                </div>
                                <div class="wishlist-info">
                                    <p class="workshop-name">Dough & Fire</p>
                                    <div class="price-section">
                                        <img src="workshops/riyal.png" alt="SR" class="currency-icon">
                                        <p class="price">90</p>
                                    </div>
                                    <div class="wishlist-actions">
                                        <button class="book-now"> Book Now </button>
                                        <button class="wishlist-star">
                                            <img src="workshops/filled-star.png" alt="Favorite">
                                            <span class="tooltip">Remove Favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr class="wishlist-divider">
                        </div>
                    </div>
                    
                    

    </section>
    <button class="view-all-btn">View All 
        <span class="tooltip"> View All Posts</span>
    </button>
    
    <!-- footer.html -->
     <hr style="color:black; border-width:2px;">
<footer class="footer" id="footer">
    <div class="footer-content">
        <div class="footer-left">
            <h4>Get In Touch</h4>
            <div class="contact-info">
                <div class="contact-item">
                    <img src="workshops/360_F_553663238_v4Tva6Ie5Z5MhwCw0TknszcWuQ1ZAwQx.png" alt="Phone">
                </div>
                <div class="contact-item">
                    <img id="email" src="workshops/360_F_181003490_CxW4fQ0H3VypIIsPkFGpMDviO8ysWjOZ.png" alt="Email">
                </div>
                <div class="contact-item">
                    <img id="location" src="workshops/360_F_254622588_6OClHyYpak64rVI8y9QVjUvDlStsDEu9.png" alt="Location">
                </div>
            </div>
        </div>
        <div class="footer-right">
            <h4>Follow Us</h4>
            <div class="social-icons">
                <img id="facebook" src="workshops/black-square-outline-facebook-icon-7017516951347228u34mgnk68.png" alt="Facebook">
                <img src="workshops/twitter-icon-256x227-kf6zqma5.png" alt="Twitter">
                <img src="workshops/121.png" alt="Instagram">
            </div>
        </div>
    </div>
    <div class="footer-center">
        
        <p>© 2024 Website. All rights reserved.</p>
    </div> 
</footer>

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

    <script src='profile-page.js'> </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


</body>
</html>
