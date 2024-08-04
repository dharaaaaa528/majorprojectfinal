<?php
require_once 'dbconfig.php'; // Include your database configuration file
require_once 'topnav.php'; // Include your top navigation bar
require_once 'sessiontimeout.php';
require_once 'header.php';
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION["login"]) && !isset($_SESSION["google_loggedin"])) {
    header("Location: login.php");
    exit();
}

// Fetch user information
if (isset($_SESSION["google_loggedin"]) && $_SESSION["google_loggedin"] === TRUE) {
    // Fetch Google user information from userinfo table
    $stmt = $pdo->prepare('SELECT * FROM userinfo WHERE userid = ?');
    $stmt->execute([$_SESSION['userid']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user['username'];
} else {
    // Regular login user information
    $username = $_SESSION["username"];
}

// Check if the modal has been shown in this session
$modalShown = isset($_SESSION['modal_shown']) && $_SESSION['modal_shown'];

// If the modal hasn't been shown yet, mark it as shown
if (!$modalShown) {
    $_SESSION['modal_shown'] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Main</title>
    <style>
       

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .modal-content h1 {
            color: black;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .content {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
            color: #f2f2f2;
        }

        .button-container {
            display: flex;
            justify-content: center;
            position: absolute;
            bottom: 150px;
            width: 100%;
            padding-left: 0px;
        }

        .button {
            padding: 10px 20px;
            font-size: 50px;
            color: white;
            background-color: grey;
            text-decoration: none;
            border-radius: 30px;
        }

        .button:hover {
            background-color: darkgrey;
        }
        .main-content {
            text-align: center;
            padding: 20px;
            margin-top: 80px; /* Adjust if topnav height is different */
           background-color: rgba(0, 0, 0, 0.4);
        }

        .main-content h1 {
            font-size: 50px;
            margin-bottom: 20px;
             
        }

        .main-content p {
            font-size: 20px;
            margin-bottom: 40px;
        }

        .main-content img {
            max-width: 100%;
            height: auto;
        }
        
    </style>
</head>
<body>

<?php if (!$modalShown): ?>
    <!-- Modal HTML -->
    <div id="welcomeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
            <!-- Additional modal content can be added here -->
        </div>
    </div>
<?php endif; ?>

<!-- JavaScript for modal behavior -->
<script>
    // JavaScript to show and hide modal
    var modal = document.getElementById("welcomeModal");
    var span = document.getElementsByClassName("close")[0];

    window.onload = function() {
        <?php if (!$modalShown): ?>
            modal.style.display = "block";
        <?php endif; ?>
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<!-- Content area -->
<div class="main-content">
    <h1>Master the Art of Secure Coding</h1>
    <p>"Learn and Practice SQL & JavaScript Injection Techniques in a safe environment"</p>
    <p>Not Sure Where To Begin?</p>	
</div>

<!-- Button container -->
<div class="button-container">
    <a href="contentpage.php" class="button">START LEARNING NOW</a>
</div>

</body>
</html>
