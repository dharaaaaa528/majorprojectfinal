<?php
require_once 'server.php';
$number_of_questions = 10;
$sql = "SELECT * FROM xssquiz3 ORDER BY RAND() LIMIT $number_of_questions";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            display: flex;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .container {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .sidebar {
            width: 200px;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
        }
        .sidebar h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
            background: #007bff;
            border-radius: 5px;
            text-align: center;
        }
        .sidebar ul li a:hover {
            background: #0056b3;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .question {
            margin-bottom: 20px;
        }
        .question p {
            font-size: 1.2em;
        }
        .submit-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .timer-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px;
            border-radius: 5px;
            color: white;
            font-size: 1.2em;
            z-index: 1000;
        }
    </style>
    <script>
        // Function to start the timer
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            var interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    document.getElementById("quizForm").submit(); // Auto-submit the form
                }

                if (timer === 15 * 60) {
                    alert("15 minutes left!");
                }
            }, 1000);
        }

        // Function to prevent back navigation
        function preventBack() {
            history.pushState(null, null, window.location.href);
            window.addEventListener('popstate', function () {
                history.pushState(null, null, window.location.href);
            });
        }

        // Start the quiz and timer
        function startQuiz() {
            preventBack();
            var startTime = sessionStorage.getItem("startTime");
            var duration = sessionStorage.getItem("duration");

            if (!startTime || !duration) {
                alert("Timer not found. Redirecting to the start page.");
                window.location.href = "quizstart.php";
                return;
            }

            var elapsed = Math.floor((Date.now() - startTime) / 1000);
            var remainingTime = duration - elapsed;

            if (remainingTime <= 0) {
                alert("Time is up!");
                document.getElementById("quizForm").submit();
                return;
            }

            var display = document.querySelector('#time');
            startTimer(remainingTime, display);
        }

        // Disable back navigation
        window.onload = function () {
            preventBack();
        };
    </script>
</head>
<body onload="startQuiz();">

<div class="sidebar">
    <h2>Questions</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            $i = 1;
            $result->data_seek(0);
            while($row = $result->fetch_assoc()) {
                echo "<li><a href='#question_$i'>Question $i</a></li>";
                $i++;
            }
        }
        ?>
    </ul>
</div>

<div class="container">
    <h1>SQL INJECTION QUIZ</h1>
    <div id="quizContent">
        <form id="quizForm" action="submit_quiz.php" method="post">
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                $result->data_seek(0);
                while($row = $result->fetch_assoc()) {
                    echo "<div class='question' id='question_$i'>";
                    echo "<p>Question $i: " . $row["question_text"] . "</p>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='A'>" . $row["option_a"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='B'>" . $row["option_b"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='C'>" . $row["option_c"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='D'>" . $row["option_d"] . "</label><br>";
                    echo "</div>";
                    $i++;
                }
            } else {
                echo "No questions available";
            }
            ?>
            <input type="submit" value="Submit" class="submit-btn">
        </form>
    </div>
</div>

<div class="timer-box">
    <p>Time left: <span id="time">60:00</span> minutes</p>
</div>

</body>
</html>
<?php
$conn->close();
?>