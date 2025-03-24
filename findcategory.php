
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Page</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="header-footer2.css">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            background-color: #FFFDF0;
            text-align: center;
        }

        /* الهيدر */
        .header {
            background: #FFF3E0;
            padding: 40px 5%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* شريط التنقل */
        .navbar {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .navbar a {
            color: #333;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        /* تصميم الحاوية داخل الهيدر */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            width: 100%;
            margin-top: 30px;
        }

        /* المحتوى النصي */
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

        /* تصميم أزرار الفئات */
        .category-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            padding: 20px;
            flex-wrap: wrap;
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

        /* تأثير عند التمرير */
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

        /* تصميم قسم أفضل الورش */
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
<body>

    <header class="header">
        <div class="nav">
            <span><a href="homePage.html">Home</a></span>
            <span><a href="Explore.html">Explore</a></span>
            <span><a href="logIn.html" class="login-signup" id="login-signup">Login/Signup</a></span>
            <span><a href="Category.html">Category</a></span>
            <span><div class="language-switch" onclick="toggleLanguage()">🌐 Language</div></span>
        </div>

        <div class="header-container">
            <!-- المحتوى النصي -->
            <div class="header-text" data-aos="fade-right">
                <h1>Discover the <span class="highlight">Best Categories</span></h1>
                <p>Explore various categories of workshops including Cooking, Adventure, and Art. Enhance your skills and experience unique moments.</p>
            </div>
        </div>
    </header>

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
    <hr style="color:black; border-width:2px;">
    <footer class="footer" id="footer">
        <div class="footer-content">
            <div class="footer-left">
                <h4>Get In Touch</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <img src="workshops/360_F_553663238_v4Tva6Ie5Z5MhwCw0TknszcWuQ1ZAwQx.png" alt="Phone">
                    </div>
                    <div class="contact-item">
                        <img id="email" src="workshops/360_F_181003490_CxW4fQ0H3VypIIsPkFGpMDviO8ysWjOZ.png" alt="Email">
                    </div>
                    <div class="contact-item">
                        <img id="location" src="workshops/360_F_254622588_6OClHyYpak64rVI8y9QVjUvDlStsDEu9.png" alt="Location">
                    </div>
                </div>
            </div>
            <div class="footer-right">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <img id="facebook" src="workshops/black-square-outline-facebook-icon-7017516951347228u34mgnk68.png" alt="Facebook">
                    <img src="workshops/twitter-icon-256x227-kf6zqma5.png" alt="Twitter">
                    <img src="workshops/121.png" alt="Instagram">
                </div>
            </div>
        </div>
        <div class="footer-center">
            <p>© 2024 Website. All rights reserved.</p>
        </div>
    </footer>

    <!-- AOS Animation Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>

</body>
</html>
