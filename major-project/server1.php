<?php
// Define database connection constants
define('DB_SERVER', 'localhost'); // Change this to your database server address
define('DB_USERNAME', 'root'); // Change this to your database username
define('DB_PASSWORD', ''); // Change this to your database password
define('DB_DATABASE', 'majorproject'); // Change this to your database name

// Create a connection to the database
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Optionally, you can set the charset to utf8mb4 for full Unicode support
$mysqli->set_charset('utf8mb4');

// If you have other database-related setup, you can add it here

?>
