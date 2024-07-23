<?php
require_once 'dbconfig.php';  // Make sure this file contains your database connection setup
require_once 'header.php';    // Include header with theme setting logic

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            height: calc(100vh - 20px); /* Adjust based on top nav height */
            position: absolute;
            top: 99px; /* Adjust based on top nav height */
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
            background-color: rgba(0, 0, 0, 0.5);
            height: 100vh;
        }

        .settings-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        .settings-container h1 {
            margin-top: 0;
        }

        .settings-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .settings-container label, .settings-container select, .settings-container button {
            margin: 10px;
        }
    </style>
    <!-- Include CSS for current theme -->
    <link rel="stylesheet" href="default.css">
    <?php if (isset($_SESSION['theme']) && $_SESSION['theme'] != 'default'): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($_SESSION['theme']) ?>.css">
    <?php endif; ?>
</head>
<body>
    <?php include 'topnav.php'; // Include top navigation ?>
    <div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <a href="#"><u>Progress</u></a>
        <a href="#"><u>Certifications</u></a>
        <a href="settings.php" class="settings-link"><u>Settings</u></a>
    </div>
    <div class="content">
        <div class="settings-container">
            <h1>Settings</h1>
            <form method="post" action="">
                <label for="theme">Select Theme:</label>
                <select name="theme" id="theme">
                    <option value="default" <?= $_SESSION['theme'] == 'default' ? 'selected' : '' ?>>Default</option>
                    <option value="dark" <?= $_SESSION['theme'] == 'dark' ? 'selected' : '' ?>>Dark</option>
                    <option value="light" <?= $_SESSION['theme'] == 'light' ? 'selected' : '' ?>>Light</option>
                </select>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>
</body>
</html>

