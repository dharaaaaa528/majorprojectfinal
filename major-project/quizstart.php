<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Technique 1 Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            padding: 0;
            height: 100vh;
            /* Ensure body background is transparent */
            background-color: rgba(0, 0, 0, 0.9); /* Black with 50% opacity */
        }

        html, body {
            height: 100%;
        }
        .content {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            height: 90vh;
            padding: 20px;
            position: relative;
            color: white;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background for content */
            border-radius: 10px; /* Rounded corners for the content area */
            padding: 20px; /* Padding inside the content area */
        }
        .quiz-details {
            margin: 0;
        }
        .quiz-details h1 {
            margin-bottom: 10px;
        }
        .quiz-details .details {
            margin-bottom: 20px;
        }
        .start-button {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
        .start-button button {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .start-button button:hover {
            background-color: darkgray;
        }
    </style>
</head>
<body>
    <?php include 'topnav.php'; ?>
     
    <div class="content">
        <?php
        if (isset($_GET['technique'])) {
            $technique = htmlspecialchars($_GET['technique']);
            echo "<h1>$technique Quiz</h1>";
        } else {
            echo "<h1>No Technique Selected</h1>";
        }
        ?>
        
        <h3>Please read the below details carefully!</h3>
        <p>1. Duration: 60 Minutes</p>
        <p>2. Marks to pass: 70% (7/10)</p>
        <p>3. There are 10 questions in this quiz. To pass and get a certificate of completion you would be required to get at least 7 of them correct. Try to complete as many questions as you can.</p>
        <p>4. The test would automatically submit at the end of 60 minutes. No changes after that would be registered.</p>
        <p>5. Click on the "Click here to start" button to start the quiz.</p>
        <p>6. Note: The timer would automatically start once you click on the button.</p>
        <h4>All the best!!</h4>
            
        <div class="start-button">
            <button onclick="startQuiz()">Click here to start</button>
        </div>
    </div>

    <script>
        function startQuiz() {
            var technique = "<?php echo $technique; ?>";
            var url = "";

            switch (technique) {
                case "SQL Technique 1":
                    url = "sql1quiz.php";
                    break;
                case "SQL Technique 2":
                    url = "sql2quiz.php";
                    break;
                case "SQL Technique 3":
                    url = "sql3quiz.php";
                    break;
                case "SQL Technique 4":
                    url = "sql4quiz.php";
                    break;
                case "XS Script Technique 1":
                    url = "script1quiz.php";
                    break;
                case "XS Script Technique 2":
                    url = "script2quiz.php";
                    break;
                case "XS Script Technique 3":
                    url = "script3quiz.php";
                    break;
                case "XS Script Technique 4":
                    url = "script4quiz.php";
                    break;
                // Add more cases as needed for other techniques
                default:
                    alert("No valid technique selected" + technique);
                    return;
            }

            window.location.href = url; // Redirect to the appropriate quiz page
        }
    </script>

</body>           
</html>


