<?php
session_start();
require 'database.php';

if (!isset($_GET['workshopID'])) {
    header("Location: findcategory.php");
    exit;
}
$loggedIn = isset($_SESSION['user_id']);

$workshopID = $_GET['workshopID'];

$stmt = $connection->prepare("SELECT Title, LongDes, Location, Duration, Age, Price, ImageURL, Category FROM workshop WHERE WorkshopID = ?");
$stmt->bind_param("i", $workshopID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Workshop not found.";
    exit;
}

$workshop = $result->fetch_assoc();

$schedule_stmt = $connection->prepare("SELECT ScheduleID, Day, Date, StartTime, EndTime FROM workshop_schedule WHERE WorkshopID = ?");
$schedule_stmt->bind_param("i", $workshopID);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();

$works_stmt = $connection->prepare("SELECT ImageURL, ClientName, CreatedAt FROM previous_works WHERE WorkshopID = ?");
$works_stmt->bind_param("i", $workshopID);
$works_stmt->execute();
$works_result = $works_stmt->get_result();

$review_stmt = $connection->prepare("
    SELECT r.Rating, r.Comment, u.FirstName, u.LastName, u.ProfilePhoto
    FROM review r
    JOIN users u ON r.UserID = u.UserID
    WHERE r.WorkshopID = ?
");

$review_stmt->bind_param("i", $workshopID);
$review_stmt->execute();
$review_result = $review_stmt->get_result();

$isFavorited = false;
if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $fav_check = $connection->prepare("SELECT id FROM favorites WHERE UserID = ? AND WorkshopID = ?");
    $fav_check->bind_param("ii", $userID, $workshopID);
    $fav_check->execute();
    $fav_check_result = $fav_check->get_result();
    $isFavorited = $fav_check_result->num_rows > 0;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_favorite'])) {
        // إضافة
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            exit("Login required");
        }

        $userID = $_SESSION['user_id'];
        $check_stmt = $connection->prepare("SELECT * FROM favorites WHERE UserID = ? AND WorkshopID = ?");
        $check_stmt->bind_param("ii", $userID, $workshopID);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows === 0) {
            $insert_stmt = $connection->prepare("INSERT INTO favorites (UserID, WorkshopID) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $userID, $workshopID);
            $insert_stmt->execute();
        }

        exit;
    }

    if (isset($_POST['remove_favorite'])) {
        // إزالة
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            exit("Login required");
        }

        $userID = $_SESSION['user_id'];
        $delete_stmt = $connection->prepare("DELETE FROM favorites WHERE UserID = ? AND WorkshopID = ?");
        $delete_stmt->bind_param("ii", $userID, $workshopID);
        $delete_stmt->execute();

        exit;
    }
}

    
    
    
    

function timeAgo($datetime) {
    $now = new DateTime();
    $created = new DateTime($datetime);
    $interval = $now->diff($created);
    if ($interval->y > 0) return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    if ($interval->m > 0) return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    if ($interval->d >= 7) return floor($interval->d / 7) . ' week' . ($interval->d > 7 ? 's' : '') . ' ago';
    if ($interval->d > 0) return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    if ($interval->h > 0) return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    if ($interval->i > 0) return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}

