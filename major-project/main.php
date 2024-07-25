<?php
ob_start();
require_once 'server.php';
require_once 'topnavmain.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inj3ctPractice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('background.jpg');
            background-size: cover; /* Makes the image cover the entire page */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-position: center; /* Centers the image */
            background-attachment: fixed; /* Fixes the image while scrolling */
            color: white;
            padding: 0;
            height: 100vh;
            position: relative; /* Required for positioning elements inside */
        }

        html, body {
            height: 100%;
        }

        .main-content {
            text-align: center;
            padding: 20px;
            margin-top: 80px; /* Adjust if topnav height is different */
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

        .button-container {
            display: flex;
            justify-content: center; /* Align the button to the center */
            position: absolute;
            bottom: 150px; /* Position the button 150px from the bottom of the page */
            width: 100%;
        }

        .button {
            padding: 10px 20px;
            font-size: 20px;
            color: white;
            background-color: grey;
            text-decoration: none;
            border-radius: 30px;
        }

        .button:hover {
            background-color: darkgrey;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="main-content">
    <h1>Master the Art of Secure Coding</h1>
    <p>"Learn and Practice SQL & JavaScript Injection Techniques in a safe environment"</p>
    <p>Not Sure Where To Begin?</p>	
</div>

<div class="button-container">
    <a href="contentpagemain.php" class="button">START LEARNING NOW</a>
</div>

<footer>
    <p>&copy; 2024 Inj3ctPractice. All rights reserved.</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
</footer>

</body>
</html>
