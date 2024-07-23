<?php
include 'header.php';  // Make sure this path is correct
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Testing Editor</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        color: #000;
    }

    .container {
        width: 100%;
        max-width: 600px;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        color:black;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
    }

    input[type="submit"] {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .output {
        margin-top: 20px;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 4px;
        background-color: #f9f9f9;
    }

    .back-button {
        margin-top: 20px;
        text-align: center;
    }

    .back-button a {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
    }

    .back-button a:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>XSS Testing Editor</h1>
        <form action="xss.php" method="POST">
            <label for="xss_input">Enter your XSS script!</label><br>
            <textarea id="xss_input" name="xss_input" rows="10" cols="50"></textarea><br>
            <input type="submit" value="GO">
        </form>

        <?php
        if (isset($_POST['xss_input'])) {
            $xss_input = $_POST['xss_input'];
            $sanitized_input = htmlspecialchars($xss_input, ENT_QUOTES, 'UTF-8');
            echo "<h2>XSS Test Output</h2>";
            echo "<div class='output'>$sanitized_input</div>";
        } else {
            echo "<p>No input received.</p>";
        }
        ?>
        
        <div class="back-button">
            <a href="contentpage2.php">Go Back to Content</a>
        </div>
    </div>
</body>
</html>
