<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inj3ctPractice</title>
    <link rel="stylesheet" href="contentpage.css">
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
            <div class="technique" id="technique1" >
                <h2>SQL Technique 1</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="#" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                    <form action="sql1.php" method="get" style="margin: 0;">
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
                    <form action="sql2.php" method="get" style="margin: 0;">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique3">
                <h2>SQL Technique 3</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="sql_technique1_quiz.html" method="get" style="margin: 0;">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                    <form action="sql_technique1_editor.html" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique4">
                <h2>SQL Technique 4</h2>
                <p>[Description]</p>
                <div class="button-group">
                    <form action="sql_technique1_quiz.html" method="get" style="margin: 0;">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                    <form action="sql_technique1_editor.html" method="get" style="margin: 0;">
                        <button type="submit">Editor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
