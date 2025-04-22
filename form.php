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
    <!-- Google Fonts for Caveat, Playfair Display, Poppins, and Montserrat -->
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

        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Montserrat', sans-serif;
            background-color: #FFFDF0;
            color: #333;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6em 3em;
            background: linear-gradient(to bottom, #FFFDF0, rgba(255, 157, 35, 0.2));
            overflow: hidden;
            text-align: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23FF9D23" fill-opacity="0.15" d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,197.3C960,213,1056,203,1152,181.3C1248,160,1344,128,1392,112L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>') no-repeat;
            background-size: cover;
            z-index: 0;
        }

        .hero-text {
            max-width: 600px;
            z-index: 1;
            margin-bottom: 2em;
        }

        .hero-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            margin-bottom: 15px;
            color: #333;
        }

        .hero-text h1 .highlight {
            color: #FF9D23;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        /* Hero Images Container */
        .hero-images {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 1;
            margin-bottom: 1em;
            gap: 30px;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .hero-image-item {
            position: relative;
            width: 220px;
            height: 320px;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            z-index: 2;
            background: #FFFDF0;
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
            transform: rotate(-2deg);
        }

        .hero-image-item:nth-child(2) {
            transform: rotate(2deg);
        }

        .hero-image-item:nth-child(3) {
            transform: rotate(0deg);
        }

        .hero-image-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 10px;
            filter: brightness(1.05) contrast(1.1) sepia(0.2);
            transition: transform 0.4s ease, filter 0.4s ease;
        }

        .hero-image-item:hover {
            transform: scale(1.08) rotate(0deg);
            box-shadow: 0 8px 20px rgba(255, 157, 35, 0.4);
        }

        .hero-image-item:hover img {
            filter: brightness(1.1) contrast(1.15) sepia(0.2);
            transform: scale(1.02);
        }

        .hero-image-label {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
\            color: white;
            padding: 8px 15px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            font-family: 'Caveat', cursive;
        }

        .hero-image-item:hover .hero-image-label {
            opacity: 1;
            transform: translateX(-50%) translateY(-5px);
        }

        .hero-button button {
            background: #FF9D23;
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .hero-button button:hover {
            background: #e68b1f;
            transform: scale(1.1);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
        }

        /* Survey Section */
        .survey-section {
            position: relative;
            background: #FFFDF0;
            padding: 3em 2em;
            transition: background 0.5s ease;
        }

        .survey-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(255, 157, 35, 0.1), rgba(255, 157, 35, 0));
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .survey-background.art {
            background: linear-gradient(to bottom, rgba(255, 200, 100, 0.2), rgba(255, 157, 35, 0));
            opacity: 1;
        }

        .survey-background.cooking {
            background: linear-gradient(to bottom, rgba(255, 100, 100, 0.2), rgba(255, 157, 35, 0));
            opacity: 1;
        }

        .survey-background.adventure {
            background: linear-gradient(to bottom, rgba(100, 200, 150, 0.2), rgba(255, 157, 35, 0));
            opacity: 1;
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
            top: 50%;
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

        .journey-step:nth-child(1) { left: 5%; }
        .journey-step:nth-child(2) { left: 30%; }
        .journey-step:nth-child(3) { left: 55%; }
        .journey-step:nth-child(4) { left: 80%; }

        @keyframes pulse {
            0% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
            100% { transform: translateY(-50%) scale(1); }
        }

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

        .survey-box button {
            margin-top: 2.5em;
            background: #FDE5B7;
            color: #333;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
            animation: pulse-button 2s infinite;
        }

        .survey-box button:hover {
            background: #FF9D23;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @keyframes pulse-button {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
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

        .radio-choice input[type="radio"]:checked + label {
            color: #FF9D23;
        }

        .radio-choice input[type="radio"]:checked ~ .radio-choice {
            background: #FDE5B7;
            border-color: #FF9D23;
        }

        /* Results Section (Updated for Horizontal Layout) */
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
            flex-direction: row;
            gap: 20px;
            padding: 20px;
            overflow-x: auto; /* Enable horizontal scrolling */
            white-space: nowrap; /* Prevent wrapping */
        }

        .grid-item {
            position: relative;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            padding-bottom: 15px;
            transition: transform 0.3s ease;
            min-width: 250px; /* Ensure cards have a fixed minimum width */
            max-width: 300px; /* Ensure card doesn’t stretch too wide */
            display: inline-block; /* Ensure items stay in a single row */
        }

        .grid-item img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            display: block; /* Remove any unwanted spacing */
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
        @media (max-width: 768px) {
            .hero {
                padding: 4em 1.5em;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-images {
                flex-direction: row;
                gap: 15px;
                padding: 15px;
            }

            .hero-image-item {
                width: 160px;
                height: 240px;
                padding: 8px;
            }

            .hero-image-label {
                font-size: 0.9rem;
                padding: 6px 12px;
                opacity: 1;
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
            .hero-text h1 {
                font-size: 1.8rem;
            }

            .hero-image-item {
                width: 130px;
                height: 190px;
                padding: 6px;
            }

            .hero-images {
                gap: 10px;
                padding: 10px;
            }

            .hero-button button {
                padding: 12px 24px;
                font-size: 1rem;
            }

            .survey-box h2 {
                font-size: 1.8rem;
            }

            .choice img {
                width: 60px;
                height: 60px;
            }

            .journey-step {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
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
                <a href="Survey.php">Survey</a>
                <a href="findcategory.php">Category</a>
            </nav>
        </div>

        <nav class="desktop-nav">
            <a href="homepage.php">Home</a>
            <a href="<?php echo $loggedIn ? 'ProfilePage.php' : 'login.php'; ?>">
                <?php echo $loggedIn ? 'Profile' : 'Login'; ?>
            </a>
            <a href="Explore.php">Explore</a>
            <a href="Survey.php">Survey</a>
            <a href="findcategory.php">Category</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" data-aos="fade-up">
        <div class="hero-text" data-aos="fade-up">
            <h1>Embark on Your <span class="highlight">Workshop Journey</span></h1>
            <p>Answer a few questions, and we’ll guide you to workshops that match your passions.</p>
        </div>
        <div class="hero-images" data-aos="fade-up" data-aos-delay="100">
            <div class="hero-image-item" data-aos="zoom-in" data-aos-delay="100">
                <img src="workshops/adv.png" alt="Person as Adventurer">
                <span class="hero-image-label">Adventurer</span>
            </div>
            <div class="hero-image-item" data-aos="zoom-in" data-aos-delay="200">
                <img src="workshops/cooc.png" alt="Person as Chef">
                <span class="hero-image-label">Chef</span>
            </div>
            <div class="hero-image-item" data-aos="zoom-in" data-aos-delay="300">
                <img src="workshops/arti.png" alt="Person as Artist">
                <span class="hero-image-label">Artist</span>
            </div>
        </div>
        <div class="hero-button" data-aos="fade-up" data-aos-delay="400">
            <button onclick="scrollToSurvey()">Start Your Adventure</button>
        </div>
    </section>

    <!-- Survey Section -->
    <section class="survey-section" id="survey">
        <div class="survey-background" id="survey-background"></div>
        <div class="journey-map">
            <div class="journey-step active" data-step="1">1</div>
            <div class="journey-step" data-step="2">2</div>
            <div class="journey-step" data-step="3">3</div>
            <div class="journey-step" data-step="4">4</div>
        </div>
        <div class="survey-box" data-aos="fade-up">
            <form id="survey-form">
                <div class="question" id="question-1">
                    <h2>What type of activity do you prefer?</h2>
                     <div class="radio-choices">
                        <div class="radio-choice">
                            <input type="radio" name="day-preference" value="weekdays" id="weekdays" onclick="selectChoice('weekdays', 'day-preference')">
                            <label for="weekdays">Art</label>
                        </div>
                        <div class="radio-choice">
                            <input type="radio" name="day-preference" value="weekends" id="weekends" onclick="selectChoice('weekends', 'day-preference')">
                            <label for="weekends">Cooking</label>
                        </div>
                           <div class="radio-choice">
                            <input type="radio" name="day-preference" value="weekends" id="weekends" onclick="selectChoice('weekends', 'day-preference')">
                            <label for="weekends">Adventure</label>
                        </div>
                    </div>
                    <input type="hidden" name="activity" id="activity">
                </div>

                <div class="question" id="question-2" style="display: none;">
                    <h2>Do you prefer group or individual workshops?</h2>
                    <div class="radio-choices">
                        <div class="radio-choice">
                            <input type="radio" name="workshop-type" value="group" id="group" onclick="selectChoice('group', 'workshop-type')">
                            <label for="group">Group</label>
                        </div>
                        <div class="radio-choice">
                            <input type="radio" name="workshop-type" value="individual" id="individual" onclick="selectChoice('individual', 'workshop-type')">
                            <label for="individual">Individual</label>
                        </div>
                    </div>
                    <input type="hidden" name="workshop_type" id="workshop-type">
                </div>

                <div class="question" id="question-3" style="display: none;">
                    <h2>Do you prefer morning or evening sessions?</h2>
                    <div class="radio-choices">
                        <div class="radio-choice">
                            <input type="radio" name="time-preference" value="morning" id="morning" onclick="selectChoice('morning', 'time-preference')">
                            <label for="morning">Morning</label>
                        </div>
                        <div class="radio-choice">
                            <input type="radio" name="time-preference" value="evening" id="evening" onclick="selectChoice('evening', 'time-preference')">
                            <label for="evening">Evening</label>
                        </div>
                    </div>
                    <input type="hidden" name="time_preference" id="time-preference">
                </div>

                <div class="question" id="question-4" style="display: none;">
                    <h2>Do you prefer workshops on weekdays or weekends?</h2>
                    <div class="radio-choices">
                        <div class="radio-choice">
                            <input type="radio" name="day-preference" value="weekdays" id="weekdays" onclick="selectChoice('weekdays', 'day-preference')">
                            <label for="weekdays">Weekdays</label>
                        </div>
                        <div class="radio-choice">
                            <input type="radio" name="day-preference" value="weekends" id="weekends" onclick="selectChoice('weekends', 'day-preference')">
                            <label for="weekends">Weekends</label>
                        </div>
                    </div>
                    <input type="hidden" name="day_preference" id="day-preference">
                </div>

                <button type="button" onclick="nextQuestion()">Next</button>
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

        function selectChoice(choice, field) {
            document.getElementById(field).value = choice;

            // Update background for activity selection
            if (field === 'activity') {
                const background = document.getElementById('survey-background');
                background.className = 'survey-background ' + choice.toLowerCase();

                confetti({
                    particleCount: 50,
                    spread: 60,
                    origin: { y: 0.6 },
                    colors: ['#FF9D23', '#FDE5B7', '#FFFDF0']
                });
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
            fetch('getRecommendations.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Debug: Check the HTML returned
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