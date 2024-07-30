<?php
ob_start();
require_once 'server.php';
require_once 'topnavmain.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terms of Service - Inj3ctPractice</title>
    <style>
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #333;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: white;
            height: 100vh;
        }

        html, body {
            height: 100%;
        }

        .content {
            max-width: 800px;
            margin: auto;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        p, ul {
            line-height: 1.6;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 5px 0;
        }

        footer a {
            color: #f2f2f2;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="content">
    <h1>Terms of Service</h1>
    <p>Last updated: July 24, 2024</p>
    
    <p>Welcome to Inj3ctPractice!</p>
    
    <p>These terms and conditions outline the rules and regulations for the use of Inj3ctPractice's Website, located at [your website URL].</p>
    
    <h2>By accessing this website we assume you accept these terms and conditions. Do not continue to use Inj3ctPractice if you do not agree to take all of the terms and conditions stated on this page.</h2>
    
    <h3>Cookies</h3>
    <p>We employ the use of cookies. By accessing Inj3ctPractice, you agreed to use cookies in agreement with the Inj3ctPractice's Privacy Policy.</p>
    
    <h3>License</h3>
    <p>Unless otherwise stated, Inj3ctPractice and/or its licensors own the intellectual property rights for all material on Inj3ctPractice. All intellectual property rights are reserved. You may access this from Inj3ctPractice for your own personal use subjected to restrictions set in these terms and conditions.</p>
    
    <h3>You must not:</h3>
    <ul>
        <li>Republish material from Inj3ctPractice</li>
        <li>Sell, rent, or sub-license material from Inj3ctPractice</li>
        <li>Reproduce, duplicate, or copy material from Inj3ctPractice</li>
        <li>Redistribute content from Inj3ctPractice</li>
    </ul>
    
    <h3>Content Liability</h3>
    <p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website.</p>
    
    <h3>Your Privacy</h3>
    <p>Please read our Privacy Policy.</p>
    
    <h3>Hyperlinking to our Content</h3>
    <p>The following organizations may link to our Website without prior written approval:</p>
    <ul>
        <li>Government agencies</li>
        <li>Search engines</li>
        <li>News organizations</li>
        <li>Online directory distributors</li>
        <li>System wide Accredited Businesses</li>
    </ul>
    
    <h3>iFrames</h3>
    <p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>
    
    <h3>Reservation of Rights</h3>
    <p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request.</p>
    
    <h3>Disclaimer</h3>
    <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website.</p>
</div>

<footer>
    <p>&copy; 2024 Inj3ctPractice. All rights reserved.</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
</footer>

</body>
</html>
