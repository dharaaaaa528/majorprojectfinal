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

// Explicitly set test ID to 1
$testId = 5;

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

// Fetch test questions and options based on the fetched question IDs
$sql = "SELECT tq.question_id, tq.question_text, topt.option1, topt.option2, topt.option3, topt.option4, topt.correct_option
        FROM test_questions tq
        INNER JOIN test_options topt ON tq.question_id = topt.question_id
        WHERE tq.question_id IN (" . implode(',', array_fill(0, count($questionIds), '?')) . ")";
if ($stmt = $conn->prepare($sql)) {
    $types = str_repeat('i', count($questionIds));
    $stmt->bind_param($types, ...$questionIds);
    $stmt->execute();
    $stmt->bind_result($questionId, $questionText, $option1, $option2, $option3, $option4, $correctOption);
    
    while ($stmt->fetch()) {
        if (!isset($questions[$questionId])) {
            $questions[$questionId] = [
                'id' => $questionId,
                'text' => $questionText,
                'options' => [
                    1 => $option1,
                    2 => $option2,
                    3 => $option3,
                    4 => $option4
                ],
                'correct_option' => $correctOption
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
    <title>SQL Technique 1 Test</title>
    <style>
        body {
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

        .option input {
            margin-right: 10px;
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
            animation: blink-red 3s; /* Ensure the blinking is 3 seconds */
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
            <h1>SQL Basic Test</h1>
            <div class="timer" id="timer">Time Left: 60:00</div>
            <div class="quiz-content">
                <form id="quizForm" action="submit_test1.php" method="POST">
                    <input type="hidden" name="test_id" value="<?php echo $testId; ?>">
                    <?php 
                    $index = 1;
                    foreach ($questions as $questionId => $question): ?>
                        <div id="question_<?php echo $index; ?>" class="question-container">
                            <div class="question-text">
                                <?php echo $index . '. ' . htmlspecialchars($question['text']); ?>
                            </div>
                            <div class="options">
                                <?php foreach ($question['options'] as $optionIndex => $option): ?>
                                    <div class="option">
                                        <label>
                                            <input type="radio" name="question_<?php echo $questionId; ?>" value="<?php echo $optionIndex; ?>" class="answer-option" data-question-id="question_<?php echo $index; ?>">
                                            <?php echo htmlspecialchars($option); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php 
                    $index++;
                    endforeach; ?>
                    <button type="submit" class="submit-btn">Submit Test</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    // Timer logic
    var totalSeconds = 60 * 60; // 60 minutes
    var timerElement = document.getElementById("timer");
    var interval = setInterval(function() {
        totalSeconds--;
        var minutes = Math.floor(totalSeconds / 60);
        var seconds = totalSeconds % 60;
        timerElement.textContent = "Time Left: " + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;

        if (totalSeconds <= 0) {
            clearInterval(interval);
            document.getElementById("quizForm").submit();
        }

        if (totalSeconds <= 5 * 60) {
            timerElement.classList.add("red");
        }
    }, 1000);

    // Side navigation link highlight on answer
    var answerOptions = document.querySelectorAll('.answer-option');
    answerOptions.forEach(function(option) {
        option.addEventListener('change', function() {
            var questionId = this.getAttribute('data-question-id');
            var navLink = document.querySelector('.sidebar ul li a[href="#' + questionId + '"]');
            if (navLink) {
                navLink.classList.add('answered');
            }
        });
    });

    // Handle form submission
    document.getElementById('quizForm').addEventListener('submit', function(e) {
        var unansweredQuestions = [];
        var questionContainers = document.querySelectorAll('.question-container');
        questionContainers.forEach(function(container) {
            var questionId = container.id;
            var isAnswered = container.querySelector('input[type="radio"]:checked');
            var navLink = document.querySelector('.sidebar ul li a[href="#' + questionId + '"]');
            if (!isAnswered) {
                unansweredQuestions.push(questionId);
            }
        });

        if (unansweredQuestions.length > 0) {
            e.preventDefault(); // Prevent form submission

            unansweredQuestions.forEach(function(questionId) {
                var navLink = document.querySelector('.sidebar ul li a[href="#' + questionId + '"]');
                if (navLink) {
                    // Trigger blink animation
                    navLink.classList.remove('blink');
                    void navLink.offsetWidth; // Trigger reflow to restart animation
                    navLink.classList.add('blink');
                }
            });
        }
    });
});

    </script>
</body>
</html>
