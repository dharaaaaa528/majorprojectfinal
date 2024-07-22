<?php
include 'topnav.php';  // Make sure this path is correct
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Test</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: black;
        color: white;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        font-family: Arial, sans-serif;
        background-size: cover; /* Makes the image cover the entire page */
        background-size: cover; /* Makes the image cover the entire page */
        background-repeat: no-repeat; /* Prevents the image from repeating */
        background-position: center; /* Centers the image */
        background-attachment: fixed;  /* Black with 50% opacity *//* Fixes the image while scrolling */       
    
    }

    nav {
        background-color: #333;
        width: 100%;
    }

    .main-content {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        background-color: rgba(0, 0, 0, 0.1); /* Black with 50% opacity */
    }

    .container {
        text-align: center;
        background-color: darkgrey; /* White background */
        color: black; /* Black text */
        padding: 40px; /* Padding around content */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Box shadow for depth */
        max-width: 80%; /* Limit maximum width */
        width: 500px; /* Fixed width */
    }

    .container h1 {
        font-size: 3em; /* Increase font size */
        font-weight: bold;
        margin-bottom: 20px; /* Increase margin */
    }

    .container label {
        font-size: 1.3em; /* Increase font size */
        font-weight: bold;
    }

    .container button {
        margin-top: 20px;
        padding: 25px 80px; /* Adjust padding for larger buttons */
        font-size: 1.5em;
        border-radius: 10px;
        border: none;
        background-color: black;
        color: white;
        cursor: pointer;
        width: 100%; /* Make button full-width */
        max-width: 100%; /* Limit maximum width */
    }

    .container button:hover {
        background-color: grey;
    }
        
    </style>
</head>
<body>
    <nav>
        <?php include 'header.php'; ?>
    </nav>
    <div class="main-content">
        <div class="container">
            <h1>TEST</h1>
            <form action="sqltest.php" method="post">
                <label for="test">Select a test you would like to take:</label>
                <br>
                <button type="submit" name="test" value="SQL Injection">SQL Injection</button>
             </form>
             <form action="scripttest.php" method="post">
                <button type="submit" name="test" value="Script Techniques">Script Techniques</button>
            </form>
        </div>
    </div>
</body>
</html>

