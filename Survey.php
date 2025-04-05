<?php
ob_start(); // <--- ابدأ التخزين المؤقت (احتياطي عشان ما يطبع HTML بالغلط)
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    header('Content-Type: application/json');
    require_once __DIR__ . '/database.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['error' => 'Invalid data format']);
        exit;
    }

    if (empty($data['categories'])) {
        echo json_encode(['error' => 'Please select at least one category']);
        exit;
    }

    if (empty($data['locations'])) {
        echo json_encode(['error' => 'Please select at least one location']);
        exit;
    }

    $categories = array_map(function($cat) use ($connection) {
        return mysqli_real_escape_string($connection, $cat);
    }, $data['categories']);

    $locations = array_map(function($loc) use ($connection) {
        return mysqli_real_escape_string($connection, $loc);
    }, $data['locations']);

    $query = "SELECT * FROM workshop WHERE Category IN ('" . implode("','", $categories) . "')";

    if (!empty($data['workshopType'])) {
        $workshopType = mysqli_real_escape_string($connection, $data['workshopType']);
        if ($workshopType !== 'Both') {
            $query .= " AND Type = '$workshopType'";
        }
    }

    $query .= " AND Location IN ('" . implode("','", $locations) . "')";

    if (!empty($data['priceRange'])) {
        switch ($data['priceRange']) {
            case '0-200':
                $query .= " AND Price <= 200";
                break;
            case '200-300':
                $query .= " AND Price > 200 AND Price <= 300";
                break;
            case '300+':
                $query .= " AND Price > 300";
                break;
        }
    }

    $query .= " ORDER BY Price ASC LIMIT 12";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        echo json_encode(['error' => 'Database error: ' . mysqli_error($connection)]);
        exit;
    }

    $workshops = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $workshops[] = [
            'WorkshopID' => $row['WorkshopID'],
            'Title' => $row['Title'],
            'ShortDes' => $row['ShortDes'],
            'Category' => $row['Category'],
            'Location' => $row['Location'],
            'Type' => $row['Type'],
            'Price' => $row['Price'],
            'ImageURL' => $row['ImageURL']
        ];
    }

    echo json_encode($workshops);
    exit;
}

// باقي الصفحة (HTML) يبدأ هنا فقط إذا ما كان JSON
$loggedIn = isset($_SESSION['userID']);
?>

<!-- هنا تبدأ واجهة HTML بالكامل -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Page</title>
    <!-- بقية العناصر مثل CSS و JavaScript تُدرج هنا -->
