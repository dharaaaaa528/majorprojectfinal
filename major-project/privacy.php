<?php
ob_start();
require_once 'server.php';
require_once 'topnavmain.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy - Inj3ctPractice</title>
    <style>
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #333;
             height: 100px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: white; /* Adjusted to match navbar height */
           
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
    <h1>Privacy Policy</h1>
    <p>Last updated: July 24, 2024</p>
    
    <p>Welcome to Inj3ctPractice! This Privacy Policy describes how we handle your personal information when you use our website and services.</p>
    
    <h2>Information We Collect</h2>
    <p>We collect information that you provide to us directly and information that is collected automatically when you visit our website.</p>
    
    <h3>Personal Information</h3>
    <p>When you use our services, you may provide us with personal information such as your name and email address. This information is used to communicate with you and to improve our services.</p>
    
    <h3>Usage Data</h3>
    <p>We collect information about your use of our website, including IP addresses, browser types, and pages visited. This information helps us analyze and improve the performance of our website.</p>
    
    <h2>How We Use Your Information</h2>
    <p>We use the collected information for various purposes, including:</p>
    <ul>
        <li>Providing and maintaining our Service</li>
        <li>Improving our website and services</li>
        <li>Communicating with you, including responding to your inquiries</li>
    </ul>
    
    <h2>Data Security</h2>
    <p>We take reasonable measures to protect your personal information from unauthorized access, use, or disclosure. However, no method of transmission over the internet or method of electronic storage is 100% secure.</p>
    
    <h2>Cookies</h2>
    <p>We use cookies to enhance your experience on our website. Cookies are small files that are stored on your device and help us remember your preferences and provide a more personalized experience.</p>
    
    <h2>Changes to This Privacy Policy</h2>
    <p>We may update our Privacy Policy from time to time. Any changes will be posted on this page with an updated effective date.</p>
    
    <h2>Contact Us</h2>
    <p>If you have any questions about this Privacy Policy or our data practices, please contact us at Support@InjectPractice.com.</p>
</div>

<footer>
    <p>&copy; 2024 Inj3ctPractice. All rights reserved.</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
</footer>

</body>
</html>
