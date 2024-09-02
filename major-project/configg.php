<?php
// Database configuration
$db_hostname = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_database = "testt";

// Create connection
$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>