</head>
<body>
    <!-- محتوى الصفحة مثل نموذج الاستبيان، الصور، النتيجة، إلخ -->
    <!-- انسخي محتوى Survey.html هنا من داخل <body> -->
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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


        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        header.with-background {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.7);
     
        }
        header.no-background {

            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 4;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-container {
            display: flex;
            align-items: center;
        }
        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }
        .nav-links li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .language-switch {
            cursor: pointer;
            font-size: 18px;
            margin-left: 20px;
            color: white;
        }
        .login-signup {
            font-size: 18px;
            color: white;
            text-decoration: none;
        }
        /* Hamburger Menu */
        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
            color: white;
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
            padding-top: 200px;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .container {
    width: 80%;
    margin: auto;
    padding: 100px;
    background: hsla(31, 69%, 89%, 0.399);
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px; 
    position: relative; 
}
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        p.intro {
            text-align: center;
            color: #666;
            font-size: 18px;
        }
        .survey-form {
            margin-right: 40px;
            margin-top: 20px;
            max-width: 600px;
            width: 60%;
        }
        .question {
            margin-bottom: 20px;
        }
        .question h3 {
            margin-bottom: 10px;
            color: #444;
        }
        .options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .option {
            display: flex;
            align-items: center;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .option:hover {
            background: #e0e0e0;
        }
        .option input {
            margin-left: 10px;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #e39a42;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s ease;
        }
        .submit-btn:hover {
            background: #5c7fa4;
        }
        .results {

    display: none; 
    flex-direction: column;
    align-items: center;
    width: 60%; 
}
        .results h2 {
            text-align: center;
            color: #333;
        }
        .workshops-grid {
    
    display: flex; 
    gap: 20px; 
    margin-top: 20px;
    justify-content: center; 
}
        .workshop-card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .workshop-card:hover {
            transform: translateY(-5px);
        }
        .workshop-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        .workshop-card h4 {
            margin: 10px 0;
            color: #444;
        }
        .workshop-card p {
            color: #666;
        }
        .workshop-card button {
            display: block;
            width: 100%;
            padding: 10px;
            background: #6e9bca;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .workshop-card button:hover {
            background: #5d7c9e;
        }
        .retake-btn {
    display: block;
    width: fit-content;
    padding: 12px 24px;
    background: #a77b28;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
    margin: 20px auto 0;
}
.retake-btn:hover {
    background: #bee9c7;
}

.image-slider {
    width: 35%;
    height: 600px;
    overflow: hidden;
    position: relative;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transition: opacity 0.3s ease; 
}

.image-slider.hidden {
    opacity: 0; 
    pointer-events: none; 
}
        .image-slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .image-slider img.active {
            opacity: 1;
        }

        /* تعديلات للهواتف المحمولة */
@media (max-width: 768px) {
    body {
        padding-top: 120px;
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
    
    .nav-links {
        display: none;
    }
    
    .menu-toggle {
        display: block;
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
}

/* تعديلات للأجهزة اللوحية */
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

/* تعديلات للشاشات الكبيرة */
@media (min-width: 1025px) {
    .container {
        max-width: 1200px;
    }
}
  /* تحسينات عامة للاستجابة */
.container {
    box-sizing: border-box;
}

.survey-form, .results {
    box-sizing: border-box;
    padding: 15px;
}

.options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}

.option {
    margin: 0;
    width: 100%;
}

.workshops-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    width: 100%;
}

.workshop-card {
    width: 100%;
    box-sizing: border-box;
}

/* تحسينات للقوائم */
.nav-links {
    flex-wrap: wrap;
    justify-content: center;
}

/* تحسينات للعناوين */
h1 {
    font-size: calc(1.5rem + 1vw);
    margin: 20px 0;
}

h3 {
    font-size: calc(1rem + 0.5vw);
}      
    </style>
</head>
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



    <div class="container">
        <form id="surveyForm">
            <div class="survey-form">
                <h1 id="main-title">Discover the Best Workshops for You!</h1>
                <p class="intro" id="intro-text">Complete this short survey to get personalized workshop recommendations based on your interests.</p>
                
                <!-- Question 1: Preferred Workshop Categories -->
                <div class="question">
                    <h3>Choose your preferred categories:</h3>
                    <div class="options">
                        <label class="option">
                            <input type="checkbox" name="categories[]" value="Art" checked>
                            🎨 Arts & Crafts
                        </label>
                        <label class="option">
                            <input type="checkbox" name="categories[]" value="Cooking">
                            👩‍🍳 Cooking Workshops
                        </label>
                        <label class="option">
                            <input type="checkbox" name="categories[]" value="Adventure">
                            ⛰️ Adventure Activities
                        </label>
                    </div>
                </div>
                
                <!-- Question 2: Participation Method -->
                <div class="question">
                    <h3>Preferred participation method:</h3>
                    <div class="options">
                        <label class="option">
                            <input type="radio" name="workshopType" value="in-person" checked>
                            🏫 In-person
                        </label>
                        <label class="option">
                            <input type="radio" name="workshopType" value="Online">
                            🏠 Online
                        </label>
                        <label class="option">
                            <input type="radio" name="workshopType" value="Both">
                            🔄 Both
                        </label>
                    </div>
                </div>
                
                <!-- Question 3: Location -->
                <div class="question">
                    <h3>Select your city:</h3>
                    <div class="options">
                        <label class="option">
                            <input type="checkbox" name="locations[]" value="Riyadh" checked>
                            Riyadh
                        </label>
                        <label class="option">
                            <input type="checkbox" name="locations[]" value="Jeddah">
                            Jeddah
                        </label>
                        <label class="option">
                            <input type="checkbox" name="locations[]" value="Dammam">
                            Dammam
                        </label>
                        <label class="option">
                            <input type="checkbox" name="locations[]" value="Taif">
                            Taif
                        </label>
                    </div>
                </div>
                
                <!-- Question 4: Price Range -->
                <div class="question">
                    <h3>Price range:</h3>
                    <div class="options">
                        <label class="option">
                            <input type="radio" name="priceRange" value="0-200" checked>
                            💵 Up to 200 SAR
                        </label>
                        <label class="option">
                            <input type="radio" name="priceRange" value="200-300">
                            💰 200 - 300 SAR
                        </label>
                        <label class="option">
                            <input type="radio" name="priceRange" value="300+">
                            💎 Above 300 SAR
                        </label>
                    </div>
                </div>
            </div>
    
            <div id="errorContainer" class="error-message"></div>
            <button type="submit" class="submit-btn">Find Recommended Workshops</button>
        </form>
    
   
 

        <div class="results" id="resultsSection" style="display:none;">
            <h2>Recommended Workshops</h2>
            <div class="workshops-grid" id="workshopsGrid"></div>
            <button class="retake-btn" onclick="retakeSurvey()">Retake Survey</button>
        </div>


            
        <div class="image-slider">
            <img class="active" src="workshops/workshop3 (4).jpeg" alt="Workshop 1">
            <img src="workshops/workshop3.jpeg" alt="Workshop 2">
            <img src="workshops/workshop3 (6).jpeg" alt="Workshop 3">
            <img src="workshops/workshop3 (5).jpeg" alt="Workshop 4">
            <img src="workshops/workshop3 (4).jpeg" alt="Workshop 5">
            <img src="workshops/workshop3 (3).jpeg" alt="Workshop 6">
            <img src="workshops/workshop3 (2).jpeg" alt="Workshop 7">
       
        </div>
    </div>


    
    <script>
        // Image slider
        const sliderImages = document.querySelectorAll('.image-slider img');
        let currentImageIndex = 0;
    
        function changeSliderImage() {
            sliderImages[currentImageIndex].classList.remove('active');
            currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
            sliderImages[currentImageIndex].classList.add('active');
        }
    
        setInterval(changeSliderImage, 3000);
    
        // دالة عرض الأخطاء
        function showError(message) {
            const errorContainer = document.getElementById("errorContainer");
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            setTimeout(() => {
                errorContainer.style.display = 'none';
            }, 5000);
        }
    


        // دالة إرسال الاستبيان
        async function submitSurvey(event) {
    event.preventDefault();
    
    const formData = {
        categories: Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(el => el.value),
        workshopType: document.querySelector('input[name="workshopType"]:checked').value,
        locations: Array.from(document.querySelectorAll('input[name="locations[]"]:checked')).map(el => el.value),
        priceRange: document.querySelector('input[name="priceRange"]:checked').value
    };

    try {
        const response = await fetch('Survey.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();
        
        if (result.error) {
            showError(result.error);
        } else {
            displayResults(result);
        }
    } catch (error) {
        console.error('Error:', error);
        showError("حدث خطأ أثناء جلب البيانات");
    }
}
    
        // دالة عرض النتائج
        function displayResults(workshops) {
            document.getElementById("surveyForm").style.display = "none";
            document.querySelector(".image-slider").classList.add("hidden");
            document.getElementById("resultsSection").style.display = "flex";
    
            const workshopsGrid = document.getElementById("workshopsGrid");
            workshopsGrid.innerHTML = "";
    
            if (!workshops || workshops.length === 0) {
                workshopsGrid.innerHTML = "<p>No matching workshops found. Please try different selections.</p>";
                return;
            }
    
            workshops.forEach(workshop => {
                const card = document.createElement("div");
                card.className = "workshop-card";
                card.innerHTML = `
                    <img src="${workshop.ImageURL}" alt="${workshop.Title}" style="width:100%; height:200px; object-fit:cover; border-radius:8px;">
                    <h3>${workshop.Title}</h3>
                    <p><strong>Category:</strong> ${workshop.Category}</p>
                    <p><strong>Location:</strong> ${workshop.Location}</p>
                    <p><strong>Price:</strong> SAR ${workshop.Price}</p>
                    <p>${workshop.ShortDes || workshop.description || ''}</p>
                    <button class="book-btn" onclick="bookWorkshop(${workshop.WorkshopID})">Book Now</button>
                `;
                workshopsGrid.appendChild(card);
            });
        }
    
        // دالة إعادة الاستبيان
        function retakeSurvey() {
            document.getElementById("surveyForm").style.display = "block";
            document.getElementById("resultsSection").style.display = "none";
            document.querySelector(".image-slider").classList.remove("hidden");
        }
    
        // دالة الحجز
        function bookWorkshop(workshopId) {
            alert(`Booking workshop with ID: ${workshopId}`);
            // يمكن إضافة منطق الحجز هنا
        }
    
        // ربط دالة الإرسال بالنموذج عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById("surveyForm");
    if (form) {
        form.addEventListener("submit", submitSurvey);
    } else {
        console.error("Form not found!");
    }
});

function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
}
    </script>

    
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

</body>
</html>
