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
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
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
        background-color: rgba(0, 0, 0, 0.1);
    }

    .container {
        text-align: center;
        background-color: darkgrey;
        color: black;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        max-width: 80%;
        width: 500px;
    }

    .container h1 {
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .container label {
        font-size: 1em;
        font-weight: bold;
    }

    .container button {
        margin-top: 20px;
        padding: 25px 80px;
        font-size: 1.5em;
        border-radius: 10px;
        border: none;
        background-color: black;
        color: white;
        cursor: pointer;
        width: 100%;
        max-width: 100%;
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
            <h1>SCRIPT INJECTION TEST</h1>
            <label for="technique">Select a level you would like to attempt:</label>
            <br>
            <form action="teststartbasic.php" method="get">
                <button type="submit" name="technique" value="XSS Test Basic">BASIC</button>
            </form>
            <form action="teststartintermediate.php" method="get">
                <button type="submit" name="technique" value="XSS Test Intermediate">INTERMEDIATE</button>
            </form>
            <form action="teststartadvanced.php" method="get">
                <button type="submit" name="technique" value="XSS Test Advanced">ADVANCED</button>
            </form>
            <form action="teststartallinone.php" method="get">
                <button type="submit" name="technique" value="XSS Test AllInOne">ALL IN ONE</button>
            </form>
        </div>
    </div>
</body>
</html>