function renderStars($rating) {
    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>booking</title>
    <link rel="stylesheet" href="header.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
body {
            margin: 0;
            background: #FFFDF0;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #444;
            

        }
    

html, body {
    overflow-x: hidden;
}



        .header-content {
    z-index: 1;
    margin: 0 auto;
    text-align: center;
    width: 100%;
    padding: 10%;
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


button {
    font-family: 'swap', serif;
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
            margin-left: 65.3%;
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
    border-radius: 8px;
    
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
    height:33px;
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
.wishlist-star {
    position: absolute;
    top: 25px;
    right: 35px;
    cursor: pointer;
    z-index: 1000;
    width: 48px;
    height: 48px;
}

.wishlist-star svg {
    width: 100%;
    height: 100%;
}

.wishlist-star svg:hover {
    transform: scale(1.13);
    filter: drop-shadow(0 0 6px gold);
}


#sub:hover {
    transform: scale(1.1);
}

.time-button:hover{
    transform: scale(1.1);
}

.card:hover{
    transform: scale(1.1);
}



     /* الهيدر */
header {
    position: absolute; /* بدلاً من relative */
    top: 0;
    left: 0;
    width: 100%;
    z-index: 10;
    background: transparent; /* شفّاف */
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}



.header {
            position: relative;
            width: 100%;
            height: 655px;
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


        @media (max-width: 768px) {


    .outline-text {
        font-size: 90px; 
    }

    .header-content .quote {
        font-size: 16px; 
    }
    

    .wishlist-star {
        width: 30px !important;
        height: 30px !important;
        top: 20px !important;
        right: 25px !important;
    }

    .wishlist-star svg {
        width: 100% !important;
        height: 100% !important;
    }
    
        .timeline-text {
        font-size: 12px;
        margin:  0px -68px ;
         justify-content: space-between;
    }

    .timeline-text div {
        font-size: 12px;
        
    }
    
        #workshop-times {
        flex-direction: column !important;
        align-items: center !important;  
        padding: 0 0 !important;      
    }

    .time-button {
        width: 55% !important;      
        height: 2% !important;
        margin-bottom: 0px !important;
        
    }

    .time-button span {
        font-size: 11px !important;
        line-height: 1.2 !important;
    }
    
      .price-section {
        flex-direction: row !important;
        align-items: center !important;
        margin: 0 50px !important;
    }

    .price {
        font-size: 16px !important;
        margin: 0 !important;
    }

    .currency-icon {
        width: 18px !important;
        height: 18px !important;
    }

    #sub {
        width: 80px !important;
        padding: 8px !important;
        margin-left: 140px !important;
        font-size: 13px !important;
    }
    
    .experience-cards {
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding: 0 15px;
    }

    .card {
        flex: 0 0 75%;
        margin-right: 10px; 
        scroll-snap-align: start;
    }
    
        .reviews {
        padding: 0 10px;
    }

    .carousel-item {
        width: 300px !important;
        height: 240px;
        transform: scale(0.9);
        margin-right: 10px;
    }

    .carousel-item.active {
        transform: translateX(-40%) scale(1);
    }

    .carousel-item.left,
    .carousel-item.right {
        transform: scale(1);
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
}



</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<!-- navbar.html -->

<?php
$imagePath = str_replace('\\', '/', $workshop['ImageURL']);
?>


   

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
            <div class="mobile-language-switch" onclick="toggleLanguage()">
                🌐 Language
            </div>
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
        <a href="#" class="language-switch" onclick="toggleLanguage()">🌐 Language</a>
    </nav>
</header>
     <div class="header" style="background: url('<?php echo $imagePath; ?>') no-repeat center center/cover;">
    <div class="header-content">
        <h1>
            <br><span class="outline-text"><?php echo htmlspecialchars($workshop['Title']); ?></span><br>
        </h1>
        <p class="quote"><?php echo nl2br(htmlspecialchars($workshop['LongDes'])); ?></p>
    </div>
</div>



<div class="box-container">
    <div class="wishlist-star" id="wishlistStar" onclick="handleFavorite()">
    <svg id="starIcon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="<?php echo $isFavorited ? '#facc15' : '#ccc'; ?>" viewBox="0 0 24 24">
        <path d="M12 2.25l2.96 6.1 6.72.98-4.84 4.72 1.14 6.65L12 17.77l-5.98 3.15 1.14-6.65-4.84-4.72 6.72-.98L12 2.25z" stroke="<?php echo $isFavorited ? '#facc15' : '#ccc'; ?>" stroke-linejoin="round" stroke-linecap="round"/>
    </svg>
</div>

    <br><br><img src="workshops/line2.png" alt="Timeline" class="timeline-img">
    <div class="timeline-text">
    <div><?php echo htmlspecialchars($workshop['Location']); ?></div>
    <div><?php echo htmlspecialchars($workshop['Duration']); ?></div>
    <div><?php echo '+' . htmlspecialchars($workshop['Age']) . ' years'; ?></div>
</div>

    
    <br><br><br>
    
    <div>Available Times</div><br><br>
<div id="workshop-times">
    <?php
    $timeIndex = 1;
    while ($row = $schedule_result->fetch_assoc()):
        $day = $row['Day'];
        $date = date("F j, Y", strtotime($row['Date']));
        $startTime = date("g:i A", strtotime($row['StartTime']));
        $endTime = date("g:i A", strtotime($row['EndTime']));
        $buttonId = "time" . $timeIndex;
        $scheduleID = $row['ScheduleID'];
    ?>
<button class="time-button" 
            id="<?php echo $buttonId; ?>" 
            data-scheduleid="<?php echo $scheduleID; ?>"
            onclick="selectTime('<?php echo $buttonId; ?>')">
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
        <?php
$profileImage = !empty($row['ProfilePhoto']) ? 'uploads/' . $row['ProfilePhoto'] : 'uploads/default.jpg';
?>
<img class="i" src="<?php echo $profileImage; ?>" alt="">

<h3><?php echo $name; ?></h3><br>
<p><?php echo $comment; ?></p><br>
        <p class="stars"><?php echo $stars; ?></p>
    </div>
<?php
$index++;
endwhile;
?>
</div>

<br><br>
    
          
<footer>
    <div class="footer-left-1">
        <h4>Get In Touch</h4>
        <div class="contact-info-1" id="contact-us">
            <div class="contact-item-1">
                <img src="workshops/phone.png" alt="Phone Icon" class="icon-phone">
                <span class="single-line-1">+996 58765 43210</span>
            </div>
            <div class="contact-item-1">
                <img src="workshops/mail.png" alt="Email Icon" class="icon-email">
                <span class="single-line-1">mehar@gmail.com</span>
            </div>
            <div class="contact-item-1">
                <img src="workshops/location.png" alt="Location Icon" class="icon-location">
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
        <h4>Social media</h4>
        <div class="social-icons-1">
            <img src="workshops/Facebook_icon_(black).svg" alt="Facebook" class="icon-facebook">
            <img src="workshops/X1.png" alt="Twitter" class="icon-twitter">
            <img src="workshops/CIS-A2K_Instagram_Icon_(Black).svg" alt="Instagram" class="icon-instagram">
        </div>
    </div>
    
    <div class="footer-bottom-1">
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



</script>

<script>
function confirmBooking() {
    if (!selectedTime) {
        Swal.fire({
            icon: 'warning',
            title: 'Select Time',
            text: 'Please choose a time before confirming booking!',
            confirmButtonColor: '#E6B740'
        });
        return;
    }

    // استخراج البيانات من الزر المحدد
    const selectedButton = document.getElementById(selectedTime);
    const scheduleID = selectedButton.dataset.scheduleid; 
    const timeText = selectedButton.innerText.trim().split('\n');
    
    const date = timeText[1].trim(); // مثال: March 27, 2025
    const startEnd = timeText[2].split(' - ');
    const startTime = convertTo24Hour(startEnd[0].trim()); // مثال: 15:00:00
    const endTime = convertTo24Hour(startEnd[1].trim());   // مثال: 17:00:00

    Swal.fire({
        title: 'Confirm Booking?',
        text: 'Are you sure you want to book this workshop?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#E6B740',
        cancelButtonColor: '#aaa'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('confirm_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    workshopID: <?php echo $workshopID; ?>,
                    date: formatDate(date), 
                    startTime: startTime,
                    endTime: endTime,
                    scheduleID: scheduleID
                })
            })
            .then(response => {
                if (response.status === 403) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Login Required',
                        text: 'You need to login to book this workshop.',
                        showCancelButton: true,
                        confirmButtonText: 'Login',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#E6B740',
                        cancelButtonColor: '#aaa'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                    return null; // أوقف الاستمرار
                }
                return response.json();
            })

            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Confirmed!',
                        text: 'Your booking number is: ' + data.BID,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#E6B740'
                    }).then(() => {
                        window.location.href = 'findcategory.php';
                    });
                } else if (data.status === 'already_booked') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Already Booked!',
                        text: data.message,
                        confirmButtonColor: '#E6B740'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: data.message || 'Something went wrong.',
                        confirmButtonColor: '#E6B740'
                    });
                }
            });
        }
    });
}


