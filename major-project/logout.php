<?php
// Initialize the session
session_start();
// Destroy the session
session_destroy();
// Redirect to the login page
header('Location: main.php');
exit;
?>