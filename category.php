<?php
session_start();
require 'database.php';

$category = $_GET['category'] ?? 'Art'; // احتياطي لو مافي قيمة

// نستخدمها للفلتر
$filterCategory = $_GET['cat'] ?? $category;
$location = $_GET['city'] ?? '';
$price = $_GET['price'] ?? '';
$type = $_GET['type'] ?? '';
$date = $_GET['date'] ?? '';

$conditions = [];
$params = [];
$types = "";

$query = "SELECT Title, ShortDes, Location, Price, ImageURL FROM workshop WHERE 1=1";

// Category (من صفحة التوجيه أو الفلتر)
if (!empty($filterCategory) && $filterCategory !== "All") {
    $query .= " AND Category = ?";
    $params[] = $filterCategory;
    $types .= "s";
}

// Location
if (!empty($location) && $location !== "All") {
    $query .= " AND Location = ?";
    $params[] = $location;
    $types .= "s";
}

// Price
if (!empty($price)) {
    if ($price == 'Less than 150 SAR') {
        $query .= " AND Price < 150";
    } elseif ($price == '150 - 200 SAR') {
        $query .= " AND Price BETWEEN 150 AND 200";
    } elseif ($price == 'More than 200 SAR') {
        $query .= " AND Price > 200";
    }
}

// Type
if (!empty($type) && $type !== "All") {
    $query .= " AND Type = ?";
    $params[] = $type;
    $types .= "s";
}

// Date
if (!empty($date)) {
    $query .= " AND Date = ?";
    $params[] = $date;
    $types .= "s";
}

// Exclude Dammam
$query .= " AND Location IS NOT NULL AND Location != 'Dammam'";

// Prepare and execute
$stmt = $connection->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art</title>
    <link rel="stylesheet" href="header.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <style>
        
               
   /* ====== الفوتر ====== */
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

/* ✅ كل أيقونة + النص جنب بعض، والكل في نفس السطر */
.contact-info-1 {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    align-items: center;
    gap: 20px;
    flex-wrap: nowrap;
    margin-top: 10px;
    width: 100%; /* ✅ ياخذ كامل مساحة الفوتر */
}



