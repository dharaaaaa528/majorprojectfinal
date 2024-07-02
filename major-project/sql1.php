<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Technique 1 Quiz</title>
    <style>
        body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            height: 90vh;
            padding: 20px;
        }
        .quiz-details {
            margin: 0;
        }
        .quiz-details h1 {
            margin-bottom: 20px;
        }
        .quiz-details .details {
            margin-bottom: 20px;
        }
        .start-button {
            align-self: flex-end;
            margin-right: 20px;
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
        <div class="quiz-details">
            <h1>SQL TECHNIQUE 1 QUIZ</h1>
            <div class="details">
            	<h3>Pleae read the below details carefully!</h3>
                <p>Duration: 60 Minutes</p>
                <p>Marks to pass: 70% (7/10)</p>
                <p> There are 10 questions in this quiz. To pass and get a certificate of completion you would be required to get at least 7 of them correct. Try to complete as many questions as you can.</p>
                <p>The test would automatically submit at the end of 60 minutes. No changes after that would be registered.</p>
                <p>Click on the "Click here to start" button to start the quiz.</p>
                <p>Note: The timer would sutomatically start once you click on the button.</p>
                <p>All the best!!</p>
            </div>
        </div>
        <div class="start-button">
            <button onclick="startQuiz()">Click here to start</button>
        </div>
    </div>

    <script>
        function startQuiz() {
            // Add the link to the quiz page or start quiz functionality here
            window.location.href = 'start_quiz.php'; // Redirect to the quiz page
        }
    </script>
</body>
</html>
