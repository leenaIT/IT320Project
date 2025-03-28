<?php
session_start();
require 'database.php';

if (!isset($_GET['workshopID'])) {
    echo "Workshop ID is missing.";
    exit;
}

$workshopID = $_GET['workshopID'];

$stmt = $connection->prepare("SELECT Title, LongDes, Location, Duration, Age, Price, ImageURL FROM workshop WHERE WorkshopID = ?");
$stmt->bind_param("i", $workshopID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Workshop not found.";
    exit;
}


$workshop = $result->fetch_assoc();

$schedule_stmt = $connection->prepare("SELECT Day, Date, StartTime, EndTime FROM workshop_schedule WHERE WorkshopID = ?");
$schedule_stmt->bind_param("i", $workshopID);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();



$works_stmt = $connection->prepare("SELECT ImageURL, ClientName, CreatedAt FROM previous_works WHERE WorkshopID = ?");
$works_stmt->bind_param("i", $workshopID);
$works_stmt->execute();
$works_result = $works_stmt->get_result();



$review_stmt = $connection->prepare("
    SELECT r.Rating, r.Comment, u.FirstName, u.LastName
    FROM review r
    JOIN users u ON r.UserID = u.UserID
    WHERE r.WorkshopID = ?
");
$review_stmt->bind_param("i", $workshopID);
$review_stmt->execute();
$review_result = $review_stmt->get_result();



function timeAgo($datetime) {
    $now = new DateTime();
    $created = new DateTime($datetime);
    $interval = $now->diff($created);

    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d >= 7) {
        $weeks = floor($interval->d / 7);
        return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'Just now';
    }
}



function renderStars($rating) {
    $full = str_repeat('★', $rating);
    $empty = str_repeat('☆', 5 - $rating);
    return $full . $empty;
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>booking</title>
    <link rel="stylesheet" href="header-footer2.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
<style>
body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: #FFFDF0;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #444;
            

        }
    
.header {
            position: relative;
            width: 100%;
            height: 655px;
            background: url('workshops/candle.jpeg') no-repeat center center/cover;
            color: white;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .header::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .header-content {
    z-index: 1;
    margin: 0 auto;
    text-align: center;
    width: 100%;
}


        .header-content h1 {
            font-family: 'Anton', sans-serif;
            font-size: 70px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
            display: flex;
            flex-direction: column;
            line-height: 1;
        }

        .header-content h1 span {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1.5s ease-out forwards;
        }

        .header-content h1 span:nth-child(1) {
            animation-delay: 0.5s;
        }

        .header-content h1 span:nth-child(2) {
            animation-delay: 1s;
        }



        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .outline-text {
            color: transparent;
            -webkit-text-stroke: 2px white;
            font-size: 130px;
        }

        .header-content .quote {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            color: #f4d47c;
            margin-top: 15px;
            font-size: 20px;
            opacity: 0;
            animation: fadeIn 2s ease-out 1.5s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }





        .box-container {
            background: #fdfdf9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 70px 70px 0px 0px; 
            padding: 20px;
            text-align: center;
            position: relative;
            margin: -60px 0px 50px 0px;
            z-index: 10; 
        }


        .timeline-img {
            width: 100%;
            max-width: 1000px;
        }

        .timeline-text {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
            font-size: 14px;
            color: #444;
            font-weight: 600;
        }

        .timeline-text div {
            width: 30%;
            text-align: center;
        }


        .time-button:hover::before {
    background: #63040400;
}

        #workshop-times {
    display: flex;
    justify-content: center; 
    gap: 20px; 
    text-align: center;
    padding: 0px 120px;
}

.time-button {
    position: relative;
    background: #630404af; 
    border: none;
    padding: 15px;
    margin: 20px auto;
    cursor: pointer;
    border-radius: 20px;
    width: 15%;
    height: auto;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.time-button::before {
    content: "";
    position: absolute;
    width: 88%;
    height: 83%;
    background: white;
    border-radius: 15px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
}

.time-button span {
    position: relative;
    z-index: 2;
    color: #444;
    font-size: 14px;
    text-align: center;
}

.time-button.selected::before {
    background: #63040400; 
}

.time-button.selected span {
    color: white; 
}


        .price-section {
            display: flex;
            align-items: center;
            margin-left: 220px;
            gap: 0.2em;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
            margin-right: 5px;
        }

        .currency-icon {
            width: 20px;
            height: 20px;
        }

        .line {
        width: 70%;
        height: 1px;
        background: #444;
        
        flex: 1;
        margin-left: 15%;
    }


    #sub {
            background-color: #000000b5;
            color: #ffffff;
            border: none;
            padding: 10px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 15px;
            width: 100px;
            margin-left: 68.3%;
        }