function convertTo24Hour(timeStr) {
    const [time, modifier] = timeStr.split(" ");
    let [hours, minutes] = time.split(":");

    if (modifier === "PM" && hours !== "12") {
        hours = parseInt(hours, 10) + 12;
    }
    if (modifier === "AM" && hours === "12") {
        hours = "00";
    }

    return `${hours}:${minutes}:00`;
}

// تحويل التاريخ من "March 27, 2025" إلى "2025-03-27"
function formatDate(dateStr) {
    const dateObj = new Date(dateStr);
    return dateObj.toISOString().split('T')[0];
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
        
        
        <script>
let isFavorited = <?php echo $isFavorited ? 'true' : 'false'; ?>;

    function handleFavorite() {
    const starIcon = document.getElementById('starIcon');

    if (!isFavorited) {
        // إضافة إلى المفضلة
        Swal.fire({
            title: 'Add to Wishlist?',
            text: 'Do you want to add this workshop to your wishlist?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#E6B740',
            cancelButtonColor: '#aaa'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(window.location.href, {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: "add_favorite=1"
                })
                .then(response => {
                    if (response.status === 403) {
                        Swal.fire({
                        title: 'Login Required',
                        text: 'You need to login to add this to your wishlist.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Login',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#E6B740',
                        cancelButtonColor: '#aaa'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                    return;
                }


                    starIcon.setAttribute("fill", "#facc15");
                    starIcon.setAttribute("stroke", "#facc15");
                    isFavorited = true;

                    Swal.fire({
                        title: 'Added!',
                        text: 'This workshop has been added to your wishlist.',
                        icon: 'success',
                        confirmButtonColor: '#E6B740'
                    });
                });
            }
        });

    } else {
        
        Swal.fire({
            title: 'Remove from Wishlist?',
            text: 'Do you want to remove this workshop from your wishlist?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#E6B740',
            cancelButtonColor: '#aaa'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(window.location.href, {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: "remove_favorite=1"
                })
                .then(() => {
                    starIcon.setAttribute("fill", "#ccc");
                    starIcon.setAttribute("stroke", "#ccc");
                    isFavorited = false;

                    Swal.fire({
                        title: 'Removed!',
                        text: 'This workshop has been removed from your wishlist.',
                        icon: 'info',
                        confirmButtonColor: '#E6B740'
                    });
                });
            }
        });
    }
}



</script>

<script>
function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
    }
    </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>