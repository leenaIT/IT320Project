<?php
session_start();


// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);

// Database connection settings
$Shost = "localhost";
$Sdatabase = "mehar";
$Suser = "root";
$Spass = "root";

// Establish database connection
$Sconnection = mysqli_connect($Shost, $Suser, $Spass, $Sdatabase, 8889);
if (!$Sconnection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize error message variable
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $errorMessage = "❌ Please fill in all fields!";
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Query to fetch user data based on email
        $query = "SELECT UserID, Password FROM users WHERE Email = ?";
        $stmt = mysqli_prepare($Sconnection, $query);

        if (!$stmt) {
            $errorMessage = "❌ Database query error!";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_bind_result($stmt, $user_id, $hashed_password);
                mysqli_stmt_fetch($stmt);

                // Hash the entered password to compare
                if (password_verify($password, $hashed_password)) {

                    $_SESSION['user_id'] = $user_id;
                    header("Location: Homepage.php");
                    exit();
                } else {
                    $errorMessage = "❌ Incorrect email or password!";
                }
            } else {
                $errorMessage = "❌ User not found!";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

mysqli_close($Sconnection);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="script.js"></script>
    <style>html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}
</style>
</head>
<body class="with-background">

    <header class="with-background ">
        <div class="logo"><img src="workshops/logo.png" alt="logo" height="80" width="80"></div>
        
   
<!-- زر الهامبرغر المحسن -->
<div class="hamburger" onclick="toggleMenu(this)">
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
</div>

<!-- القائمة المتنقلة المحسنة -->
<div class="mobile-nav-container">
    <nav class="mobile-nav">
         <a href="homePage.php">Home</a>
        <div class="mobile-language-switch" onclick="toggleLanguage()">
            🌐 Language
        </div>
    </nav>
</div>

<!-- القائمة الأصلية للكمبيوتر -->
<nav class="desktop-nav">
     <a href="homePage.php">Home</a>
    <div class="language-switch" onclick="toggleLanguage()">
        🌐 Language
    </div>
</nav>
    </header>

<?php if (!empty($errorMessage)): ?>
    <div id="alert-box" class="alert"><?php echo $errorMessage; ?></div>
<?php endif; ?>

<main>
    <div class="container">
        <div class="logo-box"><img src="workshops/gif.gif" alt="logo" height="150" width="150"></div>
        <div class="login-box">
            <h2>Login to mehar.com</h2>
            <form action="login.php" method="POST">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="you@email.com" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Login</button>
            </form>
            <br>
            <p>Don't have an account? <a href="signup.php" class="sign-up-page">Create an account</a></p>
        </div>
    </div>
</main>
 
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
    document.addEventListener("DOMContentLoaded", function() {
        var alertBox = document.getElementById("alert-box");
        if (alertBox) {
            alertBox.style.display = "block";
            setTimeout(function() {
                alertBox.style.display = "none";
            }, 3000); // Hide after 3 seconds
        }
    });

    function toggleLanguage() {
        let htmlTag = document.documentElement;
        let navLinks = document.querySelectorAll(".nav-links li a");
        let menuLinks = document.querySelectorAll(".menu ul li a");

        if (htmlTag.lang === "en") {
            htmlTag.lang = "ar";
            htmlTag.dir = "rtl"; 

            document.querySelector(".language-switch").textContent = " 🌐 اللغة  ";
            document.querySelector(".login-box h2").textContent = "تسجيل الدخول إلى mehar.com";
            document.querySelector("label[for='email']").textContent = "البريد الإلكتروني";
            document.querySelector("input#email").placeholder = "mehar@email.com";
            document.querySelector("label[for='password']").textContent = "كلمة المرور";
            document.querySelector("button").textContent = "تسجيل الدخول";
            document.querySelector("p").innerHTML = "ليس لديك حساب؟ <a href='sign-up.html' class='sign-up-page'>إنشاء حساب</a>";

            navLinks[0].textContent = "الرئيسية";
            navLinks[1].textContent = "استكشاف";
        } else {
            location.reload();
        }
    }

    function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
}
</script>

</body>
</html>