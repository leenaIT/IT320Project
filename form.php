<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey | Mehar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <link rel="stylesheet" href="header.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;600&family=Poppins:wght@400;600&family=Caveat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Consistent font application */
        .desktop-nav a,
        .language-switch,
        .mobile-nav a,
        .mobile-language-switch {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Footer */
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

        /* Base Styles */
        body {
            margin: 0;
            background-color: #FFFDF0;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Main Content Styles */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Section (Aligned with Category page) */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 100px auto 60px; /* Restored to original to keep images in place */
            padding: 0 20px;
            flex-wrap: wrap;
        }

        .header-text {
            flex: 1;
            max-width: 70%;
            text-align: left;
            position: relative;
            top: -80px; /* Move the text up without affecting images */
        }

        .header-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .highlight {
            color: #FF9D23;
        }

        .header-text p {
            font-size: 1.1rem;
            color: #555;
            max-width: 80%;
                        margin-bottom: 20px;

        }

        /* Start Button */
        .header-button button {
            display: inline-block;
            background-color: #FF9D23;
            color: white;
            padding: 5px 25px;
            border-radius: 30px;
            font-size: 1rem;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 8px rgba(255, 157, 35, 0.3);
        }

        .header-button button:hover {
            background-color: #e68a1a;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(255, 157, 35, 0.4);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Image Cluster Section (On the Right) */
        .header-images-right {
            flex: 1;
            display: flex;
            justify-content: flex-end;
            max-width: 50%;
        }

        .image-cluster-container {
            position: relative;
            width: 400px;
            height: 600px;
        }

        .image-cluster-item {
            position: absolute;
            width: 200px;
            height: 300px;
            border: 3px solid #FF9D23;
            border-radius: 0;
            overflow: hidden;
            transition: transform 0.4s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .image-cluster-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .image-cluster-item.top-left {
            top: 0;
            left: 0;
            transform: rotate(-5deg);
        }

        .image-cluster-item.top-right {
            top: 0;
            right: 0;
            transform: rotate(5deg);
        }

        .image-cluster-item.bottom-center {
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) rotate(0deg);
        }

        .image-cluster-item:hover {
            transform: scale(1.1) rotate(0deg);
            z-index: 10;
            box-shadow: 0 12px 25px rgba(255, 157, 35, 0.3);
        }

        /* Survey Section */
        .survey-section {
            position: relative;
            background: #FFFDF0;
            padding: 3em 2em;
        }

        .journey-map {
            position: relative;
            height: 100px;
            margin-bottom: 2em;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 100"><path d="M0,50 Q200,20 400,50 T800,50" stroke="%23FF9D23" stroke-width="4" fill="none" stroke-dasharray="10,10"/></svg>') no-repeat center;
            background-size: contain;
        }

        .journey-step {
            position: absolute;
            top: 42%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: #FDE5B7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .journey-step.active {
            background: #FF9D23;
            color: white;
            animation: pulse 1s infinite;
        }

        .journey-step:nth-child(1) { left: 15%; }
        .journey-step:nth-child(2) { left: 30%; }
        .journey-step:nth-child(3) { left: 55%; }
        .journey-step:nth-child(4) { left: 80%; }

        .survey-box {
            background: white;
            margin: 0 auto;
            padding: 3em;
            border-radius: 15px;
            max-width: 800px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .survey-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .survey-box h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            margin-bottom: 1.5em;
            color: #333;
            text-align: center;
        }

        .choices {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        .choice {
            text-align: center;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 10px;
            border-radius: 10px;
        }

        .choice:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .choice img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #FDE5B7;
            padding: 12px;
            margin-bottom: 10px;
            transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .choice:hover img {
            background: #FF9D23;
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(255, 157, 35, 0.5);
        }

        /* Radio Button Choices */
        .radio-choices {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .radio-choice {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border: 2px solid #FDE5B7;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .radio-choice:hover {
            background: #FDE5B7;
            border-color: #FF9D23;
        }

        .radio-choice input[type="radio"] {
            display: none;
        }

        .radio-choice label {
            cursor: pointer;
            font-weight: 600;
            color: #333;
        }
        .radio-choice label {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
}


        .radio-choice input[type="radio"]:checked + label {
            color: #FF9D23;
        }

        .radio-choice input[type="radio"]:checked ~ .radio-choice {
            background: #FDE5B7;
            border-color: #FF9D23;
        }

        /* Results Section */
        .results {
            margin-top: 2em;
            padding: 0 2em;
        }

        .results h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #333;
            text-align: center;
            margin-bottom: 1.5em;
        }

     .grid {
    display: flex;
    flex-wrap: nowrap; /* still allows horizontal scrolling */
    gap: 40px;          /* this now works properly */
    padding: 20px;
    overflow-x: auto;
}


        .grid-item {
                margin-right: 40px;

            position: relative;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            padding-bottom: 15px;
            transition: transform 0.3s ease;
            min-width: 250px;
            max-width: 300px;
            display: inline-block;
        }

.grid-item:last-child {
    margin-right: 0;
}


        .grid-item img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            display: block;
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
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

        .grid-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .no-results {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            padding: 2em;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .header-text h1 {
                font-size: 2.4rem;
            }

            .header-text p {
                max-width: 90%;
            }

            .image-cluster-container {
                width: 350px;
                height: 500px;
            }

            .image-cluster-item {
                width: 180px;
                height: 270px;
            }

            .header-text {
                top: -30px; /* Slightly less adjustment for smaller screens */
            }
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin: 60px auto 40px; /* Matches Category page */
            }

            .header-text {
                max-width: 100%;
                text-align: center;
                top: -20px; /* Adjusted for smaller screens */
            }

            .header-text h1 {
                font-size: 2rem;
            }

            .header-text p {
                max-width: 100%;
                font-size: 1rem;
            }

            .header-images-right {
                max-width: 100%;
                justify-content: center;
            }

            .image-cluster-container {
                width: 300px;
                height: 450px;
            }

            .image-cluster-item {
                width: 150px;
                height: 225px;
            }

            .survey-section {
                padding: 2em 1em;
            }

            .survey-box {
                padding: 2em;
            }

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

        @media (max-width: 480px) {
            .header-container {
                margin: 40px auto 30px; /* Matches Category page */
            }

            .header-text h1 {
                font-size: 1.8rem;
                margin-top: 60px; /* Matches Category page */
                text-align: left;
            }

            .header-text p {
                text-align: left;
                font-size: 0.9rem;
            }

            .header-text {
                top: -15px; /* Minimal adjustment for smallest screens */
            }

            .image-cluster-container {
                width: 250px;
                height: 400px;
            }

            .image-cluster-item {
                width: 120px;
                height: 180px;
            }

            .header-button button {
                padding: 8px 20px;
                font-size: 0.9rem;
            }

            .survey-box h2 {
                font-size: 1.8rem;
            }

            .journey-step {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }

            .choice img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="workshops/logo.png" alt="logo">
        </div>

        <div class="hamburger" onclick="toggleMenu(this)">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </div>

        <div class="mobile-nav-container">
            <nav class="mobile-nav">
                <a href="homepage.php">Home</a>
                <a href="ProfilePage.php"><?php echo $loggedIn ? 'Profile' : 'Login'; ?></a>
                <a href="Explore.php">Explore</a>
                <a href="form.php">Survey</a>
                <a href="findcategory.php">Category</a>
            </nav>
        </div>

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

    <!-- Header Section -->
    <div class="main-content">
        <div class="header-container">
            <!-- Text Section on the Left -->
            <div class="header-text" data-aos="fade-right">
                <h1>Embark on Your <span class="highlight">Workshop Journey</span></h1>
                <p>Answer a few questions, and we’ll guide you to workshops that match your passions.</p>
                <div class="header-button" data-aos="fade-up" data-aos-delay="400">
                    <button onclick="scrollToSurvey()">Start Your journey</button>
                </div>
            </div>

            <!-- Image Cluster on the Right -->
            <div class="header-images-right" data-aos="fade-left" data-aos-delay="100">
                <div class="image-cluster-container">
                    <!-- Top Left Image -->
                    <div class="image-cluster-item top-left">
                        <img src="workshops/arti.png" alt="Artist">
                    </div>
                    <!-- Top Right Image -->
                    <div class="image-cluster-item top-right">
                        <img src="workshops/adv.png" alt="Adventurer">
                    </div>
                    <!-- Bottom Center Image -->
                    <div class="image-cluster-item bottom-center">
                        <img src="workshops/cooc.png" alt="Chef">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Survey Section -->
    <section class="survey-section" id="survey">
        <div class="journey-map">
            <div class="journey-step active" data-step="1">1</div>
            <div class="journey-step" data-step="2">2</div>
            <div class="journey-step" data-step="3">3</div>
            <div class="journey-step" data-step="4">4</div>
        </div>
        <div class="survey-box" data-aos="fade-up">
            <form id="survey-form">
                <div class="question" id="question-1">
                    <h2>What do you enjoy doing in your free time?</h2>
                 <div class="radio-choices">
    <div class="radio-choice">
        <label>
            <input type="radio" name="activity" value="Art" onclick="selectChoice('Art', 'activity')">
I'm into drawing and creative hands-on projects

        </label>
    </div>
    <div class="radio-choice">
        <label>
            <input type="radio" name="activity" value="Cooking" onclick="selectChoice('Cooking', 'activity')">
           I love trying new recipes and cooking fun dishes


        </label>
    </div>
    <div class="radio-choice">
        <label>
            <input type="radio" name="activity" value="Adventure" onclick="selectChoice('Adventure', 'activity')">
             I enjoy outdoor adventures and exploring new places
        </label>
    </div>
</div>
                    <input type="hidden" name="activity" id="activity">
                </div>

                <div class="question" id="question-2" style="display: none;">
                    <h2>Do you prefer group or individual workshops?</h2>
                 <div class="radio-choices">
    <div class="radio-choice">
        <label>
            <input type="radio" name="workshop-type" value="group" onclick="selectChoice('group', 'workshop-type')">
            Group
        </label>
    </div>
    <div class="radio-choice">
        <label>
            <input type="radio" name="workshop-type" value="individual" onclick="selectChoice('individual', 'workshop-type')">
            Individual
        </label>
    </div>
</div>

                    <input type="hidden" name="workshop_type" id="workshop-type">
                </div>

                <div class="question" id="question-3" style="display: none;">
                    <h2>Do you prefer morning or evening sessions?</h2>
                  <div class="radio-choices">
    <div class="radio-choice">
        <label>
            <input type="radio" name="time-preference" value="morning" onclick="selectChoice('morning', 'time-preference')">
            Morning
        </label>
    </div>
    <div class="radio-choice">
        <label>
            <input type="radio" name="time-preference" value="evening" onclick="selectChoice('evening', 'time-preference')">
            Evening
        </label>
    </div>
</div>

                    <input type="hidden" name="time_preference" id="time-preference">
                </div>

                <div class="question" id="question-4" style="display: none;">
                    <h2>Do you prefer workshops on weekdays or weekends?</h2>
                 <div class="radio-choices">
    <div class="radio-choice">
        <label>
            <input type="radio" name="day-preference" value="weekdays" onclick="selectChoice('weekdays', 'day-preference')">
            Weekdays
        </label>
    </div>
    <div class="radio-choice">
        <label>
            <input type="radio" name="day-preference" value="weekends" onclick="selectChoice('weekends', 'day-preference')">
            Weekends
        </label>
    </div>
</div>

                    <input type="hidden" name="day_preference" id="day-preference">
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div class="results" id="results" style="display: none;">
            <h2>Your Recommended Workshops</h2>
            <div id="results-grid"></div>
        </div>
    </section>

    <!-- Footer -->
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

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-quad',
            once: true
        });

        function toggleMenu(button) {
            button.classList.toggle('active');
            document.querySelector('.mobile-nav-container').classList.toggle('show');
            document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
        }

        function scrollToSurvey() {
            document.getElementById('survey').scrollIntoView({ behavior: 'smooth' });
        }

        let currentStep = 1;
        const totalSteps = 4;
        let hasAnswered = false;

        function selectChoice(choice, field) {
            document.getElementById(field).value = choice;

            if (!hasAnswered) {
                hasAnswered = true;
                setTimeout(() => {
                    nextQuestion();
                    hasAnswered = false;
                }, 800);
            }
        }

        function nextQuestion() {
            if (currentStep < totalSteps) {
                document.getElementById(`question-${currentStep}`).style.display = 'none';
                currentStep++;
                document.getElementById(`question-${currentStep}`).style.display = 'block';
                document.querySelector('.journey-step.active').classList.remove('active');
                document.querySelector(`.journey-step[data-step="${currentStep}"]`).classList.add('active');
            } else {
                submitSurvey();
            }
        }

        function submitSurvey() {
            const formData = new FormData(document.getElementById('survey-form'));
            fetch('getrecommendation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                document.getElementById('results-grid').innerHTML = data;
                document.getElementById('results').style.display = 'block';
                document.getElementById('results').scrollIntoView({ behavior: 'smooth' });

                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#FF9D23', '#FDE5B7', '#FFFDF0']
                });
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('results-grid').innerHTML = '<div class="no-results">An error occurred. Please try again.</div>';
                document.getElementById('results').style.display = 'block';
            });
        }
    </script>
</body>
</html>