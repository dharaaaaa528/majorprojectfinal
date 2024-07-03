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
        }
    </style>
</head>
<body>
    <?php include 'topnavmain.php'; ?>
    <div class="container">
    	<div class="sidebar">
            <ul>
                <li><a href="#">Profile</a></li>
                <li><a href="contentpagemain.php">SQL techniques</a></li>
                <li><a href="contentpage2main.php">XSScript techniques</a></li>
                
            </ul>
     	</div>       
        <div class="content">
            <div class="technique" id="technique1" >
                <h2>SQL Technique 1</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="SQL Technique 1">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
                
            </div>
            <div class="technique" id="technique2">
                <h2>SQL Technique 2</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="SQL Technique 2">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique3">
                <h2>SQL Technique 3</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="SQL Technique 3">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique4">
                <h2>SQL Technique 4</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                    	<input type="hidden" name="technique" value="SQL Technique 4">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
