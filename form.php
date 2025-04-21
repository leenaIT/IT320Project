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
            padding: 5em 3em;
            background: linear-gradient(to bottom, #FFFDF0, rgba(255, 157, 35, 0.1));
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
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23FF9D23" fill-opacity="0.1" d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,197.3C960,213,1056,203,1152,181.3C1248,160,1344,128,1392,112L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>') no-repeat;
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
            margin-bottom: 2em;
            gap: 20px;
        }

        .hero-images::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background: rgba(255, 157, 35, 0.1);
            border-radius: 20px;
            z-index: -1;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .hero-image-item {
            position: relative;
            width: 200px;
            height: 300px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 2;
        }

        .hero-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hero-image-item:hover {
            transform: scale(1.05);
        }

        .hero-image-item:hover img {
            box-shadow: 0 6px 16px rgba(255, 157, 35, 0.5);
        }

        .hero-image-label {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 157, 35, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .hero-image-item:hover .hero-image-label {
            opacity: 1;
        }

        /* Hero Button */
        .hero-button {
            z-index: 1;
        }

        .hero-button button {
            background: #FDE5B7;
            color: #333;
            border: none;
            padding: 14px 28px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hero-button button:hover {
            background: #FF9D23;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @keyframes bob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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

        .survey-background.selfdev {
            background: linear-gradient(to bottom, rgba(100, 150, 255, 0.2), rgba(255, 157, 35, 0));
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

        .journey-step:nth-child(1) { left: 10%; }
        .journey-step:nth-child(2) { left: 40%; }
        .journey-step:nth-child(3) { left: 70%; }

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


        
     
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                padding: 3em 1.5em;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .hero-images {
                flex-direction: row;
                gap: 10px;
                margin-top: 20px;
            }

            .hero-image-item {
                width: 150px;
                height: 225px;
            }

            .hero-image-label {
                opacity: 1;
                font-size: 0.8rem;
            }

            .survey-section {
                padding: 2em 1em;
            }

            .survey-box {
                padding: 2em;
            }

          
        }

        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 1.8rem;
            }

            .hero-image-item {
                width: 120px;
                height: 180px;
            }

            .survey-box h2 {
                font-size: 1.8rem;
            }

            .how-it-works h2 {
                font-size: 1.8rem;
            }

            .choice img {
                width: 60px;
                height: 60px;
            }

            .step img {
                width: 60px;
            }

            .journey-step {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }

            .logo-tagline {
                display: none;
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
                <img src="workshops/Ad.png" alt="Person as Adventurer">
                <span class="hero-image-label">Adventurer</span>
            </div>
            <div class="hero-image-item" data-aos="zoom-in" data-aos-delay="200">
                <img src="workshops/co.png" alt="Person as Chef">
                <span class="hero-image-label">Chef</span>
            </div>
            <div class="hero-image-item" data-aos="zoom-in" data-aos-delay="300">
                <img src="workshops/ar.png" alt="Person as Artist">
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
        </div>
        <div class="survey-box" data-aos="fade-up">
            <h2>What activities spark your passion?</h2>
            <div class="choices">
                <div class="choice" onclick="selectChoice('Art')" data-aos="zoom-in">
                    <img src="workshops/art-icon.png" alt="Art">
                    <div>Art</div>
                </div>
                <div class="choice" onclick="selectChoice('Cooking')" data-aos="zoom-in" data-aos-delay="100">
                    <img src="workshops/cooking-icon.png" alt="Cooking">
                    <div>Cooking</div>
                </div>
                <div class="choice" onclick="selectChoice('Adventure')" data-aos="zoom-in" data-aos-delay="200">
                    <img src="workshops/adventure-icon.png" alt="Adventure">
                    <div>Adventure</div>
                </div>
                
            </div>
            <button onclick="nextQuestion()">Next</button>
        </div>
    </section>

  
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
        const totalSteps = 3;
        let lastChoice = '';

        function selectChoice(choice) {
            lastChoice = choice.toLowerCase().replace(' ', '');
            const background = document.getElementById('survey-background');
            background.className = 'survey-background ' + lastChoice;

            confetti({
                particleCount: 50,
                spread: 60,
                origin: { y: 0.6 },
                colors: ['#FF9D23', '#FDE5B7', '#FFFDF0']
            });
        }

        function nextQuestion() {
            if (currentStep < totalSteps) {
                currentStep++;
                document.querySelector('.journey-step.active').classList.remove('active');
                document.querySelector(`.journey-step[data-step="${currentStep}"]`).classList.add('active');
            } else {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#FF9D23', '#FDE5B7', '#FFFDF0']
                });
                alert('Great job! Let’s explore your matches!');
            }
        }
    </script>
</body>
</html>