<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 7200); // 7200 seconds = 2 hours
    session_set_cookie_params(7200);
    session_start();
}
?>

<!-- session_timeout.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
    <script>
        let inactivityTime = function () {
            let time;
            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;

            function logoutUser() {
                window.location.href = 'logout.php';
            }

            function resetTimer() {
                clearTimeout(time);
                time = setTimeout(() => {
                    let logout = confirm("You have been inactive for 1 hour. Do you want to stay logged in?");
                    if (logout) {
                        // Set a new timer for 2 hours after user confirms to stay logged in
                        clearTimeout(time);
                        time = setTimeout(logoutUser, 7200000); // 7200000 ms = 2 hours
                    } else {
                        logoutUser();
                    }
                }, 3600000);  // 3600000 ms = 1 hour
            }
        };

        inactivityTime();
    </script>
</body>
</html>
