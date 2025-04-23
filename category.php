<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);

require 'database.php';

$category = $_GET['category'] ?? 'Art';

$isCleared = isset($_GET['category']) && !isset($_GET['price']) && !isset($_GET['type']) && !isset($_GET['date']) && !isset($_GET['city']);

$filterCategory = $_GET['cat'] ?? $category;
$location = $isCleared ? '' : ($_GET['city'] ?? '');
$price    = $isCleared ? '' : ($_GET['price'] ?? '');
$type     = $isCleared ? '' : ($_GET['type'] ?? '');
$date     = $isCleared ? '' : ($_GET['date'] ?? '');

$conditions = [];
$params = [];
$types = "";

$query = "SELECT DISTINCT w.WorkshopID, w.Title, w.ShortDes, w.Location, w.Price, w.ImageURL 
          FROM workshop w 
          LEFT JOIN workshop_schedule s ON w.WorkshopID = s.WorkshopID 
          WHERE w.Location != 'Dammam'";

// Category
if (!empty($filterCategory) && $filterCategory !== "All") {
    $query .= " AND w.Category = ?";
    $params[] = $filterCategory;
    $types .= "s";
}

// Location
if (!empty($location) && $location !== "All") {
    $query .= " AND w.Location = ?";
    $params[] = $location;
    $types .= "s";
}

// Price
if (!empty($price)) {
    if ($price == 'Less than 150 SAR') {
        $query .= " AND w.Price < 150";
    } elseif ($price == '150 - 200 SAR') {
        $query .= " AND w.Price BETWEEN 150 AND 200";
    } elseif ($price == 'More than 200 SAR') {
        $query .= " AND w.Price > 200";
    }
}

// Type
if (!empty($type) && $type !== "All") {
    $query .= " AND w.Type = ?";
    $params[] = $type;
    $types .= "s";
}

// Date
if (!empty($date) && $date !== "All") {
    $query .= " AND s.Date = ?";
    $params[] = $date;
    $types .= "s";
}

// تنفيذ الاستعلام
$stmt = $connection->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();


$stmt = $connection->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$headerImage = 'workshops/stained-brushes-inside-pocket.jpg';
$headerTitle = 'DISCOVER THE';
$headerCategory = 'Art';
$headerQuote = '“Express your personality through artful experiences.”';

if ($filterCategory === 'Adventure') {
    $headerImage = 'workshops/adventurepage.jpg';
    $headerTitle = 'LIVE THE';
    $headerCategory = 'Adventure';
    $headerQuote = '“Step beyond the ordinary and discover who you are through bold, adventurous moments.”';
} elseif ($filterCategory === 'Cooking') {
    $headerImage = 'workshops/cookingpage.jpg';
   $headerTitle = 'EXPLORE THE';
$headerCategory = 'Flavor';


    $headerQuote = '“Enjoy cooking experiences and improve your skills with every new recipe.”';
}
?>

<script>
function toggleFilterBox() {
    const filterBox = document.getElementById('filterBox');
    filterBox.style.display = (filterBox.style.display === 'none' || filterBox.style.display === '') ? 'block' : 'none';
}

document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector(".search-input");
    const items = document.querySelectorAll(".grid-item");

    input.addEventListener("input", function () {
        const keyword = this.value.toLowerCase();

        items.forEach(item => {
            const title = item.querySelector("h3").textContent.toLowerCase();
            if (title.includes(keyword)) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });
    });
});
</script>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($filterCategory); ?></title>
    <link rel="stylesheet" href="header.css">
<link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <style>
        
    .desktop-nav a,
