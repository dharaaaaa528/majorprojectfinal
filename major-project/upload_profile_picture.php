<?php
session_start();
require_once 'server.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userid'];

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];

    // Ensure the file is an image
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo "Only JPEG, PNG, and GIF files are allowed.";
        exit();
    }

    // Move the file to the uploads directory
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Update the user's profile picture in the database
        $query = $conn->prepare("UPDATE userinfo SET profile_picture = ? WHERE userid = ?");
        if ($query === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $query->bind_param("si", $filePath, $userId);
        if ($query->execute()) {
            $_SESSION['profile_picture'] = $filePath;
            header("Location: profile.php");
            exit();
        } else {
            echo "Failed to update profile picture in the database.";
        }
        $query->close();
    } else {
        echo "Failed to upload file.";
    }
} else {
    echo "No file uploaded.";
}
?>

