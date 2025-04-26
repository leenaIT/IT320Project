<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Page</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="header.css">
    <link rel="icon" type="image/png" href="workshops/logo.png">


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

    /* Base Styles */
    body {
        margin: 0;
        background-color: #FFFDF0;
        color: #333;
        line-height: 1.6;
        overflow-x: hidden;
    }

    /* Main Content Styles */
    .main-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 100px auto 60px;
        padding: 0 20px;
    }

    .header-text {
        text-align: left;
        max-width: 70%;
        margin-top: 30px;
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
    }

    /* Category Section */
    .category-section {
        margin: 80px auto;
        text-align: center;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 40px;
        text-align: center;
    }

    .category-container {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
        margin: 0 auto;
    }

    .category-btn {
        position: relative;
        width: 280px;
        height: 280px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease-in-out;
    }

    .category-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .category-btn span {
        position: absolute;
        bottom: 25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1.4rem;
        font-weight: bold;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        text-transform: uppercase;
    }

    .category-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
    }

    .category-btn:hover img {
        transform: scale(1.1);
    }

    .top-workshops {
        padding-top: 40px;
        background: #FFF3E0;
    }

    /* Grid Style for Top Workshops */
    .workshops-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    

    .workshop-card {
        position: relative;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        overflow: hidden;
        padding-bottom: 15px;
        transition: transform 0.3s ease;
    }

    .workshop-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }

    .workshop-card .tag {
        position: absolute;
        top: 15px;
        left: -10px;
        background-color: #FF9D23;
        color: #fff;
        padding: 5px 15px;
        font-size: 14px;
        border-radius: 0 10px 10px 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .workshop-card h3 {
        margin: 10px 5px 5px 5px;
        font-size: 16px;
        color: #FF9D23;
        text-align: center;
    }

    .workshop-card p {
        font-size: 14px;
        color: #555;
        margin: 5px 15px;
        text-align: center;
    }

    .workshop-card .details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px;
        margin-top: 10px;
    }

    .workshop-card .price {
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
        font-size: 14px;
        text-decoration: none;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 44px;
        min-height: 44px;
        text-align: center;
        line-height: 28px;
    }

    .more-btn:hover {
        background-color: #FF9D23;
        transform: scale(1.1); 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); 
        color: white;
    }

    .workshop-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

 

    /* Responsive Breakpoints */
    @media (max-width: 1024px) {
        .header-text h1 {
            font-size: 2.4rem;
        }
        
        .category-btn {
            width: 240px;
            height: 240px;
        }
        
        .header-text p {
            max-width: 90%;
        }
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: 60px auto 40px;
        }
        
        .header-text {
            max-width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        
        .header-text h1 {
            font-size: 2rem;
        }
        
        .header-text p {
            max-width: 100%;
            font-size: 1rem;
        }
        
        .category-btn {
            width: 200px;
            height: 200px;
        }
        
        .category-btn span {
            font-size: 1.2rem;
        }
        
        .workshops-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }
      

    @media (max-width: 480px) {
        .header-container {
            margin: 40px auto 30px;
        }
        
        .header-text h1 {
            font-size: 1.8rem;
             margin-top: 60px;
             text-align: left;

        }
        .header-text p {
           text-align: left; 
        }

        
        
        .section-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
        }
        
        .category-container {
            gap: 20px;
        }
        
        .category-btn {
            width: 160px;
            height: 160px;
        }
        
        .workshops-grid {
            grid-template-columns: 1fr;
        }
        
        .workshop-card {
            margin-bottom: 20px;
        }
        
        .more-btn {
            padding: 8px 15px;
            font-size: 13px;
        }
        
        body {
            font-size: 14px;
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
<body class="no-background">
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
            <a href="exploree.php">Explore</a>
            <a href="form.php">Survey</a>
            <a href="findcategory.php">Category</a>
            
        </nav>
    </div>

    <nav class="desktop-nav">
        <a href="homepage.php">Home</a>
        <a href="<?php echo $loggedIn ? 'ProfilePage.php' : 'login.php'; ?>">
            <?php echo $loggedIn ? 'Profile' : 'Login'; ?>
        </a>
            <a href="exploree.php">Explore</a>
        <a href="form.php">Survey</a>
        <a href="findcategory.php">Category</a>
    </nav>
</header>

    <div class="main-content">
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
                <?php
                require 'database.php';

                $topWorkshops = ["Coffee Art", "Candle Making", "Fly over the sea"];
                $placeholders = implode(',', array_fill(0, count($topWorkshops), '?'));

                $sql = "SELECT WorkshopID, Title, ImageURL, Location, ShortDes, Price FROM workshop WHERE Title IN ($placeholders)";
                $stmt = $connection->prepare($sql);

                $stmt->bind_param(str_repeat('s', count($topWorkshops)), ...$topWorkshops);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $delay = 0;
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="workshop-card" data-aos="fade-up" data-aos-delay="'.$delay.'">';
                        echo '<div class="tag">'.htmlspecialchars($row['Location']).'</div>';
                        echo '<img src="'.$row['ImageURL'].'" alt="'.htmlspecialchars($row['Title']).'">';
                        echo '<h3>'.htmlspecialchars($row['Title']).'</h3>';
                        echo '<p>'.(!empty($row['ShortDes']) ? htmlspecialchars($row['ShortDes']) : 'Discover the art of '.htmlspecialchars($row['Title'])).'</p>';
                        echo '<div class="details">';
                        echo '<a href="booking.php?workshopID='.$row['WorkshopID'].'" class="more-btn">More details</a>';
                        echo '<span class="price">';
                        echo htmlspecialchars($row['Price']);
                        echo '<img src="workshops/riyal.png" alt="SAR" class="riyal-icon">';
                        echo '</span>';
                        echo '</div>';
                        echo '</div>';
                        $delay += 200;
                    }
                } else {
                    echo '<div class="no-results">No workshops found</div>';
                }
                $connection->close();
                ?>
            </div>
        </section>
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
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
    </script>
</body>
</html>