.language-switch,
.mobile-nav a,
.mobile-language-switch {
font-family: 'Poppins', sans-serif !important;
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

        body {
            margin: 0;
            background: #FFFDF0;
        }

        .header {
            position: relative;
            width: 100%;
            height: 550px;
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
            margin-left: 150px;
            margin-top: 50px;
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
            font-size: 200px;
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

        .container {
            padding: 30px;
            text-align: center;
        }

        .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Auto-adjust */
    gap: 20px;
    padding: 20px;
}


        .grid-item {
            position: relative;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            overflow: hidden;
            padding-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .grid-item img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .grid-item .tag {
            position: absolute;
            top: 15px;
            left: -10px;
            background-color: #FF9D23;
            color: #fff;
            padding: 5px 15px;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .grid-item h3 {
            font-family: 'Montserrat', sans-serif;
            margin: 10px 5px 5px 5px;
            font-size: 16px;
            color: #FF9D23;
            text-align: center;
        }

        .grid-item p {
            font-size: 14px;
            color: #555;
            margin: 5px 15px;
            text-align: center;
        }

        .grid-item .details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
            margin-top: 10px;
        }

        .grid-item .price {
            color: #333;
            background: none;
            padding: 0;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-direction: row-reverse;
        }

        .riyal-icon {
            width: 12px !important;
            height: 12px !important;
            vertical-align: middle;
        }

        .more-btn {
    display: inline-block; 
    background-color: #FDE5B7;
    color: #333;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 12px;
    text-decoration: none; 
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease; 
}

.more-btn:hover {
    background-color: #FF9D23;
    transform: scale(1.1); 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); 
    color: white;
}

        .grid-item .more-btn:hover {
    background-color: #FF9D23;
    transform: scale(1.1); 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); 
}

        .grid-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

      
      .search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    gap: 12px;
}

.search-input-container {
    position: relative;
    width: 100%;
    max-width: 500px;
}

.search-input {
    width: 100%;
    padding: 12px 40px 12px 20px;
    border: 2px solid #FF9D23;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    font-family: 'Montserrat', sans-serif;
    transition: border 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #FFA833;
}

.search-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    cursor: pointer;
}

.search-container > .filter-btn {
    border: none;
    border-radius: 20%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease;
    padding: 0;
    margin-left: 0;
}

.search-container > .filter-btn:hover {
    background-color:#FF9D23;
}

.filter-icon {
    width: 30px;
    height: 30px;
}

        .filter-btn {
            background-color: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
            margin-left: 20px;
        }
        .filter-box {
    background-color: #FFFDF0; 
    padding: 15px;
    border-radius: 15px;
    width: 250px; 
    position: absolute;
    top: 200px; 
    right:300px; 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
    z-index: 1000;
    display: none; 
    backdrop-filter: blur(10px); 
    border: 1px solid rgba(255, 157, 35, 0.3); 
}


.filter-box select,
.filter-box input {
    width: 100%;
    padding: 8px; 
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #FF9D23; 
    background-color: #FFFDF0; 
    font-family: 'Montserrat', sans-serif;
    font-size: 12px; 
    color: #333;
}

input:focus, select:focus, button:focus {
    outline: none; 
}
input[type="date"] {
    width: 230px; 
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #FF9D23;
    background-color: #FFFDF0;
    font-size: 14px; 
    color: #333;
}


.filter-box label {
    margin-bottom: 6px; 
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px; 
    color: #FF9D23;
}

.filter-action {
    text-align: center;
}

.filter-action button {
    background-color: #FF9D23; 
    color: white;
    padding: 8px 16px; 
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    transition: background-color 0.3s ease;
}

.filter-action button:hover {
    background-color: #FF9D23; 
}

        .filter-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        @media (max-width: 768px) {
    .header {
        height: 400px; 
    }

    .header-content {
        margin-left: 20px;
    }

    .header-content h1 {
        font-size: 30px; 
    }

    .outline-text {
        font-size: 80px;
    }
    .quote{
    font-size: 40px;
    }
}
@media (max-width: 600px) {
   .search-container {
        flex-direction: row; 
        align-items: center; 
        justify-content: center;
    }

    .search-input-container {
        flex: 1;
    }

    .filter-btn {
        margin-left: 10px;
    }
    .search-input {
        width: 100%;
    }

    .filter-box {
        width: 90%;
        right: 5%;
    }
      .more-btn {
    
    padding: 8px 8px;
   }
}

