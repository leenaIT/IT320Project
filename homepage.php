<?php
ini_set('display_errors',1);
session_start();

$host = "localhost";
$dbname = "mehar";
$user = "root";
$pass = "root";

$conn = new mysqli($host, $user, $pass, $dbname, 8889);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$loggedIn = isset($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>homePage</title>
    <link rel="stylesheet" href="styles.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            width: 30px;
            height: 30px;
        }

        .icon-email {
            width: 42px;
            height: 42px;
        }

        .icon-location {
            width: 42px;
            height: 42px;
        }

        .icon-facebook,
        .icon-twitter,
        .icon-instagram {
            width: 35px;
            height: 35px;
        }

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

        @media (max-width: 480px) {
            .contact-info-1 {
                gap: 10px;
            }
            
            .icon-phone,
            .icon-email,
            .icon-location {
                width: 28px;
                height: 28px;
            }
            
            .icon-facebook,
            .icon-twitter,
            .icon-instagram {
                width: 30px;
                height: 30px;
            }
            
            .footer-logo-1 {
                width: 70px;
            }
        }
    </style>
</head>
<body class="no-background ">

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
            <a href="exploree.php">Explore</a>
            <a href="form.php">Survey</a>
            <a href="findcategory.php">Category</a>
           
        </nav>
    </div>

    <!-- قائمة سطح المكتب -->
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


    <?php
$sql_description = "SELECT Title, ShortDes, ImageURL FROM workshop WHERE WorkshopID = 4";
$result_desc = $conn->query($sql_description);
$desc_data = $result_desc->fetch_assoc();

$sql_thumbnails = "SELECT WorkshopID, Title, ShortDes, ImageURL FROM workshop WHERE WorkshopID IN (4,5,6,7)";
$result_thumbnails = $conn->query($sql_thumbnails);

?>

<div class="container2" id="container2" style="background-image: url('<?php echo htmlspecialchars($desc_data['ImageURL']); ?>');">
    <div class="description" id="description">
        <h1><?php echo htmlspecialchars($desc_data['Title']); ?></h1>
        <p><?php echo htmlspecialchars($desc_data['ShortDes']); ?></p>
    </div>
    
    <div class="thumbnails" id="thumbnails">
        <?php while ($row = $result_thumbnails->fetch_assoc()): ?>
            <div class="thumbnail" data-image="<?php echo htmlspecialchars($row['ImageURL']); ?>" 
                 data-title="<?php echo htmlspecialchars($row['Title']); ?>" 
                 data-description="<?php echo htmlspecialchars($row['ShortDes']); ?>">
                <img src="<?php echo htmlspecialchars($row['ImageURL']); ?>" alt="<?php echo htmlspecialchars($row['Title']); ?>">
                <h3><?php echo htmlspecialchars($row['Title']); ?></h3>
            </div>
        <?php endwhile; ?>
    </div>
</div>
        <div class="navigation">
            <button class="arrow" id="prev">&larr;</button>
            <div class="image-counter">
             <pre><span id="current-image">1</span>  /  <span id="total-images">5</span></pre>  
            </div>
            <button class="arrow" id="next">&rarr;</button>
        </div>
    </div>
    
    <section id="about" class="about-section">
        <div class="header-about-container">
            <div class="header-about">
                <div class="header-content-about">
                    <h1>
                        <span>Welcome to</span>
                        <span class="outline-text">Mehar</span>
                    </h1>
                    <p class="quote">“Your gateway to discovering new workshops and experiences.”</p>
                </div>
            </div>
    
            <div class="about-content">
                <div class="about-description">
                    <p>
                        Mehar is your ultimate platform to discover and book workshops tailored to your interests. Whether you are eager to learn a new skill or dive deeper into a hobby, we offer personalized recommendations that match your preferences. Take a quick survey to explore workshops that suit you best and enjoy seamless booking management.
                    </p>
                    <p class="survey">
                        Ready to find the perfect workshop for you? Click the link below to take the survey and get personalized suggestions!
                    </p>
                    <br>
                    <a href="Survey.php" class="about-btn">Take the Survey</a>
                </div>
            </div>
        </div>
    </section>
  <?php
$sql = "SELECT 
            r.Rating, 
            r.Comment, 
            u.FirstName, 
            u.LastName, 
            u.ProfilePhoto,
            w.ImageURL, 
            w.Title
        FROM review r
        JOIN users u ON r.UserID = u.UserID
        JOIN workshop w ON r.WorkshopID = w.WorkshopID";

$result = $conn->query($sql);
?>

