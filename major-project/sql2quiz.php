<?php
require_once 'server.php';

$number_of_questions = 10; // Specify the number of questions you want to fetch
$sql = "SELECT * FROM sqlquiz2 ORDER BY RAND() LIMIT $number_of_questions";
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
            color: #fff; /* Ensure text is visible on dark background */
            display: flex;
        }
        .container {
            flex: 1;
            padding: 20px;
            margin-left: 250px; /* Space for the sidebar */
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
            background: grey;
            border-radius: 5px;
            text-align: center;
        }
        .sidebar ul li a.answered {
            background: green;
        }
        .sidebar ul li a:hover {
            background: #0056b3;
        }
        .sidebar ul li a.flashing {
            animation: flash 1s infinite;
        }
        @keyframes flash {
            0% { background-color: grey; }
            50% { background-color: #ff0000; }
            100% { background-color: grey; }
        }
        @keyframes flashRed {
            0% { color: #fff; }
            50% { color: #ff0000; }
            100% { color: #fff; }
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
        #timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.2em;
        }
        #timer.flashing {
            animation: flashRed 1s infinite;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Question Navigation</h2>
        <ul id="questionNavigation">
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                $result->data_seek(0); // Reset the result pointer to the beginning
                while($row = $result->fetch_assoc()) {
                    echo "<li><a href='#question_$i' id='nav_question_$i'>Question $i</a></li>";
                    $i++;
                }
            }
            ?>
        </ul>
    </div>

    <div class="container">
        <h1>SQL INJECTION QUIZ</h1>
        <p id="timer">Time Left: 20:00</p> <!-- Display timer -->
        <form id="quizForm" action="submit_quiz.php" method="post" onsubmit="return validateForm()">
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                $result->data_seek(0); // Reset the result pointer to the beginning
                while($row = $result->fetch_assoc()) {
                    echo "<div class='question' id='question_$i'>";
                    echo "<p>Question $i: " . $row["question_text"] . "</p>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='A' onclick='markAnswered($i)'>" . $row["option_a"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='B' onclick='markAnswered($i)'>" . $row["option_b"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='C' onclick='markAnswered($i)'>" . $row["option_c"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='D' onclick='markAnswered($i)'>" . $row["option_d"] . "</label><br>";
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

    <script>
        // Timer code
        var timer;
        var minutes = 20; // Initial minutes
        var seconds = 0; // Initial seconds

        function startTimer() {
            timer = setInterval(function() {
                if (seconds == 0) {
                    if (minutes == 0) {
                        clearInterval(timer);
                        document.getElementById('quizForm').submit(); // Automatically submit quiz when time is up
                    } else {
                        minutes--;
                        seconds = 59;
                    }
                } else {
                    seconds--;
                }
                if (minutes < 3) {
                    document.getElementById('timer').classList.add('flashing');
                }
                displayTime();
            }, 1000);
        }

        function displayTime() {
            var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
            var formattedSeconds = seconds < 10 ? '0' + seconds : seconds;
            document.getElementById('timer').textContent = 'Time Left: ' + formattedMinutes + ':' + formattedSeconds;
        }

        function validateForm() {
            let unanswered = [];
            let questions = document.querySelectorAll('.question');
            questions.forEach((question, index) => {
                let radios = question.querySelectorAll('input[type="radio"]');
                let answered = Array.from(radios).some(radio => radio.checked);
                if (!answered) {
                    unanswered.push(index + 1);
                    let navItem = document.getElementById('nav_question_' + (index + 1));
                    navItem.classList.add('flashing');
                    setTimeout(() => {
                        navItem.classList.remove('flashing');
                    }, 2000); // Remove flashing after 2 seconds
                }
            });

            if (unanswered.length > 0) {
                return false;
            }
            return true;
        }

        function markAnswered(questionNumber) {
            let navItem = document.getElementById('nav_question_' + questionNumber);
            navItem.classList.add('answered');
        }

        // Start timer when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startTimer();
        });

        // Warn user before leaving page
        window.onbeforeunload = function() {
            return "Are you sure you want to leave? All progress will be lost.";
        };
    </script>
</body>
</html>
<?php
$conn->close();
?>

