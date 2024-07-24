<?php
require_once 'dbconfig.php';  // Make sure this file contains your database connection setup
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $theme_name = $_POST['theme_name'];
    $user_id = $_SESSION['userid'];

    // Handle image file upload
    $target_dir = "user_themes/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $theme_file = $target_dir . basename($_FILES["theme_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($theme_file, PATHINFO_EXTENSION));

    // Check if file is a valid image
    $check = getimagesize($_FILES["theme_file"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file extensions
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Attempt to upload file
        if (move_uploaded_file($_FILES["theme_file"]["tmp_name"], $theme_file)) {
            // Insert theme details into database
            $stmt = $pdo->prepare('INSERT INTO user_themes (userid, theme_name, theme_file) VALUES (?, ?, ?)');
            $stmt->execute([$user_id, $theme_name, basename($_FILES["theme_file"]["name"])]);

            // Create CSS file
            $css_file = 'user_themes/' . htmlspecialchars($theme_name) . '.css';
            $css_content = "
html, body." . htmlspecialchars($theme_name) . " {
    height: 100%; /* Ensure the body takes up the full height of the viewport */
    margin: 0; /* Remove default margins */
    padding: 0; /* Remove default padding */
}

body." . htmlspecialchars($theme_name) . " {
    font-family: Arial, sans-serif;
    background-image: url('../user_themes/" . basename($_FILES["theme_file"]["name"]) . "'); /* Path to your background image */
    background-size: cover; /* Ensure the background covers the entire viewport */
    background-repeat: no-repeat; /* Prevent repeating of the image */
    background-position: center; /* Center the image */
    background-attachment: fixed; /* Make the background fixed while scrolling */
    color: white; /* Text color for contrast */
}";
            file_put_contents($css_file, $css_content);

            echo "The file has been uploaded and CSS file created.";
            header('Location: settings.php');
            exit;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>


