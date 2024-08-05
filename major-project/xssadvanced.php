<?php
require_once 'config.php';
require_once 'header.php';

unset($_SESSION['quiz_submitted']);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userid'];
$isGoogleLoggedIn = isset($_SESSION['google_loggedin']) && $_SESSION['google_loggedin'] == 1;

$_SESSION['quiz_submitted'] = false;

// Set test ID to 2
$testId = 7;

if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time();
}

// Store the test ID in the session
$_SESSION['test_id'] = $testId;

$questions = []; // Initialize $questions as an empty array

// Fetch 50 random question IDs first
$questionIds = [];
$sql = "SELECT question_id FROM test_questions WHERE test_id = ? ORDER BY RAND() LIMIT 50";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $testId);
    $stmt->execute();
    $stmt->bind_result($questionId);
    while ($stmt->fetch()) {
        $questionIds[] = $questionId;
    }
    $stmt->close();
} else {
    echo "Error fetching question IDs: " . $conn->error;
    exit();
}

// If no question IDs are fetched, exit
if (empty($questionIds)) {
    echo "No questions found for the selected test.";
    exit();
}

// Fetch test questions based on the fetched question IDs
$sql = "SELECT question_id, question_text
        FROM test_questions
        WHERE question_id IN (" . implode(',', array_fill(0, count($questionIds), '?')) . ")";
if ($stmt = $conn->prepare($sql)) {
    $types = str_repeat('i', count($questionIds));
    $stmt->bind_param($types, ...$questionIds);
    $stmt->execute();
    $stmt->bind_result($questionId, $questionText);
    
    while ($stmt->fetch()) {
        if (!isset($questions[$questionId])) {
            $questions[$questionId] = [
                'id' => $questionId,
                'text' => $questionText
            ];
        }
    }
    $stmt->close();
} else {
    echo "Error fetching test questions: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XSS Advanced Test</title>
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

        html {
            scroll-behavior: smooth;
        }

        html, body {
            height: 100%;
        }

        .container {
            flex: 1;
            padding: 20px;
            margin-left: 250px; /* Space for the sidebar */
            background-color: rgba(0, 0, 0, 0.5);
            width: 1229px;
        }

        .quiz-content {
            padding: 20px;
            border-radius: 8px;
        }

        .question-text {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .options {
            margin-top: 10px;
        }

        .option {
            margin-bottom: 10px;
        }

        .options input {
        margin-right: 10px;
        width: 100%; /* Adjust this value to set the desired width */
        padding: 10px; /* Add padding for better appearance */
        box-sizing: border-box; /* Ensure padding and border are included in the total width */
    }
        

        .submit-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .timer {
            font-size: 20px;
            font-weight: bold;
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            z-index: 1000;
        }

        .timer.red {
            background-color: red;
            animation: flash 2s infinite;
        }

        @keyframes flash {
            0%, 100% {
                background-color: red;
                color: white;
            }
            50% {
                background-color: white;
                color: red;
            }
        }

        /* Side Navigation Styles */
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
            background: grey; /* Default background color */
            border-radius: 5px;
            text-align: center;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background: #0056b3; /* Change color on hover */
        }

        .sidebar ul li a.answered {
            background-color: green; /* Background color for answered questions */
        }

        .sidebar ul li a.blink {
            animation: blink-red 1s; /* Ensure the blinking is visible */
        }

        @keyframes blink-red {
            0%, 100% {
                background-color: grey;
                color: #fff;
            }
            50% {
                background-color: red;
                color: #fff;
            }
        }

        .question-container {
            scroll-margin-top: 100px; /* Adjust based on your header height */
            margin-bottom: 30px; /* Increased space between questions */
        }
    </style>
</head>
<body>
    <!-- Side Navigation Panel -->
    <div class="sidebar">
        <h2>Questions</h2>
        <ul>
            <?php 
            $index = 1;
            foreach ($questions as $questionId => $question): ?>
                <li><a href="#question_<?php echo $index; ?>" data-question-id="<?php echo $index; ?>">Question <?php echo $index; ?></a></li>
            <?php 
            $index++;
            endforeach; ?>
        </ul>
    </div>

    <!-- Main Quiz Container -->
    <div class="main-content">
        <div class="container">
            <h1>SQL Intermediate Test</h1>
            <div class="timer" id="timer">Time Left: 60:00</div>
            <div class="quiz-content">
                <form id="quizForm" action="submit_test.php" method="POST">
                    <input type="hidden" name="test_id" value="<?php echo $testId; ?>">
                    <?php 
                    $index = 1;
                    foreach ($questions as $questionId => $question): ?>
                        <div id="question_<?php echo $index; ?>" class="question-container">
                            <div class="question-text">
                                <?php echo $index . '. ' . htmlspecialchars($question['text']); ?>
                            </div>
                            <div class="options">
                                <input type="text" name="question_<?php echo $questionId; ?>" placeholder="Enter your answer here" data-question-id="<?php echo $index; ?>">
                            </div>
                        </div>
                    <?php 
                    $index++;
                    endforeach; ?>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var timerElement = document.getElementById('timer');
            var endTime = new Date().getTime() + 60 * 60 * 1000; // 1 hour from now

            function updateTimer() {
                var now = new Date().getTime();
                var timeLeft = endTime - now;

                if (timeLeft <= 0) {
                    timerElement.textContent = "Time's up!";
                    return;
                }

                var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                timerElement.textContent = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (timeLeft <= 5 * 60 * 1000) {
                    timerElement.classList.add('red');
                }

                setTimeout(updateTimer, 1000);
            }

            updateTimer();

            // Smooth scrolling for sidebar links
            var sidebarLinks = document.querySelectorAll('.sidebar a');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    var targetId = this.getAttribute('href').substring(1);
                    var targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70, // Adjust scroll offset as needed
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Update sidebar link color based on input
            var inputs = document.querySelectorAll('input[type="text"]');
            inputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    var questionId = this.getAttribute('data-question-id');
                    var link = document.querySelector('.sidebar a[data-question-id="' + questionId + '"]');
                    if (link) {
                        link.classList.add('answered');
                    }
                });
            });

            // Validate all questions are answered before submitting
            document.getElementById('quizForm').addEventListener('submit', function(event) {
    var unansweredQuestions = [];
    var inputs = document.querySelectorAll('input[type="text"]');

    inputs.forEach(function(input) {
        if (input.value.trim() === "") {
            unansweredQuestions.push(input.getAttribute('data-question-id'));
        }
    });

    // Reset blinking class for all links
    sidebarLinks.forEach(function(link) {
        link.classList.remove('blink');
        // Trigger reflow to restart animation
        void link.offsetWidth;
    });

    if (unansweredQuestions.length > 0) {
        event.preventDefault(); // Prevent form submission
        unansweredQuestions.forEach(function(questionId) {
            var link = document.querySelector('.sidebar a[data-question-id="' + questionId + '"]');
            if (link) {
                link.classList.add('blink');
            }
        });
    }
});

        });
    </script>
</body>
</html>