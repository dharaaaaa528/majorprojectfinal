<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inj3ctPractice</title>
    <link rel="stylesheet" href="contentpage.css">
     <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff; /* Ensure text is visible on dark background */
        }
        .container {
            display: flex;
            min-height: 100vh; /* Ensure the container spans the full height of the viewport */
        
            
        }
        .sidebar {
            width: 150px !important;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 490vh; /* Ensure the sidebar spans the full height of the viewport */
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar ul li {
            margin-bottom: 10px;
           
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            letter-spacing: 2px;
            line-height: 1;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            color: #000; /* Black text for better readability on light background */
            background-color: rgba(255, 255, 255, 0.9); /* Light background for content area */
            border-radius: 10px;
            margin: 20px;
            width: 100px;
        }
        .technique {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .technique h2 {
            margin-top: 0;
        }
        .button-group {
            margin-top: 20px;
        }
        .button-group button {
            margin-right: 10px;
            margin-bottom: 10px; /* Add margin bottom for spacing */
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .example {
            background-color: #333;
            padding: 5px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .example pre {
            margin: 0;
            color: #fff; /* Ensure code text is visible on dark background */
        }   
    </style>
</head>
<body>
    <?php include 'topnav.php'; ?>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="#">Profile</a></li>
                <li><a href="contentpage.php">SQL techniques</a></li>
                <li><a href="contentpage2.php">XSScript techniques</a></li>
                
            </ul>
        </div>
        <div class="content">
            <div class="technique" id="technique1">
                <h2>XS Script Technique 1</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="XS Script Technique 1">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="XS Script Technique 1">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique2">
                <h2>XS Script Technique 2</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="XS Script Technique 2">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique3">
                <h2>XS Script Technique 3</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="XS Script Technique 3">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique4">
                <h2>XS Script Technique 4</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="XS Script Technique 4">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
