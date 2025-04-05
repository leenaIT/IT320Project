<?php
// Database configuration
$host = 'localhost';
$dbname = 'mehar'; 
$username = 'root';             
$password = ' root';             

// Create a new MySQLi connection (procedural)
$connection = mysqli_connect($host, $username, $password, $dbname,8889);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
