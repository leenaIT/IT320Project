<?php
session_start();

$host = "localhost";
$dbname = "mehar";
$user = "root";
$pass = "root";

$connection = new mysqli($host, $user, $pass, $dbname, 8889);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
$loggedIn = isset($_SESSION['user_id']);

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST["first-name"]);
    $lastName = trim($_POST["last-name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $phone = trim($_POST["phone"]);

    // ✅ التحقق من الحقول الفارغة أولاً
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($phone)) {
        $errorMessage = "❌ Please fill all fields!";
    } 
    // ✅ التحقق من صحة الإيميل
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "❌ Invalid email format!";
    } 
    // ✅ التحقق من قوة الباسوورد قبل إدخاله
    elseif (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d).{8,}$/", $password)) {
        $errorMessage = "❌ Password must be at least 8 characters, include 1 letter and 1 number!";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $errorMessage = "❌ Mobile number must be exactly 10 digits!";
    }
    else {
        // ✅ التأكد أن الإيميل غير مستخدم مسبقًا
        $checkQuery = "SELECT Email FROM users WHERE Email = ?";
        $checkStmt = $connection->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errorMessage = "❌ This email is already registered! Redirecting to login...";
            
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 4000);
            </script>";

        } else {
            // ✅ إذا كل شيء صحيح، يتم تشفير كلمة المرور وإدخال البيانات
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO users (FirstName, LastName, Email, Password, Mobile) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($query);
            if (!$stmt) {
                $errorMessage = "❌ Database error!";
            } else {
                $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $phone);
                if ($stmt->execute()) {
                    $_SESSION["user_email"] = $email;
                    header("Location: login.php");
                    exit();
                } else {
                    $errorMessage = "❌ Registration failed!";
                }
                $stmt->close();
            }
        }
        $checkStmt->close();
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            <a href="homepage.php">Home</a>
            <a href="ProfilePage.php"><?php echo $loggedIn ? 'Profile' : 'Login'; ?></a>
            <a href="Explore.php">Explore</a>
            <a href="Survey.php">Survey</a>
            <a href="findcategory.php">Category</a>
            
        </nav>
</div>

<!-- القائمة الأصلية للكمبيوتر -->
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

    
    <?php if (!empty($errorMessage)): ?>
    <div id="alert-box" class="alert"><?php echo $errorMessage; ?></div>
   <?php endif; ?>


    <main>
        <div class="container">
            <div class="logo-box">
                <img src="workshops/gif.gif" alt="logo" height="150" width="150">
            </div>
            <div class="signup-box">
                <h2>Create Your Account</h2><br>
                <form action="signup.php" method="POST">
                    <label for="first-name">First Name</label>
                    <input type="text" name="first-name" id="first-name" value="<?php echo htmlspecialchars($_POST['first-name'] ?? ''); ?>">

                    <label for="last-name">Last Name</label>
                    <input type="text" name="last-name" id="last-name" value="<?php echo htmlspecialchars($_POST['last-name'] ?? ''); ?>">

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" oninput="checkPasswordStrength()" required>
                    <p class="password-requirements" id="password-error"> 
                        Password must be at least 8 characters, include 1 letter and 1 number.
                    </p><br>

                    <label for="phone">Mobile Number</label>
                    <input type="tel" name="phone" id="phone" placeholder="+966 5xxxxxxxx" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">

                    <button type="submit">Create Account</button>
                </form>
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
            }, 3000); 
        }
    });

function toggleLanguage() {
    let htmlTag = document.documentElement;
    let navLinks = document.querySelectorAll(".nav-links li a");
    let menuLinks = document.querySelectorAll(".menu ul li a");

    if (htmlTag.lang === "en") {
        htmlTag.lang = "ar";
        htmlTag.dir = "rtl";

        document.querySelector(".language-switch").textContent = "🌐 اللغة";

        document.querySelector(".signup-box h2").textContent = "إنشاء حساب";
        document.querySelector("label[for='first-name']").textContent = "الاسم الأول";
        document.querySelector("label[for='last-name']").textContent = "اسم العائلة";
        document.querySelector("label[for='email']").textContent = "البريد الإلكتروني";
        document.querySelector("label[for='password']").textContent = "كلمة المرور";
        document.querySelector(".password-requirements").textContent = "يجب أن تكون كلمة المرور 8 أحرف على الأقل، تحتوي على حرف واحد ورقم واحد، وتكون حساسة لحالة الأحرف.";
        document.querySelector("label[for='phone']").textContent = "رقم الجوال";
        document.querySelector("button").textContent = "إنشاء حساب";

        navLinks[0].textContent = "الرئيسية";
        navLinks[1].textContent = "استكشاف";

        menuLinks[0].textContent = "الرئيسية";
        menuLinks[1].textContent = "استكشاف";

    } else {
        location.reload();
    }
}

function toggleMenu(button) {
    button.classList.toggle('active');
    document.querySelector('.mobile-nav-container').classList.toggle('show');
    
    document.body.style.overflow = button.classList.contains('active') ? 'hidden' : '';
}


        function checkPasswordStrength() {
            let password = document.getElementById("password").value;
            let errorText = document.getElementById("password-error");
            const strongPasswordPattern = /^(?=.*[a-zA-Z])(?=.*\d).{8,}$/;

            if (strongPasswordPattern.test(password)) {
                errorText.style.color = "green";
                errorText.innerHTML = "✔ Strong password";
            } else {
                errorText.style.color = "red";
                errorText.innerHTML = "❌ Password must be at least 8 characters, include 1 letter and 1 number.";
            }
        }
    </script>
</body>
</html>