.contact-item-1 {
    display: flex;
    align-items: center; /* محاذاة الأيقونة مع النص */
    gap: 8px; /* مسافة بين الأيقونة والنص */
    white-space: nowrap; /* منع انقسام النص */
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

/* النص اللي تحت */
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

/* ====== أحجام الأيقونات (حسب طلبك) ====== */
.icon-phone {
    display: inline-block !important;  /* تأكيد ظهور العنصر */
    width: 30px !important;
    height: 30px !important;
   /* إلغاء الطفو */
}

.icon-phone {
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

/* ✅ Responsive للجوال */
@media (max-width: 768px) {
    .footer-left-1,
    .footer-center-1,
    .footer-right-1 {
        flex: 100%;
        margin-bottom: 1em;
    }

    .contact-info-1 {
        flex-direction: column;
        gap: 15px;
    }

    .icon-phone,
    .icon-email,
    .icon-location,
    .icon-facebook,
    .icon-twitter,
    .icon-instagram {
        transform: scale(0.9);
    }
}
        body {
            margin: 0;
            background: #FFFDF0;
        }

        .header {
            position: relative;
            width: 100%;
            height: 550px;
            background: url('workshops/stained-brushes-inside-pocket.jpg') no-repeat center center/cover;
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
            grid-template-columns: repeat(4, 1fr);
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
    display: inline-block; /* لجعل الرابط يبدو كزر */
    background-color: #FDE5B7;
    color: #333;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 12px;
    text-decoration: none; /* إزالة الخط السفلي من الرابط */
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease; /* تأثير سلس */
}

/* تأثير التحويم (Hover) */
.more-btn:hover {
    background-color: #FF9D23;
    transform: scale(1.1); /* تكبير الزر */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* إضافة ظل */
    color: white;
}

        .grid-item .more-btn:hover {
    background-color: #FF9D23;
    transform: scale(1.1); /* تكبير الزر */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* إضافة ظل */
}

        .grid-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

      
        .search-container {
    display: flex;
    align-items: center;
    justify-content: flex-start; /* Align to the left */
    gap: 40px;
    padding-left: 13px; /* Add padding to match the header content */
}

.search-input-container {
    position: relative;
    width: 550px;
}

/* تحسين مربع الإدخال */

.search-input {
    padding: 12px 50px 12px 15px; /* Add padding to the right to make space for the icon */
    width: 100%;
    height: 55px;
    border-radius: 25px;
    border: solid 2px #FF9D23;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    color: #333;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* تأثير عند النقر أو التركيز على مربع البحث */
.search-input:focus {
    outline: none;
    border-color: #FF9D23; /* تغيير لون الحدود */
    background-color: #fff; /* جعل الخلفية بيضاء عند التركيز */
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

        .filter-icon {
            width: 40px !important;
            height: 50px !important;
            object-fit: contain;
        }

        .search-icon, .filter-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

.search-icon {
    position: absolute;
    right: 0.1px; /* Adjust this value to position the icon inside the box */
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    cursor: pointer;
}

        .filter-icon {
            position: static;
        }

        .filter-btn {
            background-color: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
            margin-left: 20px;
        }
        .filter-box {
    background-color: #FFFDF0; /* شفافية مع لون أبيض */
    padding: 15px; /* تقليل الحشو */
    border-radius: 15px;
    width: 250px; /* تصغير العرض */
    position: absolute; /* تغيير من fixed إلى absolute */
    top: 200px; /* التعديل حسب الموقع المطلوب */
    right:300px; /* التعديل حسب الموقع المطلوب */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* إضافة ظل */
    z-index: 1000; /* للتأكد من ظهوره فوق العناصر الأخرى */
    display: none; /* يظل مخفيًا حتى يتم الضغط على الزر */
    backdrop-filter: blur(10px); /* تأثير ضبابي خلف الصندوق */
    border: 1px solid rgba(255, 157, 35, 0.3); /* حدود متناسقة مع لون الصفحة */
}


.filter-box select,
.filter-box input {
    width: 100%;
    padding: 8px; /* تقليل الحشو */
    margin-bottom: 10px; /* تقليل المسافة */
    border-radius: 8px;
    border: 1px solid #FF9D23; /* لون الحدود متناسق مع التصميم */
    background-color: #FFFDF0; /* خلفية شفافة */
    font-family: 'Montserrat', sans-serif;
    font-size: 12px; /* تصغير حجم الخط */
    color: #333;
}

input:focus, select:focus, button:focus {
    outline: none; /* إزالة الحدود الرمادية */
}
input[type="date"] {
    width: 230px; /* ضبط العرض ليكون متناسقًا */
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #FF9D23;
    background-color: #FFFDF0;
    font-size: 14px; /* تحسين وضوح الخط */
    color: #333;
}


.filter-box label {
    margin-bottom: 6px; /* تقليل المسافة */
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px; /* تصغير حجم الخط */
    color: #FF9D23; /* لون النص متناسق مع التصميم */
}

.filter-action {
    text-align: center;
}

.filter-action button {
    background-color: #FF9D23; /* لون الزر متناسق مع التصميم */
    color: white;
    padding: 8px 16px; /* تصغير حجم الزر */
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px; /* تصغير حجم الخط */
    transition: background-color 0.3s ease;
}

.filter-action button:hover {
    background-color: #e68a1f; /* لون الزر عند التحويم */
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
    </style>
</head>
<body>
    <div class="header">
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
            <a href="Explore.php">Explore</a>
            <a href="login.php">Login/Signup</a>
            <a href="findcategory.php">Category</a>
            <div class="mobile-language-switch" onclick="toggleLanguage()">
                🌐 Language
            </div>
        </nav>
    </div>

    <!-- قائمة سطح المكتب -->
    <nav class="desktop-nav">
        <a href="homepage.php">Home</a>
        <a href="">Explore</a>
        <a href="findcategory.php">Category</a>
        <div class="language-switch" onclick="toggleLanguage()">
            🌐 Language
        </div>
    </nav>
</header>
 
        <div class="header-content">
            <h1>
                <span>DISCOVER THE</span>
                <span class="outline-text">Art</span>
            </h1>
            <p class="quote">“Express your personality through artful experiences.”</p>
        </div>
    </div>
    <div class="container">
        <div class="search-container">
            <div class="search-input-container">
                <input type="text" class="search-input" placeholder="Search for your interest!">
                <img src="/IT320Project/workshops/360_F_558272798_DNqj4q2TXE7EsDM9Zp2wdyap8gzatwlF.webp" alt="Search Icon" class="search-icon">
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
                <a href="booking.php" class="more-btn">More details</a>
                <span class="price">
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
    </script>
</body>
</html>