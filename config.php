<?php

// Database configuration
$servername = "";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Other configurations
define('BASE_URL', '');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Start a session (if needed)
session_start();

// Autoload classes (if you use classes)
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.class.php';
});

?>