@media (max-width: 768px) {
    .desktop-nav {
        display: none; /* Hide desktop menu */
    }

    .mobile-nav-container {
        display: block; 
    }
}
   @media (max-width: 600px) {
    .grid {
        grid-template-columns: repeat(2, 1fr); 
    }
}
    
/* ✅ Responsive للجوال */
@media (max-width: 768px) {
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
} 
    </style>
</head>
<body>
<div class="header" style="background: url('<?php echo $headerImage; ?>') no-repeat center center/cover;">
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
    <div class="header-content">
            <h1>
                <span><?php echo $headerTitle; ?></span>
                <span class="outline-text"><?php echo $headerCategory; ?></span>
            </h1>
            <p class="quote"><?php echo $headerQuote; ?></p>
        </div>
    </div>
    <div class="container">
        <div class="search-container">
            <div class="search-input-container">
                <input type="text" class="search-input" placeholder="Search for your interest!">
            </div>
            <button class="filter-btn" onclick="toggleFilterBox()">
                <img src="/IT320Project/workshops/Adobe Express - file.png" alt="Filter Icon" class="filter-icon">
            </button>
        </div>
    <form method="GET" class="filter-box" id="filterBox">
    <label for="price">Price:</label>
    <select name="price" id="price">
        <option>All</option>
        <option>Less than 150 SAR</option>
        <option>150 - 200 SAR</option>
        <option>More than 200 SAR</option>
    </select>

    <label for="city">City:</label>
    <select name="city" id="city">
        <option>All</option>
        <option>Riyadh</option>
        <option>Jeddah</option>
    </select>

    <label for="date">Date:</label>
    <input type="date" name="date" id="date">

    <label for="type">Workshop Type:</label>
    <select name="type" id="type">
        <option>All</option>
        <option>Online</option>
        <option>In-person</option>
        <option>Both</option>
    </select>

  <label for="cat">Category:</label>
<select name="cat" id="cat" disabled>
    <option selected><?php echo htmlspecialchars($filterCategory); ?></option>
</select>
<input type="hidden" name="cat" value="<?php echo htmlspecialchars($filterCategory); ?>">


    <div class="filter-action">
        <button type="submit" class="filter-btn">Apply</button>
            <a href="category.php?category=<?php echo urlencode($filterCategory); ?>" class="filter-btn" style="margin-top:10px; background-color:#ccc; color:#333; padding:8px 16px; border-radius:20px; text-decoration:none; display:inline-block;">Clear Filters</a>

    </div>

</form>

        </div>
     <div class="grid">
<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="grid-item">
            <div class="tag"><?php echo $row['Location']; ?></div>
            <img src="<?php echo $row['ImageURL']; ?>" alt="<?php echo $row['Title']; ?>">
            <h3><?php echo $row['Title']; ?></h3>
            <p><?php echo $row['ShortDes']; ?></p>
            <div class="details">
<a href="booking.php?workshopID=<?php echo $row['WorkshopID']; ?>" class="more-btn">More details</a>                <span class="price">
                    <?php echo $row['Price']; ?>
                    <img src="workshops/riyal.png" alt="SAR" class="riyal-icon">
                </span>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="no-results">No workshops found for the selected criteria.</div>
<?php endif; ?>
</div>

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
function toggleFilterBox() {
    const filterBox = document.getElementById('filterBox');
    if (filterBox.style.display === 'none' || filterBox.style.display === '') {
        filterBox.style.display = 'block';
    } else {
        filterBox.style.display = 'none';
    }
}

        function toggleMenu(button) {
            button.classList.toggle('active');
            document.querySelector('.mobile-nav-container').classList.toggle('show');
            document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
        }
    </script>
</body>
</html>