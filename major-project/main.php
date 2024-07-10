<?php
ob_start();
require_once 'server.php';
require_once 'topnavmain.php';

if (isset($_GET['search'])) {
    $searchQuery = strtolower(trim($_GET['search']));
    
    switch ($searchQuery) {
        case 'sql injection':
            header("Location: contentpagemain.php");
            exit();
        case 'script injection':
            header("Location: contentpage2main.php");
            exit();
        case 'sql':
            header("Location: contentpagemain.php");
            exit();
        case 'script':
            header("Location: contentpage2main.php");
            exit();
        default:
            $searchError = "No results found for '$searchQuery'. Please search for 'SQL Injection' or 'Script Injection'.";
    }
}
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

 .content {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
            color: #f2f2f2;
        }

        .search-bar-container {
            text-align: center;
            margin: 20px auto;
            background-color: transparent;
        }

        .search-bar input[type="text"] {
            width: 40%;
            padding: 10px;
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ccc;
        }

        .search-bar input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 25px;
            border: none;
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .search-bar input[type="submit"]:hover {
            background-color: #555;
        }
        .button-container {
            display: flex;
            justify-content: center; /* Align the button to the left */
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

<div class="search-bar-container">
    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Type your search query here">
            <input type="submit" value="Search">
        </form>

        <?php
        if (isset($searchError)) {
            echo "<p>$searchError</p>";
        }
        ?>
    </div>
</div>

<div class="button-container">
    <a href="contentpagemain.php" class="button">START LEARNING NOW</a>
</div>

</body>
</html>


