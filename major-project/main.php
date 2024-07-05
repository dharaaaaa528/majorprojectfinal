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
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-image: url('background2.jpg');
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

.content {
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for better readability */
    border-radius: 10px;
    max-width: 550px;
    margin-left: 0px; /* Adjust this value to move the content to the left */
    color: #f2f2f2;
    text-align: center; /* Center the text inside the content */
    position: relative;
}

.content h1 {
    margin-bottom: 20px;
}

.content p {
    margin-bottom: 20px;
}

.button-container {
    display: flex;
    justify-content: flex-start; /* Align the button to the left */
    position: absolute;
    bottom: 150px; /* Position the button 150px from the bottom of the page */
    width: 100%;
    padding-left: 0px; /* Add padding to create some space from the left edge */
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


