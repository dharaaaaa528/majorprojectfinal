<?php
session_start();
include 'server.php'; // Ensure this file includes the database connection

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
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
    exit('Access denied. Admins only.');
}

// Check if an ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Invalid ID.');
}

$quizId = intval($_GET['id']);

// Prepare and execute the deletion query
$query = $pdo->prepare('DELETE FROM quizzes WHERE id = ?');
$result = $query->execute([$quizId]);

if ($result) {
    // Redirect back to the quizzes page with a success message
    header('Location: contentpage2.php?message=Quiz deleted successfully');
} else {
    // Redirect back to the quizzes page with an error message
    header('Location: contentpage2.php?message=Failed to delete quiz');
}
exit;