.experience-cards {
    display: flex;
    flex-wrap: nowrap;
    justify-content: left;
    align-items: center;;
    margin-bottom:10px;
    width:90%;
    height: 100%;
    gap: 3em;
    scroll-behavior: smooth;
    overflow-x: auto;  
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    margin-left: 55px;  
    margin-top: -150px;
    margin-bottom: -150px;
}

.experience-cards::-webkit-scrollbar {
    display: none;
}  





.card p strong{
    margin-left:25px;
    color: black;
    
}

.card {
    flex-shrink: 0;
    flex-grow: 1;
    width: 25%; 
    background: white;
    border-radius: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 15px;
    text-align: center;
    transition: transform 0.2s ease-in-out;
    margin: 0;
    height: auto;
    scroll-snap-align: start;
    box-sizing: border-box;
}

.card .content {
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis; 
    word-wrap: break-word; 
}

.image-placeholder1{
    background-image: url("workshops/candle2.jpg");
    background-position: -60%;
    background-position:  center; 
    background-size: 100%; 
    width: 100%;
    height: 200px;
    border-radius: 20px;
}

.image-placeholder2{
    background-image: url("workshops/candle3.jpg"); 
    background-position: -60%;
    background-position:  center; 
    background-size: 100%; 
    width: 100%;
    height: 200px;
    border-radius: 20px;
}

.image-placeholder3{
    background-image: url("workshops/candle4.jpg");
    background-position: -60%;
    background-position:  center; 
    background-size: 100%; 
    width: 100%;
    height: 200px;
    border-radius: 20px;
}


.image-placeholder4{
    background-image: url("workshops/candle5.jpg");
    background-position: -60%;
    background-position: bottom center; 
    background-size: 100%; 
    width: 100%;
    height: 200px;
    border-radius: 20px;
}
.image-placeholder5{
    background-image: url("workshops/candle6.jpg");
    background-position: -60%;
    background-position:  center; 
    background-size: 100%; 
    width: 100%;
    height: 200px;
    border-radius: 20px;
}
.image-placeholder6{
    background-image:url("workshops/candle7.jpg");
    background-position: -60%;
    background-position:   bottom center; 
    background-size: 100%; 
    width: 100%;
    height: 200px;
    border-radius: 20px;
}


.card-content {
    margin-top: 15px;
    background: #f5f5f5;
    border-radius: 5px;
    
}

.post-name{
margin-top:10px;
color:darkred;
font-size:11px;
text-align: center;
}

.profile {
    display: flex;
    align-items: center;
    gap: 10px;
    text-align: center;
}













   
.carousel-item {
    position: absolute;
      width: 400px;
      height: 250px;
      opacity: 0.5;
      transform: scale(0.8);
      transition: all 0.5s ease;
      text-align: center;
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 87, 125, 0.2);
      padding: 20px;

      word-wrap: break-word; 
    box-sizing: border-box; 
  }

  .carousel-item img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    position: absolute;
    top: -30px;
    left: 30px;
    border: 4px solid #ffffff; 
  }

  .carousel-item h3 {
    margin: 10px 0 5px;
    font-size: 1.2em;
    margin-top: 30px;
    color: #f4d47c;
  }



  .carousel-item.active {
    opacity: 1;
    transform: scale(1);
    z-index: 3;
    left: 50%;
    transform: translateX(-50%) scale(1);
  }

  .carousel-item.left {
    left: 1%;
    transform: translateX(0) scale(0.8);
    z-index: 2;
  }

  .carousel-item.right {
    left: 67.5%;
    transform: translateX(0) scale(0.8);
    z-index: 2;
  }

  .carousel-item.hidden {
    opacity: 0;
    z-index: 1;
    transform: scale(0.6);
  }

  .reviews {
    position: relative;
      width: 100%;
      height: 300px;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      overflow: visible;
}

.reviews p{
    font-size: 14px;
}
#cli {
    font-size: 36px;
    font-weight: bold;
    color: #444;
    text-align: center;
    
}

.stars{
    color:darkred;
}

</style>
</head>
<body>
<!-- navbar.html -->
<div class="header" style="background: url('<?php echo $workshop['ImageURL']; ?>') no-repeat center center/cover;">
    <header>
        <div class="nav">
            <span><a href="homePage.html">Home</a></span>
            <span><a href="Explore.html">Explore</a></span>
            <span><a href="ProfilePage.html">Profile</a></span>
            <span><a href="findcategory.html">Category</a></span>
            <span><div class="language-switch" onclick="toggleLanguage()">🌐 Language</div></span>
        </div>
    </header>  
    <div class="header-content">
        <h1>
            <br><span class="outline-text"><?php echo htmlspecialchars($workshop['Title']); ?></span><br>
        </h1>
        <p class="quote"><?php echo nl2br(htmlspecialchars($workshop['LongDes'])); ?></p>
    </div>
