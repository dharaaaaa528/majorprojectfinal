<?php
require_once 'dbconfig.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch user information from userinfo table
$stmt = $pdo->prepare('SELECT profile_picture FROM userinfo WHERE userid = ?');
$stmt->execute([$_SESSION['userid']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Output the profile picture path for debugging
echo "Profile Picture Path: " . htmlspecialchars($user['profile_picture']);
?>