<section class="cont-reviws">
    <h2 id="cli">Client Testimonials</h2>
    <div class="reviews">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fullName = htmlspecialchars($row['FirstName'] . " " . $row['LastName']);
                $comment = htmlspecialchars($row['Comment']);
                $rating = intval($row['Rating']);
                $workshopTitle = htmlspecialchars($row['Title']);

                // Generate star icons
                $stars = str_repeat('<i class="fas fa-star" style="color:#FFFF00;"></i>', $rating) .
                         str_repeat('<i class="far fa-star" style="color:#FFFF00;"></i>', 5 - $rating);

                // Profile photo
                $profilePhoto = ($row['ProfilePhoto']) ? 'uploads/' . $row['ProfilePhoto'] : 'uploads/default.jpg';

                // Workshop image
                $correctedPath = str_replace("\\", "/", $row['ImageURL']);
                $workshopImage = ($correctedPath) ? htmlspecialchars($correctedPath) : 'workshops/default-workshop.jpg';
        ?>
        <div class="carousel-item">
            <div class='profile-pic'>
            <!-- User photo -->
            <img src="<?= $profilePhoto ?>" alt="Client" class="user-photo">
            </div>
            <!-- User name -->
            <h3><?= $fullName ?></h3>

            <!-- Workshop section -->
            <div class="workshop-info">
                <img src="<?= $workshopImage ?>" alt="Workshop" class="workshop-photo" >
                <p class="workshop-title"><strong><?= $workshopTitle ?></strong></p>
            </div>

            <!-- Review comment -->
            <p class="review-text"><?= $comment ?></p>
            <!-- Rating stars -->
            <p class="stars"><?= $stars ?></p>
        </div>
        <?php
            }
        } else {
            echo "<p>No reviews available.</p>";
        }
        ?>
    </div>


   
</section>



    
    <br>
   
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

    <script>
    const container = document.getElementById("container2");
    const description = document.getElementById("description");
    const thumbnails = document.querySelectorAll(".thumbnail");
    const prevButton = document.getElementById("prev");
    const nextButton = document.getElementById("next");
    const currentImageSpan = document.getElementById("current-image");
    const totalImagesSpan = document.getElementById("total-images");

    let currentIndex = 0;

    
    totalImagesSpan.textContent = thumbnails.length;

    const updateBackground = (index) => {
      const thumbnail = thumbnails[index];
      const image = thumbnail.getAttribute("data-image");
      const title = thumbnail.getAttribute("data-title");
      const desc = thumbnail.getAttribute("data-description");

      container.style.backgroundImage = `url(${image})`;
      description.querySelector("h1").textContent = title;
      description.querySelector("p").textContent = desc;

      thumbnails.forEach((thumb, i) => {
        thumb.classList.toggle("active", i === index);  
      });

      currentImageSpan.textContent = index + 1;  
    };

    thumbnails.forEach((thumbnail, index) => {
      thumbnail.addEventListener("click", () => {
        currentIndex = index;
        updateBackground(index);
      });
    });

    prevButton.addEventListener("click", () => {
      currentIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
      updateBackground(currentIndex);
    });

    nextButton.addEventListener("click", () => {
      currentIndex = (currentIndex + 1) % thumbnails.length;
      updateBackground(currentIndex);
    });

    updateBackground(0);

    // Toggle Language function
      function toggleLanguage() {
    let htmlTag = document.documentElement;
    let navLinks = document.querySelectorAll(".nav-links li a");
    let menuLinks = document.querySelectorAll(".menu ul li a");
    let loginSignupButton = document.getElementById("login-signup");

    if (htmlTag.lang === "en") {
        htmlTag.lang = "ar";
        htmlTag.dir = "rtl";

        document.querySelector(".language-switch").textContent = "🌐 اللغة";

        navLinks[0].textContent = "استكشاف";
        navLinks[1].textContent = "تسجيل الدخول/التسجيل"; 

        menuLinks[0].textContent = "استكشاف";
        menuLinks[1].textContent = "تسجيل الدخول/التسجيل"; 
       
        loginSignupButton.textContent = "تسجيل الدخول/التسجيل"; 

    } else {
        location.reload();
    }
}



function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
        document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';

}


(function reviewsCarousel() {
    const carouselItems = document.querySelectorAll(".carousel-item");
    let activeIndex = 0;
    let startX = 0;

    function updateReviewsCarousel() {
        carouselItems.forEach((item, index) => {
            item.classList.remove("active", "left", "right", "hidden");

            if (index === activeIndex) {
                item.classList.add("active");
            } else if (index === (activeIndex - 1 + carouselItems.length) % carouselItems.length) {
                item.classList.add("left");
            } else if (index === (activeIndex + 1) % carouselItems.length) {
                item.classList.add("right");
            } else {
                item.classList.add("hidden");
            }
        });
    }

    function nextReview() {
        activeIndex = (activeIndex + 1) % carouselItems.length;
        updateReviewsCarousel();
    }

    function prevReview() {
        activeIndex = (activeIndex - 1 + carouselItems.length) % carouselItems.length;
        updateReviewsCarousel();
    }

    carouselItems.forEach((item) => {
        item.addEventListener("click", (e) => {
            const clickPosition = e.clientX;
            const itemWidth = item.clientWidth;

            if (clickPosition < itemWidth / 2) {
                prevReview();
            } else {
                nextReview();
            }
        });

        item.addEventListener("mousedown", (e) => {
            startX = e.clientX;
            item.style.transition = "none";
        });

        item.addEventListener("mousemove", (e) => {
            if (startX === 0) return;

            const diffX = e.clientX - startX;
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    prevReview();
                } else {
                    nextReview();
                }
                startX = 0;
            }
        });

        item.addEventListener("mouseup", () => {
            startX = 0;
            item.style.transition = "";
        });

        item.addEventListener("mouseleave", () => {
            startX = 0;
            item.style.transition = "";
        });
    });

    updateReviewsCarousel();
})();






</script>
</body>
</html>