</div>



<div class="box-container">
    <br><br><img src="workshops/line2.png" alt="Timeline" class="timeline-img">
    <div class="timeline-text">
    <div><?php echo htmlspecialchars($workshop['Location']); ?></div>
    <div><?php echo htmlspecialchars($workshop['Duration']); ?></div>
    <div><?php echo '+' . htmlspecialchars($workshop['Age']) . ' years'; ?></div>
</div>

    
    <br><br><br>
    
    <div>Available Time</div><br><br>
<div id="workshop-times">
    <?php
    $timeIndex = 1;
    while ($row = $schedule_result->fetch_assoc()):
        $day = $row['Day'];
        $date = date("F j, Y", strtotime($row['Date']));
        $startTime = date("g:i A", strtotime($row['StartTime']));
        $endTime = date("g:i A", strtotime($row['EndTime']));
        $buttonId = "time" . $timeIndex;
    ?>
        <button class="time-button" id="<?php echo $buttonId; ?>" onclick="selectTime('<?php echo $buttonId; ?>')">
            <span><?php echo "$day<br> $date <br> $startTime - $endTime"; ?></span>
        </button>
    <?php
        $timeIndex++;
    endwhile;
    ?>
</div>




    <br><br><div class="line"><p></p></div>

    <div class="price-section">
        <img src="workshops/riyal.png" alt="SR" class="currency-icon">
        <p class="price"><?php echo htmlspecialchars($workshop['Price']); ?></p>
        <button id="sub" onclick="confirmBooking()">Confirm Booking</button>
    </div>

    
</div>



    
<div class="experience-cards">
    <?php while ($row = $works_result->fetch_assoc()): ?>
        <div class="card">
            <div style="background-image: url('<?php echo $row['ImageURL']; ?>'); background-size: cover; background-position: center; height: 200px; border-radius: 20px;"></div>
            <div class="post-name"><?php echo timeAgo($row['CreatedAt']); ?></div>
            <div class="card-content">
                <div class="profile">
                    <p><strong><?php echo htmlspecialchars($row['ClientName']); ?></strong></p>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>


   
    <h2 id="cli">Client Testimonials</h2><br>
<div class="reviews">
<?php
$index = 0;
$positions = ['left', 'active', 'right'];
while ($row = $review_result->fetch_assoc()):
    $positionClass = $index < 3 ? $positions[$index] : ''; // فقط أول 3 يحصلون position
    $name = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
    $comment = htmlspecialchars($row['Comment']);
    $stars = renderStars((int)$row['Rating']);
?>
    <div class="carousel-item <?php echo $positionClass; ?>">
        <img class="i" src="مهار.jpg" alt="">
        <h3><?php echo $name; ?></h3>
        <p><?php echo $comment; ?></p>
        <p class="stars"><?php echo $stars; ?></p>
    </div>
<?php
$index++;
endwhile;
?>
</div>

<br><br>
    
          
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

<script>
    let selectedTime = null;

    function selectTime(timeId) {
      
        const buttons = document.querySelectorAll('.time-button');
        buttons.forEach(button => button.classList.remove('selected'));

        
        document.getElementById(timeId).classList.add('selected');
        selectedTime = timeId; 
    }

    function confirmBooking() {
        if (!selectedTime) {
            alert("يرجى اختيار وقت الحجز قبل التأكيد.");
            return;
        }
        alert("تم تأكيد الحجز للوقت: " + document.getElementById(selectedTime).innerText);
    }
</script>

<script>
const carouselItems = document.querySelectorAll('.carousel-item');
let activeIndex = 1; 

function updateCarousel() {
  carouselItems.forEach((item, index) => {
    item.classList.remove('active', 'left', 'right');
    if (index === activeIndex) {
      item.classList.add('active');
    } else if (index === (activeIndex - 1 + carouselItems.length) % carouselItems.length) {
      item.classList.add('left');
    } else if (index === (activeIndex + 1) % carouselItems.length) {
      item.classList.add('right');
    }
  });
}

function handleClick(event) {
  const clickedIndex = [...carouselItems].indexOf(event.currentTarget);
  if (clickedIndex !== activeIndex) {
    activeIndex = clickedIndex;
    updateCarousel();
  }
}

carouselItems.forEach(item => {
  item.addEventListener('click', handleClick);
});

updateCarousel();
</script>

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
  w
});

// Event listener to handle loading images
window.addEventListener('load', () => {
    document.querySelectorAll('.wishlist-img').forEach((img) => {
        img.classList.add('loaded');
    });
});





        </script>
</body>
</html>


