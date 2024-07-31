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

// Set test ID to 3 (assuming 3 is for SQL Advanced Test)
$testId = 3;

if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time();
}

// Store the test ID in the session
$_SESSION['test_id'] = $testId;

$questions = []; // Initialize $questions as an empty array

// Fetch 30 random question IDs (20 open-ended + 10 "try it now")
$questionIds = [];
$sql = "SELECT question_id FROM test_questions WHERE test_id = ? ORDER BY RAND() LIMIT 30";
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
$sql = "SELECT question_id, question_text, question_type
        FROM test_questions
        WHERE question_id IN (" . implode(',', array_fill(0, count($questionIds), '?')) . ")";
if ($stmt = $conn->prepare($sql)) {
    $types = str_repeat('i', count($questionIds));
    $stmt->bind_param($types, ...$questionIds);
    $stmt->execute();
    $stmt->bind_result($questionId, $questionText, $questionType);
    
    while ($stmt->fetch()) {
        if (!isset($questions[$questionId])) {
            $questions[$questionId] = [
                'id' => $questionId,
                'text' => $questionText,
                'type' => $questionType
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
    <title>SQL Advanced Test</title>
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

        .editor-container {
            margin-top: 10px;
            margin-bottom: 20px;
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
        }

        .editor-container textarea {
            width: 100%;
            height: 100px;
            background-color: #333;
            color: #fff;
            border: none;
            resize: none;
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
            <h1>SQL Advanced Test</h1>
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
                            <?php if ($question['type'] == 'open-ended'): ?>
                                <div class="options">
                                    <input type="text" name="question_<?php echo $questionId; ?>" placeholder="Enter your answer here" data-question-id="<?php echo $index; ?>">
                                </div>
                            <?php elseif ($question['type'] == 'try-it-now'): ?>
                                <div class="editor-container">
                                    <textarea name="question_<?php echo $questionId; ?>" placeholder="Write your SQL code here" data-question-id="<?php echo $index; ?>"></textarea>
                                </div>
                            <?php endif; ?>
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
            const timerElement = document.getElementById('timer');
            let totalTime = 60 * 60; // 60 minutes in seconds
            const endTime = Date.now() + totalTime * 1000;

            function updateTimer() {
                const now = Date.now();
                const timeLeft = Math.max(0, Math.floor((endTime - now) / 1000));

                const minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
                const seconds = String(timeLeft % 60).padStart(2, '0');

                timerElement.textContent = `Time Left: ${minutes}:${seconds}`;

                if (timeLeft <= 300) { // Less than 5 minutes left
                    timerElement.classList.add('red');
                }

                if (timeLeft > 0) {
                    requestAnimationFrame(updateTimer);
                } else {
                    alert('Time is up! Submitting your answers.');
                    document.getElementById('quizForm').submit();
                }
            }

            requestAnimationFrame(updateTimer);

            // Side Navigation Highlighting and Blinking
            const questionLinks = document.querySelectorAll('.sidebar ul li a');
            const answerInputs = document.querySelectorAll('input[type="text"], textarea');

            questionLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(link.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
                });
            });

            answerInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const questionId = input.getAttribute('data-question-id');
                    const link = document.querySelector(`.sidebar ul li a[data-question-id="${questionId}"]`);

                    if (input.value.trim() !== '') {
                        link.classList.add('answered');
                        link.classList.remove('blink');
                    } else {
                        link.classList.remove('answered');
                        link.classList.add('blink');
                    }
                });
            });

            document.getElementById('quizForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const unansweredQuestions = Array.from(answerInputs).filter(input => input.value.trim() === '');
                if (unansweredQuestions.length > 0) {
                    unansweredQuestions.forEach(input => {
                        const questionId = input.getAttribute('data-question-id');
                        const link = document.querySelector(`.sidebar ul li a[data-question-id="${questionId}"]`);
                        link.classList.add('blink');
                    });
                    alert('You have unanswered questions.');
                } else {
                    this.submit();
                }
            });
        });
    </script>
</body>
</html>
