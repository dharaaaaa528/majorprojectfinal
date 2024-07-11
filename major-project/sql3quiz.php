<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "majorproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$number_of_questions = 10; // Specify the number of questions you want to fetch
$sql = "SELECT * FROM sqlquiz3 ORDER BY RAND() LIMIT $number_of_questions";
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            text-align: center;
            color: black;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Question Navigation</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                $result->data_seek(0); // Reset the result pointer to the beginning
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
        <p id="timer">Time Left: 60:00</p> <!-- Display timer -->
        <form id="quizForm" action="submit_quiz.php" method="post" onsubmit="return validateForm()">
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                $result->data_seek(0); // Reset the result pointer to the beginning
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

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Please answer all questions before submitting the quiz.</p>
            <p id="unansweredQuestions"></p>
        </div>
    </div>

    <script>
        // Timer code
        var timer;
        var minutes = 60; // Initial minutes
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
                    unanswered.push(`<a href="#question_${index + 1}">Question ${index + 1}</a>`);
                }
            });

            if (unanswered.length > 0) {
                document.getElementById('unansweredQuestions').innerHTML = 'Unanswered questions: ' + unanswered.join(', ');
                document.getElementById('myModal').style.display = 'block';
                return false;
            }
            return true;
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        // Close the modal when the user clicks anywhere outside of it
        window.onclick = function(event) {
            let modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Start timer when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startTimer();
        });

        // Warn user before leaving page
        window.onbeforeunload = function() {
            return "Are you sure you want to leave? All progress will be lost.";
        };

        // Remove the quiz page from the history when the user leaves
        window.addEventListener('beforeunload', function() {
            window.history.pushState(null, null, location.href);
            window.history.back();
            window.history.forward();
            window.onbeforeunload = null;
        });

    </script>

</body>
</html>
<?php
$conn->close();
?>

