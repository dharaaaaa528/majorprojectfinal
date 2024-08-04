<?php
session_start();
include 'server.php';

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Fetch user details including role
$stmt = $pdo->prepare('SELECT role FROM userinfo WHERE userid = ?');
$stmt->execute([$_SESSION['userid']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('User not found');
}

// Check if the user has the 'admin' role
$isAdmin = ($user['role'] === 'admin');

if (!$isAdmin) {
    exit('Unauthorized');
}

// Handle deletion
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Delete the quiz content from the database
    $stmt = $pdo->prepare('DELETE FROM quizzes WHERE id = ?');
    $stmt->execute([$id]);
    
    // Redirect to the previous page or an appropriate page
    header('Location: testpage.php');
    exit;
} else {
    exit('Invalid ID');
}
?>
