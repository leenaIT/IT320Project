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
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFDF0;
            color: #333;
            overflow-x: hidden;
        }

        /* Header Styling */
        header {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1em 2em;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .desktop-nav a,
        .mobile-nav a {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: #333;
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s ease;
        }

        .desktop-nav a:hover,
        .mobile-nav a:hover {
            color: #FF9D23;
        }

        .logo img {
            width: 80px;
        }

        .mobile-nav-container {
            display: none;
        }

        .hamburger {
            display: none;
        }

        /* Hero Section */
        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #00cfc7 0%, #7D5EFF 100%);
            padding: 5em 3em;
            color: white;
            flex-wrap: wrap;
            gap: 2em;
        }

        .hero-text {
            max-width: 600px;
            animation: fadeIn 1s ease-in-out;
        }

        .hero-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .hero-text button {
            background: #FF9D23;
            border: none;
            padding: 14px 28px;
            border-radius: 30px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .hero-text button:hover {
            background: #e68a00;
            transform: scale(1.05);
        }

        .hero-img img {
            width: 300px;
            animation: slideInRight 1s ease-in-out;
        }

        /* Survey Box */
        .survey-box {
            background: white;
            margin: 3em auto;
            padding: 3em;
            border-radius: 20px;
            max-width: 800px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .survey-box:hover {
            transform: translateY(-5px);
        }

        .progress-bar {
            height: 12px;
            background: #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .progress {
            height: 100%;
            width: 33%;
            background: #7D5EFF;
            transition: width 0.5s ease;
        }

        .survey-box h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 1.5em;
            color: #333;
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
            transition: transform 0.3s ease;
        }

        .choice:hover {
            transform: scale(1.1);
        }

        .choice img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #EEE7FF;
            padding: 12px;
            margin-bottom: 10px;
        }

        .survey-box button {
            margin-top: 2.5em;
            background: #7D5EFF;
            color: white;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .survey-box button:hover {
            background: #5c3cd1;
        }

        /* How It Works Section */
        .how-it-works {
            background: #F9F6FF;
            padding: 4em 2em;
            text-align: center;
        }

        .how-it-works h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 2em;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 50px;
            justify-items: center;
        }

        .step {
            max-width: 220px;
        }

        .step img {
            width: 70px;
            margin-bottom: 15px;
        }

        .step p {
            font-size: 0.95rem;
            color: #444;
            line-height: 1.5;
        }

        /* Footer */
        footer {
            margin-top: 3em;
            padding: 2em 3em;
            background: #fffefc;
            border-top: 3px solid #f9b013;
            color: #333;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            align-items: start;
        }

        .footer-left-1,
        .footer-center-1,
        .footer-right-1 {
            text-align: center;
        }

        .footer-left-1 h4,
        .footer-right-1 h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .contact-info-1 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .contact-item-1 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-item-1 img {
            width: 30px;
            height: 30px;
        }

        .footer-logo-1 {
            width: 100px;
            margin: 0 auto;
        }

        .social-icons-1 {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .social-icons-1 img {
            width: 30px;
            transition: transform 0.3s ease;
        }

        .social-icons-1 img:hover {
            transform: scale(1.2);
        }

        .footer-bottom-1 {
            grid-column: 1 / -1;
            text-align: center;
            margin-top: 1em;
        }

        .footer-bottom-1 p {
            font-size: 0.85rem;
            color: #f9b013;
            padding: 0.5em 0;
            border-top: 1px solid #ccc;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 3em 1.5em;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-img img {
                width: 200px;
            }

            .survey-box {
                margin: 2em 1em;
                padding: 2em;
            }

            .hamburger {
                display: block;
                cursor: pointer;
            }

            .desktop-nav {
                display: none;
            }

            .mobile-nav-container {
                display: block;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: #fff;
                transform: translateY(-100%);
                transition: transform 0.3s ease;
            }

            .mobile-nav-container.show {
                transform: translateY(0);
            }

            .mobile-nav {
                display: flex;
                flex-direction: column;
                padding: 1em;
            }

            .mobile-nav a {
                padding: 1em;
                font-size: 1.1rem;
            }

            footer {
                padding: 1.5em;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="workshops/logo.png" alt="Mehar Logo">
        </div>
        <div class="hamburger" onclick="toggleMenu(this)">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </div>
        <div class="mobile-nav-container">
            <nav class="mobile-nav">
                <a href="homepage.php">Home</a>
                <a href="<?php echo $loggedIn ? 'ProfilePage.php' : 'login.php'; ?>">
                    <?php echo $loggedIn ? 'Profile' : 'Login'; ?>
                </a>
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
        <div class="hero-text">
            <h1>Discover Your Perfect Workshop</h1>
            <p>Answer a few fun questions, and we’ll match you with workshops tailored to your passions.</p>
            <button onclick="scrollToSurvey()">Start the Journey</button>
        </div>
        <div class="hero-img">
            <img src="workshops/download (1).png" alt="Welcome Character">
        </div>
    </section>

    <!-- Survey Section -->
    <section class="survey-box" id="survey" data-aos="fade-up">
        <div class="progress-bar">
            <div class="progress" id="progress"></div>
        </div>
        <h2>What activities spark your passion?</h2>
        <div class="choices">
            <div class="choice" onclick="selectChoice('Art')">
                <img src="workshops/art-icon.png" alt="Art">
                <div>Art</div>
            </div>
            <div class="choice" onclick="selectChoice('Cooking')">
                <img src="workshops/cooking-icon.png" alt="Cooking">
                <div>Cooking</div>
            </div>
            <div class="choice" onclick="selectChoice('Adventure')">
                <img src="workshops/adventure-icon.png" alt="Adventure">
                <div>Adventure</div>
            </div>
            <div class="choice" onclick="selectChoice('Self Dev')">
                <img src="workshops/selfdev-icon.png" alt="Self Dev">
                <div>Self Dev</div>
            </div>
        </div>
        <button onclick="nextQuestion()">Next</button>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" data-aos="fade-up">
        <h2>How We Find Your Match</h2>
        <div class="steps">
            <div class="step" data-aos="zoom-in" data-aos-delay="100">
                <img src="workshops/step1.png" alt="Step 1">
                <p>Answer engaging questions to share what inspires you.</p>
            </div>
            <div class="step" data-aos="zoom-in" data-aos-delay="200">
                <img src="workshops/step2.png" alt="Step 2">
                <p>We analyze your preferences to understand your interests.</p>
            </div>
            <div class="step" data-aos="zoom-in" data-aos-delay="300">
                <img src="workshops/step3.png" alt="Step 3">
                <p>Get personalized workshop recommendations just for you!</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-left-1">
            <h4>Get In Touch</h4>
            <div class="contact-info-1">
                <div class="contact-item-1">
                    <img src="workshops/phone.png" alt="Phone Icon">
                    <span>+996 58765 43210</span>
                </div>
                <div class="contact-item-1">
                    <img src="workshops/mail.png" alt="Email Icon">
                    <span>mehar@gmail.com</span>
                </div>
                <div class="contact-item-1">
                    <img src="workshops/location.png" alt="Location Icon">
                    <span>Saudi Arabia</span>
                </div>
            </div>
        </div>
        <div class="footer-center-1">
            <a href="index.html">
                <img src="workshops/logo.png" alt="Mehar Logo" class="footer-logo-1">
            </a>
        </div>
        <div class="footer-right-1">
            <h4>Connect With Us</h4>
            <div class="social-icons-1">
                <a href="#"><img src="workshops/Facebook_icon_(black).svg" alt="Facebook"></a>
                <a href="#"><img src="workshops/X1.png" alt="Twitter"></a>
                <a href="#"><img src="workshops/CIS-A2K_Instagram_Icon_(Black).svg" alt="Instagram"></a>
            </div>
        </div>
        <div class="footer-bottom-1">
            <p>© 2025 Mehar. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-quad',
            once: true
        });

        function toggleMenu(button) {
            button.classList.toggle('active');
            const mobileNav = document.querySelector('.mobile-nav-container');
            mobileNav.classList.toggle('show');
            document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
        }

        function scrollToSurvey() {
            document.getElementById('survey').scrollIntoView({ behavior: 'smooth' });
        }

        let currentStep = 1;
        const totalSteps = 3; // Example: 3 questions
        const progressBar = document.getElementById('progress');

        function selectChoice(choice) {
            console.log(`Selected: ${choice}`);
            // Add logic to store the user's choice
        }

        function nextQuestion() {
            if (currentStep < totalSteps) {
                currentStep++;
                progressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
                // Add logic to load the next question
            } else {
                // Submit survey or redirect
                alert('Survey completed!');
            }
        }
    </script>
</body>
</html>