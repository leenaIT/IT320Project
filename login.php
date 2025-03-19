<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="script.js"></script>
</head>
<body class="with-background " >
<?php
// Start session before anything elsee
session_name('unique_session_name_for_project1');
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

// Check if form is submitted safely
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate if email and password are provided
    if (empty($_POST['email']) || empty($_POST['password'])) {
        header("Location: login.php?error=Please fill in all fields");
        exit();
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Query to fetch user data based on email
    $query = "SELECT UserID, Password FROM users WHERE Email = ?";
    $stmt = mysqli_prepare($Sconnection, $query);

    if (!$stmt) {
        die("Query preparation failed: " . mysqli_error($Sconnection));
    }

    // Bind the email parameter
    mysqli_stmt_bind_param($stmt, "s", $email);
    
    // Execute the query
    if (!mysqli_stmt_execute($stmt)) {
        die("Query execution failed: " . mysqli_error($Sconnection));
    }

    // Store result
    mysqli_stmt_store_result($stmt);

    // Check if user exists
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $user_id, $hashed_password);
        mysqli_stmt_fetch($stmt);

        
          // Hash the entered password using SHA-256 to compare it with the stored hash
    $hashed_entered_password = hash('sha256', $password);

    // Verify if the entered password's hash matches the stored hash
    if ($hashed_entered_password === $hashed_password) {
        // Password matches, log the user in
        $_SESSION['user_id'] = $user_id;
          // Debugging: Check if session is set correctly
            error_log("User logged in: " . $_SESSION['user_id']);
            var_dump($_SESSION); // Debugging session data

            // Redirect to profile page
            header("Location: profile.php");
            exit();
        } else {
            // Password doesn't match
            header("Location: login.php?error=Incorrect email or password");
            exit();
        }
    } else {
        // User not found
        header("Location: login.php?error=User not found");
        exit();
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($Sconnection);
}
?>

    <header class="with-background ">
        <div class="logo"><img src="workshops/logo.png" alt="logo" height="80" width="80"></div>
        
        <div class="nav-container">
            <nav>
                <ul class="nav-links">
                    <li><a href="homePage.html"> Home </a></li>
                    <li><a href="Explore.html"> Explore page</a></li>
                </ul>
            </nav>
            <div class="language-switch" onclick="toggleLanguage()">🌐 Language</div>
        </div>

        <div class="menu-toggle" onclick="toggleMenu()">☰</div>
    </header>

    <div class="menu">
        <ul>
            <li><a href="homePage.html">Home</a></li>
            <li><a href="Explore.html">Explore page</a></li>
        </ul>
    </div>

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
                <p>Don't have an account? <a href="sign-up.html" class="sign-up-page">Create an account</a></p>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-left-1">
            <h4>Get In Touch</h4>
            <div class="contact-info-1" id="contact-us">
                <div class="contact-item-1">
                    <img src="workshops/phone1.png" alt="Phone Icon">
                    <span class="single-line-1">+996 58765 43210</span>
                </div>
                <div class="contact-item-1">
                    <img src="workshops/mail-icon.png" alt="Email Icon">
                    <span class="single-line-1">mehar@gmail.com</span>
                </div>
                <div class="contact-item-1">
                    <img src="workshops/location1.png" alt="Location Icon">
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
            <p><strong>Social media</strong></p>
            <div class="social-icons-1">
                
                <img src="workshops/facebook1.png" alt="Facebook">
                <img src="workshops/X1.png" alt="Twitter">
                <img src="workshops/instagram1.png" alt="Instagram">
                <img src="workshops/linkedin1.png" alt="LinkedIn">
            </div>
        </div>
        
        <div class="footer-bottom-1">
            <p>© 2024 Website. All rights reserved.</p>
        </div>
    </footer>
    <script>
      function toggleLanguage() {
    let htmlTag = document.documentElement;
    let navLinks = document.querySelectorAll(".nav-links li a");
    let menuLinks = document.querySelectorAll(".menu ul li a");

    if (htmlTag.lang === "en") {
        htmlTag.lang = "ar";
        htmlTag.dir = "rtl"; 

        document.querySelector(".language-switch").textContent = " 🌐 اللغة  " ;
        document.querySelector(".login-box h2").textContent = "تسجيل الدخول إلى mehar.com";
        document.querySelector("label[for='email']").textContent = "البريد الإلكتروني";
        document.querySelector("input#email").placeholder = "mehar@email.com";
        document.querySelector("label[for='password']").textContent = "كلمة المرور";
        document.querySelector("button").textContent = "تسجيل الدخول";
        document.querySelector("p").innerHTML = "ليس لديك حساب؟ <a href='sign-up.html' class='sign-up-page'>إنشاء حساب</a>";

        navLinks[0].textContent = "الرئيسية";
        navLinks[1].textContent = "استكشاف";

        menuLinks[0].textContent = "الرئيسية";
        menuLinks[1].textContent = "استكشاف";
    } else {
        location.reload();
    }
}


function toggleMenu() {
    document.querySelector(".menu").classList.toggle("active");
}

</script>
</body>
</html>

