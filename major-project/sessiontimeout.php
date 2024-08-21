<?php
session_start();

// Set session timeout period (1 hour)
$timeout_duration = 3600; // 1 hour in seconds

// Check if the user is logged in and if a timeout session exists
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $inactive = time() - $_SESSION['LAST_ACTIVITY'];
    if ($inactive >= $timeout_duration) {
        // Session timed out
        session_unset();
        session_destroy();
        header("Location: login.php"); // Redirect to login page
        exit();
    }
}

// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();
?>
<!DOCTYPE html>
<html>
<head>
<script>
// Set the timeout period (1 hour)
const timeoutDuration = 3600000; // 1 hour in milliseconds

let timeout;
const resetTimer = () => {
    clearTimeout(timeout);
    timeout = setTimeout(logoutUser, timeoutDuration);
};

const logoutUser = () => {
    window.location.href = 'login.php'; // Redirect to login page
};

// Events to reset the timer
window.onload = resetTimer;
window.onmousemove = resetTimer;
window.onmousedown = resetTimer; // touchscreen presses
window.ontouchstart = resetTimer; // touchscreen swipes
window.onclick = resetTimer;
window.onkeydown = resetTimer;
window.addEventListener('scroll', resetTimer);

// Start the timer
resetTimer();
</script>
</head>
</html>

