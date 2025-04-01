<?php
// Database configuration
$host = 'localhost';
$dbname = 'mehar'; 
$username = 'root';             
$password = '';             

// Create a new MySQLi connection (procedural)
$connection = mysqli_connect($host, $username, $password, $dbname);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
