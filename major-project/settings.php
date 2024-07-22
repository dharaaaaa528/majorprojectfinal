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
    <!-- Include CSS for current theme -->
    <link rel="stylesheet" href="default.css">
    <?php if (isset($_SESSION['theme']) && $_SESSION['theme'] != 'default'): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($_SESSION['theme']) ?>.css">
    <?php endif; ?>
</head>
<body>
    <?php include 'topnav.php'; // Include top navigation ?>
    
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
</body>
</html>
