<?php
require_once 'dbconfig.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];

    // Fetch the current theme
    $stmt = $pdo->prepare('SELECT theme FROM userinfo WHERE userid = ?');
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    $current_theme = $user_info ? $user_info['theme'] : 'default';

    // Store the theme in the session
    $_SESSION['theme'] = $current_theme;
} else {
    // Default theme if not logged in
    $_SESSION['theme'] = 'default';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Website</title>
    <link rel="stylesheet" href="default.css">
    <?php if (isset($_SESSION['theme']) && $_SESSION['theme'] != 'default'): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($_SESSION['theme']) ?>.css">
    <?php endif; ?>
</head>
<body class="<?= htmlspecialchars($_SESSION['theme'] ?? 'default') ?>">


