<?php
ob_start();
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    header('Content-Type: application/json');
    require_once __DIR__ . '/database.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['error' => 'Invalid data format']);
        exit;
    }

    // Analyze personality traits from the survey
    $personality = isset($data['personality']) ? mysqli_real_escape_string($connection, $data['personality']) : 'expressive';
    $learning = isset($data['learning']) ? mysqli_real_escape_string($connection, $data['learning']) : 'structured';
    $goal = isset($data['goal']) ? mysqli_real_escape_string($connection, $data['goal']) : 'relaxation';
    $energy = isset($data['energy']) ? mysqli_real_escape_string($connection, $data['energy']) : 'calm';
    $atmosphere = isset($data['atmosphere']) ? mysqli_real_escape_string($connection, $data['atmosphere']) : 'cozy';

    // Determine category based on personality and atmosphere
    $category = 'Art'; // Default category
    if ($personality == 'crafty' || $atmosphere == 'studio') {
        $category = 'Cooking';
    } elseif ($personality == 'adventurous' || $atmosphere == 'adventure') {
        $category = 'Adventure';
    }

    // Build base query with category filter
    $query = "SELECT * FROM workshop WHERE Category = '" . mysqli_real_escape_string($connection, $category) . "'";

    // Add characteristics filters based on user preferences
    $characteristicsFilters = [];
    
    if ($goal == 'relaxation') {
        $characteristicsFilters[] = "(ShortDes LIKE '%relax%' OR ShortDes LIKE '%calm%')";
    } elseif ($goal == 'skill') {
        $characteristicsFilters[] = "(ShortDes LIKE '%learn%' OR ShortDes LIKE '%skill%')";
    } elseif ($goal == 'connection') {
        $characteristicsFilters[] = "(ShortDes LIKE '%social%' OR ShortDes LIKE '%group%')";
    }

    if ($energy == 'energetic') {
        $characteristicsFilters[] = "(ShortDes LIKE '%active%' OR ShortDes LIKE '%energetic%')";
    } elseif ($energy == 'calm') {
        $characteristicsFilters[] = "(ShortDes LIKE '%peaceful%' OR ShortDes LIKE '%relax%')";
    }

    if (!empty($characteristicsFilters)) {
        $query .= " AND (" . implode(" OR ", $characteristicsFilters) . ")";
    }

    // Add learning style preference
    if ($learning == 'structured') {
        $query .= " AND (ShortDes LIKE '%beginner%' OR ShortDes LIKE '%step-by-step%')";
    } elseif ($learning == 'experimental') {
        $query .= " AND (ShortDes LIKE '%explore%' OR ShortDes LIKE '%discover%')";
    }

    // Order by relevance to personality
    $query .= " ORDER BY 
        CASE
            WHEN (ShortDes LIKE '%" . mysqli_real_escape_string($connection, $personality) . "%') THEN 1
            WHEN (ShortDes LIKE '%" . mysqli_real_escape_string($connection, $learning) . "%') THEN 2
            WHEN (ShortDes LIKE '%" . mysqli_real_escape_string($connection, $goal) . "%') THEN 3
            ELSE 4
        END,
        RAND()
        LIMIT 6";

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

    // Fallback to random workshops if no matches found
    if (empty($workshops)) {
        $fallbackQuery = "SELECT * FROM workshop ORDER BY RAND() LIMIT 6";
        $fallbackResult = mysqli_query($connection, $fallbackQuery);
        
        while ($row = mysqli_fetch_assoc($fallbackResult)) {
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
    }

    echo json_encode($workshops);
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Form</title>
    <link rel="stylesheet" href="styles2.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
/* ========== General Styles ========== */
html, body {
    overflow-x: hidden;
    width: 100%;
    margin: 0;
    padding: 0;
}

body {
    padding-top: 120px;
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
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

/* ========== Survey Form Styles ========== */
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

/* ========== Results Section ========== */
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
    display: grid;
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

/* ========== Image Slider ========== */
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

/* ========== Header (مطابق لصفحة البروفايل) ========== */
header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.logo {
    flex: 0;
    margin-right: auto;
}

.logo img {
    height: 80px;
    width: 80px;
}

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
            <a href="<?php echo isset($_SESSION['user_id']) ? 'ProfilePage.php' : 'login.php'; ?>">
                <?php echo isset($_SESSION['user_id']) ? 'Profile' : 'Login'; ?>
            </a>
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
        <a href="<?php echo isset($_SESSION['user_id']) ? 'ProfilePage.php' : 'login.php'; ?>">
            <?php echo isset($_SESSION['user_id']) ? 'Profile' : 'Login'; ?>
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
            <h1>Discover Your Perfect Workshop Match!</h1>
            <p class="intro">Answer these questions to help us understand your unique interests</p>
            
            <!-- Question 1: Creative Personality -->
            <div class="question">
                <h3>Which description best fits your creative style?</h3>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="personality" value="expressive" required>
                         Expressive Artist - I love painting, drawing, and visual arts
                    </label>
                    <label class="option">
                        <input type="radio" name="personality" value="crafty">
                         Hands-On Creator - I enjoy crafts, DIY projects, and making things
                    </label>
                    <label class="option">
                        <input type="radio" name="personality" value="adventurous">
                         Nature Explorer - I prefer outdoor activities and nature experiences
                    </label>
                </div>
            </div>
            
            <!-- Question 2: Learning Preference -->
            <div class="question">
                <h3>How do you prefer to learn new skills?</h3>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="learning" value="structured" required>
                         Step-by-Step - I like clear instructions and guidance
                    </label>
                    <label class="option">
                        <input type="radio" name="learning" value="experimental">
                         Exploratory - I learn by trying things out myself
                    </label>
                    <label class="option">
                        <input type="radio" name="learning" value="social">
                         Collaborative - I learn best with others in a group
                    </label>
                </div>
            </div>
            
            <!-- Question 3: Workshop Goals -->
            <div class="question">
                <h3>What's your main goal for attending a workshop?</h3>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="goal" value="relaxation" required>
                         Relaxation - To unwind and enjoy a creative break
                    </label>
                    <label class="option">
                        <input type="radio" name="goal" value="skill">
                         Skill Development - To learn something practical
                    </label>
                    <label class="option">
                        <input type="radio" name="goal" value="connection">
                         Social Connection - To meet people with similar interests
                    </label>
                </div>
            </div>
            
            <!-- Question 4: Energy Level -->
            <div class="question">
                <h3>What energy level do you prefer in activities?</h3>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="energy" value="calm" required>
                         Calm - Peaceful, low-key activities
                    </label>
                    <label class="option">
                        <input type="radio" name="energy" value="balanced">
                         Balanced - Mix of activity and relaxation
                    </label>
                    <label class="option">
                        <input type="radio" name="energy" value="energetic">
                         Energetic - High-engagement, active participation
                    </label>
                </div>
            </div>
            
            <!-- Question 5: Workshop Atmosphere -->
            <div class="question">
                <h3>What workshop atmosphere appeals to you most?</h3>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="atmosphere" value="cozy" required>
                         Cozy - Intimate, comfortable spaces
                    </label>
                    <label class="option">
                        <input type="radio" name="atmosphere" value="studio">
                         Studio - Creative, artistic environment
                    </label>
                    <label class="option">
                        <input type="radio" name="atmosphere" value="adventure">
                        Adventure - Outdoor or unconventional settings
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn">Find My Perfect Workshops</button>
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
        personality: document.querySelector('input[name="personality"]:checked').value,
        learning: document.querySelector('input[name="learning"]:checked').value,
        goal: document.querySelector('input[name="goal"]:checked').value,
        energy: document.querySelector('input[name="energy"]:checked').value,
        atmosphere: document.querySelector('input[name="atmosphere"]:checked').value
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
        showError("An error occurred while finding your matches");
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


