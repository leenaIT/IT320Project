
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Page</title>
    
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="header.css">

    <style>

        
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
            background-color: #FFFDF0;
            text-align: center;
        }

      

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            width: 100%;
            margin-left: 20px;
            margin-top: 150px;

        }

        .header-text {
            text-align: left;
            max-width: 45%;
        }

        .header-text h1 {
            font-size: 42px;
            font-weight: bold;
            color: #333;
        }

        .highlight {
            color: #FF9D23;
        }

        .header-text p {
            font-size: 18px;
            color: #555;
            margin-top: 10px;
        }

        .category-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            padding: 20px;
            flex-wrap: wrap;
        }
        .category-section h2{
            margin-top: 30px;
        }

        
        .category-btn {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .category-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-btn span {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            text-transform: uppercase;
        }

        .category-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
        }

        .section-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .top-workshops {
            padding: 40px 5%;
            background: #FFF3E0;
        }

        .workshops-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .workshop-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
        }

        .workshop-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .workshop-card h3 {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .workshop-card p {
            font-size: 14px;
            color: #555;
            margin: 0 10px 10px;
        }

        .workshop-card:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="no-background ">
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
            <a href="Explore.php">Explore</a>
            <a href="login.php">Login/Signup</a>
            <a href="findcategory.php">Category</a>
            <div class="mobile-language-switch" onclick="toggleLanguage()">
                🌐 Language
            </div>
        </nav>
    </div>

    <nav class="desktop-nav">
        <a href="Explore.php">Explore</a>
        <a href="login.php">Login/Signup</a>
        <a href="findcategory.php">Category</a>
        <div class="language-switch" onclick="toggleLanguage()">
            🌐 Language
        </div>
    </nav>
</header>



<div class="header-container">
            <div class="header-text" data-aos="fade-right">
                <h1>Discover the <span class="highlight">Best Categories</span></h1>
                <p>Explore various categories of workshops including Cooking, Adventure, and Art. Enhance your skills and experience unique moments.</p>
            </div>
        </div>


       
<section class="category-section">
    <h2 class="section-title">Find Your Category!</h2>
    <div class="category-container">

        <a href="category.php?category=Cooking">
            <div class="category-btn" data-aos="fade-up">
                <img src="workshops/cake_art.jpeg" alt="Cooking">
                <span>Cooking</span>
            </div>
        </a>

        <a href="category.php?category=Adventure">
            <div class="category-btn" data-aos="fade-up" data-aos-delay="200">
                <img src="workshops/post3.jpeg" alt="Adventure">
                <span>Adventure</span>
            </div>
        </a>

        <a href="category.php?category=Art">
            <div class="category-btn" data-aos="fade-up" data-aos-delay="400">
                <img src="workshops/drawing.jpeg" alt="Art">
                <span>Art</span>
            </div>
        </a>

    </div>
</section>


    <section class="top-workshops">
        <h2 class="section-title">Top Workshops</h2>
        <div class="workshops-grid">
            <div class="workshop-card" data-aos="fade-up">
                <img src="/IT320Project/workshops/workshop1.jpg" alt="Workshop 1">
                <h3>Workshop 1</h3>
                <p>Learn the basics of cooking with our expert chefs.</p>
            </div>
            <div class="workshop-card" data-aos="fade-up" data-aos-delay="200">
                <img src="/IT320Project/workshops/workshop2.jpg" alt="Workshop 2">
                <h3>Workshop 2</h3>
                <p>Explore the wilderness with our adventure guides.</p>
            </div>
            <div class="workshop-card" data-aos="fade-up" data-aos-delay="400">
                <img src="/IT320Project/workshops/workshop3.jpg" alt="Workshop 3">
                <h3>Workshop 3</h3>
                <p>Unleash your creativity with our art workshops.</p>
            </div>
        </div>
    </section>

    <!-- footer.html -->
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
 function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
}

    </script>

</body>
</html>
