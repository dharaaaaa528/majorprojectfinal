<?php
include 'header.php';  // Make sure this path is correct
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal XSS Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: black;
            padding: 0;
        }

        html, body {
            height: 100%;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 600px;
            z-index: 1; /* Ensure the container is above the background image */
            color: black;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center elements inside the form */
        }
        .container textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: black;
        }
        .container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #000000;
            border: 1px solid #000000;
            border-radius: 5px;
            color: white; /* Changed font color to white */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            color: white; /* Ensure table text color is also white */
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .back-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        
        .box {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            background-color: #f9f9f9;
            margin-top: 10px;
        }

        .non-sanitized-box {
            border-color: #f44336;
            background-color: #ffebee;
        }

        .sanitized-box {
            border-color: #4CAF50;
            background-color: #e8f5e9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stored XSS Demo</h1>
        <p>Try entering a script and a non-script content and look at the different outputs.</p>
        <form method="post">
            <p><strong>Script: &lt;script&gt;alert('Stored XSS!');&lt;/script&gt;<br>Non-script: test</strong></p>
            <textarea id="userInput" name="userInput" rows="10" cols="50" placeholder="Enter your script here"></textarea><br>
            <button type="submit">Submit</button>
        </form>
        <?php
        // Function to escape output safely
        function escapeOutput($input) {
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the user input
            $userInput = isset($_POST['userInput']) ? $_POST['userInput'] : '';

            // Display non-sanitized output
            echo "<h2>Non-Sanitized Output:</h2>";
            echo "<div class='box non-sanitized-box'><p>" . escapeOutput($userInput) . "</p></div>";

            // Display sanitized output
            echo "<h2>Sanitized Output:</h2>";
            // Extract and display only the script content
            if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $userInput, $matches)) {
                $scriptContent = escapeOutput($matches[1]);
                echo "<div class='box sanitized-box'><p>" . $scriptContent . "</p></div>";
            } else {
                echo "<div class='box sanitized-box'><p>No script content found.</p></div>";
            }
        } else {
            echo "<p>No input received.</p>";
        }
        ?>
    </div>
    <div class="back-button">
        <a href="contentpage2.php">Go Back to Content</a>
    </div>
</body>
</html>
