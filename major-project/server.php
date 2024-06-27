<?php
// Database configuration
$db_hostname = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_database = "majorproject";

// Create connection
$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

try {
    // Connect to the MySQL database using PDO...
    $pdo = new PDO('mysql:host=' . $db_hostname . ';dbname=' . $db_database . ';charset=utf8', $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
}
?>
