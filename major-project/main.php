<?php
require_once 'server.php';
require_once 'topnavmain.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inj3ctPractice</title>
    <style>
        /* Basic styling for the navigation */
        
        .content {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for better readability */
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
            color: #f2f2f2;
            text-align: center; /* Center the text inside the content */
        }
        
        .content h1 {
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center; /* Center the button horizontally */
            position: absolute;
            bottom: 150px; /* Position the button 20px from the bottom of the page */
            width: 100%;
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
    </style>
</head>
<body>

<div class="content">
    <h1>Welcome to Our Website</h1>
    <p>This is a sample text content to show how you can add text to your webpage. You can include paragraphs, headings, lists, images, and more to enhance the content of your site. This text block is styled with a semi-transparent background and rounded corners for better readability against the background image.</p>
    <p>Feel free to customize the styling and content to fit your needs.</p>
</div>

<div class="button-container">
    <a href="contentpagemain.php" class="button">START LEARNING NOW</a>
</div>

</body>
</html>


