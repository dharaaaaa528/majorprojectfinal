<?php
require_once 'server.php';


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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link rel="stylesheet" href="default.css"> <!-- Default CSS -->
    <?php
    $theme = isset($_SESSION['theme']) ? htmlspecialchars($_SESSION['theme']) : 'default';
    if ($theme != 'default'): ?>
        <link rel="stylesheet" href="<?= $theme ?>.css"> <!-- Theme CSS -->
    <?php endif; ?>
</head>
<body class="<?= htmlspecialchars($theme) ?>">
    <!-- Page content -->
</body>
</html>








