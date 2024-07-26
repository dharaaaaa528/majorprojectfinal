<?php
include 'topnav.php';  // Make sure this path is correct
?>
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
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            padding: 0;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.9);
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
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
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
    <?php include 'header.php'; ?>
     
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
        <p>1. Duration: 20 Minutes</p>
        <p>2. Marks to pass: 70% (7/10)</p>
        <p>3. There are 10 questions in this quiz. To pass and get a certificate of completion you would be required to get at least 7 of them correct. Try to complete as many questions as you can.</p>
        <p>4. The test would automatically submit at the end of 20 minutes. No changes after that would be registered.</p>
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
                default:
                    alert("No valid technique selected" + technique);
                    return;
            }

            // Set timer in sessionStorage and redirect
            sessionStorage.setItem("startTime", Date.now());
            sessionStorage.setItem("duration", 20 * 60); // 20 minutes in seconds
            window.location.href = url;
        }

        // Prevent forward navigation
        window.addEventListener('popstate', function(event) {
            if (sessionStorage.getItem('quiz_started') === 'true') {
                history.pushState(null, null, location.href);
            }
        });

        // Set quiz_started flag when quiz starts
        document.querySelector('.start-button button').addEventListener('click', function() {
            sessionStorage.setItem('quiz_started', 'true');
        });

        // Prevent back navigation
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };
    </script>
</body>           
</html>


