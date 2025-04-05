<?php
// Start the session to access session variables
session_start();

// Destroy all session variables
session_unset();  // Removes all session variables

// Destroy the session
session_destroy();  // Destroys the session

// Redirect the user to the login page or homepage after signing out
header("Location: homepage.php");  // You can change the redirection to wherever you want
exit();
?>
