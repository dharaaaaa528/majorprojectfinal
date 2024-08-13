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
        /* Existing CSS */
        
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
            margin-top: 80px;
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

        /* Styles for the video modal */
         .video-modal-content {
            width: 60%;
            height: auto;
            padding: 0;
            position: relative;
            top: 200px; /* Start from the middle */
            margin-left:300px;
            
            transform: translateY(-30%); /* Move slightly down */
        }

        .video-modal-content video {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Style for the video link */
        .video-link {
            text-align: center;
            position: absolute;
            bottom: 10px;
            width: 100%;
            color: #ffffff;
        }

        .video-link a {
            color: #6200ea;
            text-decoration: underline;
            cursor: pointer;
        }
        .video-button-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.video-button {
    padding: 12px 24px; /* Increased padding for a better feel */
    font-size: 18px; /* Adjusted font size */
    color: black;
    background-color: white; /* Green background color */
    border: none;
    border-radius: 8px; /* Slightly rounded corners */
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition effects */
    margin-top:200px;
}

.video-button:hover {
    background-color: grey; /* Darker green on hover */
    transform: scale(1.05); /* Slight scaling on hover */
}

.video-button:active {
    background-color: dark grey; /* Even darker green when active (clicked) */
    transform: scale(1); /* Return to original size */
}

.video-button:focus {
    outline: none; /* Remove default focus outline */
    box-shadow: 0 0 0 3px rgba(72, 207, 173, 0.5); /* Custom focus shadow */
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

<!-- Main Content area -->
<div class="main-content">
    <h1>Master the Art of Secure Coding</h1>
    <p>"Learn and Practice SQL & JavaScript Injection Techniques in a safe environment"</p>
    <p>Not Sure Where To Begin?</p>    
</div>

<!-- Button container -->
<div class="button-container">
    <a href="contentpage.php" class="button">START LEARNING NOW</a>
</div>

<!-- Welcome Modal -->
<div id="welcomeModal" class="modal">
    <div class="modal-content">
        <span class="close welcome-close">&times;</span>
        <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
        <!-- Additional modal content can be added here -->
    </div>
</div>

<!-- Video Modal -->
<div id="videoModal" class="modal">
    <div class="video-modal-content">
        <span class="close video-close">&times;</span>
        <video controls>
            <source src="your-video-file.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>

<!-- Video link -->
<div class="video-button-container">
    <button id="openVideoModal" class="video-button">Watch Introductory Video</button>
</div>

<script>
    // Welcome modal close behavior
    var welcomeModal = document.getElementById("welcomeModal");
    var welcomeClose = document.getElementsByClassName("welcome-close")[0];

    window.onload = function() {
        <?php if (!$modalShown): ?>
            welcomeModal.style.display = "block";
        <?php endif; ?>
    }

    welcomeClose.onclick = function() {
        welcomeModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == welcomeModal) {
            welcomeModal.style.display = "none";
        }
    }

    // Video modal behavior
    var videoModal = document.getElementById("videoModal");
    var openVideoModal = document.getElementById("openVideoModal");
    var videoClose = document.getElementsByClassName("video-close")[0];

    openVideoModal.onclick = function() {
        videoModal.style.display = "block";
    }

    videoClose.onclick = function() {
        videoModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == videoModal) {
            videoModal.style.display = "none";
        }
    }
</script>
