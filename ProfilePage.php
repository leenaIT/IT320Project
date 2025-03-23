<?php
session_start();
ini_set('display_errors',1);
require 'database.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data from database
$sql = "SELECT FirstName, LastName, Email, Mobile, ProfilePhoto,bio FROM users WHERE UserID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Set default profile photo if not uploaded
$photo = !empty($user['ProfilePhoto']) ? 'uploads/' . $user['ProfilePhoto'] : 'uploads/default.jpg';

// Fetch last 4 bookings for the user with workshop title
$sql = "SELECT w.Title, w.imageURL, b.BookingDate FROM booking b
        JOIN workshop w ON b.WorkshopID = w.WorkshopID
        WHERE b.UserID = ? 
        ORDER BY b.BookingDate DESC LIMIT 5"; // Limiting to last 4 bookings
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);



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
    <button type="button" id="editButton">
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
    <h3>
        <a href="booking-history.php">My Bookings</a>
    </h3>
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


    
    <script>
       
    
       let isMouseDown = false;
let startX;
let scrollLeft;

const cardsContainer = document.querySelector('.experience-cards');

cardsContainer.addEventListener('mousedown', (e) => {
  isMouseDown = true;
  startX = e.pageX - cardsContainer.offsetLeft;
  scrollLeft = cardsContainer.scrollLeft;
  cardsContainer.style.cursor = 'grabbing';
});

cardsContainer.addEventListener('mouseleave', () => {
  isMouseDown = false;
  cardsContainer.style.cursor = 'grab';
});

cardsContainer.addEventListener('mouseup', () => {
  isMouseDown = false;
  cardsContainer.style.cursor = 'grab';
});

cardsContainer.addEventListener('mousemove', (e) => {
  if (!isMouseDown) return; // Only move if mouse is down
  e.preventDefault();
  const x = e.pageX - cardsContainer.offsetLeft;
  const walk = (x - startX) * 2; // Adjust scrolling speed
  cardsContainer.scrollLeft = scrollLeft - walk;
});



const wishlistItemsContainer = document.querySelector('.wishlist-items'); // Select the wishlist-items container

wishlistItemsContainer.addEventListener('mousedown', (e) => {
  isMouseDown = true;
  startX = e.pageX - wishlistItemsContainer.offsetLeft;
  scrollLeft = wishlistItemsContainer.scrollLeft;
  wishlistItemsContainer.style.cursor = 'grabbing'; // Change cursor to grabbing
});

wishlistItemsContainer.addEventListener('mouseleave', () => {
  isMouseDown = false;
  wishlistItemsContainer.style.cursor = 'grab'; // Change cursor to grab when mouse leaves
});

wishlistItemsContainer.addEventListener('mouseup', () => {
  isMouseDown = false;
  wishlistItemsContainer.style.cursor = 'grab'; // Change cursor to grab when mouse is released
});

wishlistItemsContainer.addEventListener('mousemove', (e) => {
  if (!isMouseDown) return; // Only move if mouse is down
  e.preventDefault();
  const x = e.pageX - wishlistItemsContainer.offsetLeft;
  const walk = (x - startX) * 2; // Adjust scrolling speed (increase multiplier for faster scroll)
  
});

// Event listener to handle loading images
window.addEventListener('load', () => {
    document.querySelectorAll('.wishlist-img').forEach((img) => {
        img.classList.add('loaded');
    });
});


    document.addEventListener("DOMContentLoaded", function() {
        // Get the modal and button elements
        const modal = document.getElementById("editProfileModal");
        const btn = document.getElementById("editButton");
        const span = document.querySelector(".close");

        // Ensure elements exist before adding event listeners
        if (modal && btn && span) {
            // Show the modal when the Edit button is clicked
            btn.addEventListener("click", function(event) {
                event.preventDefault(); // Prevent any default action if it's a form
                modal.style.display = "block"; // Show the modal
            });

            // Close the modal when the close button (X) is clicked
            span.onclick = function() {
                modal.style.display = "none"; // Hide the modal
            }

            // Close the modal if the user clicks outside of it
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none"; // Hide the modal if clicked outside
                }
            }
        }
    });


</script>


</body>
</html>
