<?php
ob_start();
require_once 'dbconfig.php';  // Make sure this file contains your database connection setup
require_once 'header.php';    // Include header with theme setting logic
require_once 'topnav.php';
require_once 'sessiontimeout.php';
// Initialize the session theme if not set
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'default'; // Set a default theme if none is selected
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['theme'])) {
        $selected_theme = $_POST['theme'];
        $user_id = $_SESSION['userid'];

        // Update user's theme in the userinfo table
        $stmt = $pdo->prepare('UPDATE userinfo SET theme = ? WHERE userid = ?');
        $stmt->execute([$selected_theme, $user_id]);

        // Update the theme in the session
        $_SESSION['theme'] = $selected_theme;

        // Reload the page to apply the new theme
        header('Location: settings.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <style>
      

        .sidebar {
            
           width: 200px;
           background-color: #000;
           height: calc(100vh - 20px);
           position: fixed;
           top: 99px;
           left: 0;
           padding-top: 20px;
           color: #fff;
           border-right: 2px solid white;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar a.settings-link {
            color: #56C2DD;
        }

        .content {         
            margin-left: 200px; /* Space for the side navigation */
            padding: 20px;
            width: calc(100% - 200px); /* Adjust width based on sidebar */
            box-sizing: border-box;
            height: calc(100vh - 50px); /* Adjust based on top nav height */
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.7);
            height: 100vh;            
       }
       .theme-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
       }
       .theme-option {
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
       }
       .theme-option img {
            width: 400px; /* Adjust the width as needed */
            height: 400px; /* Adjust the height as needed */
            object-fit: cover; /* Ensure images cover the specified dimensions */
            border-radius: 5px;
            border: 8px solid white;
       }
       .theme-option.selected {
            border-color: #;
       }
       .settings-container .submit-button {
            width: 200px;
            height: 60px;
            background-color: #007bff; /* Blue color for the button */
            color: white; /* White text color */
            font-size: 18px; /* Increase the text size */
            border: none; /* Remove the border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Change cursor to pointer on hover */
            transition: background-color 0.3s ease; /* Smooth transition for background color */
            align-self: flex-end; /* Align the button to the end of the flex container */
            margin: 0; /* Remove any margin */
            margin-top: 30px;
       }
       .settings-container .submit-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
       }
       .sub-menu {
            padding-left: 30px;
       }
       .sub-menu a {
            font-size: 16px;
       }


       
     
    </style>
</head>
<body>
<br><br><br><br><br>
    <div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <div class="sub-menu">
            <a href="certificate_details.php" class="details-link"><u>Certificate Details</u></a>
        </div>
        <div class="sub-menu">
        <a href="delete_account.php" class="details1-link"><u>Delete Account</u></a>
    </div>
        <a href="progress.php"><u>Progress</u></a>
        <a href="certificate.php"><u>Quiz Certifications</u></a>
        <a href="test_certificate.php"><u>Test Certifications</u></a>
        <a href="settings.php" class="settings-link"><u>Settings</u></a>
    </div>
    <div class="content">
    <div class="settings-container">
    <h1>Settings</h1>
    <form method="post" action="">
        <label>Select Theme:</label>
        <div class="theme-options">
            <!-- Default Themes -->
            <label class="theme-option <?= isset($_SESSION['theme']) && $_SESSION['theme'] == 'light' ? 'selected' : '' ?>">
                <input type="radio" name="theme" value="light" <?= isset($_SESSION['theme']) && $_SESSION['theme'] == 'light' ? 'checked' : '' ?>>
                <img src="background3.jpg" alt="Light Theme">
                <span>Light Theme</span>
            </label>
            <label class="theme-option <?= isset($_SESSION['theme']) && $_SESSION['theme'] == 'default' ? 'selected' : '' ?>">
                <input type="radio" name="theme" value="default" <?= isset($_SESSION['theme']) && $_SESSION['theme'] == 'default' ? 'checked' : '' ?>>
                <img src="background.jpg" alt="Default Theme">
                <span>Default Theme</span>
            </label>
            <label class="theme-option <?= isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'selected' : '' ?>">
                <input type="radio" name="theme" value="dark" <?= isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'checked' : '' ?>>
                <img src="background1.jpg" alt="Dark Theme">
                <span>Dark Theme</span>
            </label>
            <!-- Custom Themes -->
            <?php
            $user_id = $_SESSION['userid'];
            $stmt = $pdo->prepare('SELECT * FROM user_themes WHERE userid = ?');
            $stmt->execute([$user_id]);
            $user_themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($user_themes as $theme) {
                echo '<label class="theme-option ' . (isset($_SESSION['theme']) && $_SESSION['theme'] == $theme['theme_name'] ? 'selected' : '') . '">
                      <input type="radio" name="theme" value="' . htmlspecialchars($theme['theme_name']) . '" ' . (isset($_SESSION['theme']) && $_SESSION['theme'] == $theme['theme_name'] ? 'checked' : '') . '>
                      <img src="user_themes/' . htmlspecialchars($theme['theme_file']) . '" alt="' . htmlspecialchars($theme['theme_name']) . '">
                      <span>' . htmlspecialchars($theme['theme_name']) . '</span>
                      </label>';
            }
            ?>
        </div>
        <button type="submit" class="submit-button">Save</button>
    </form>
    <h2>Upload Custom Theme</h2>
    <form method="post" action="upload_theme.php" enctype="multipart/form-data">
        <label for="theme_name">Theme Name:</label>
        <input type="text" name="theme_name" id="theme_name" required>
        <label for="theme_file">Theme Image (JPG/PNG):</label>
        <input type="file" name="theme_file" id="theme_file" accept=".jpg,.jpeg,.png" required>
        <button type="submit" class="submit-button">Upload Theme</button>
    </form>
</div>
    