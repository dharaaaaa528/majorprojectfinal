<?php
require_once 'config.php';
require_once 'header.php';

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

// Check if quiz ID is passed via GET or SESSION
$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : (isset($_SESSION['quiz_id']) ? intval($_SESSION['quiz_id']) : 1);

if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time();
}

// Store the quiz ID in the session
$_SESSION['quiz_id'] = $quizId;

$questions = []; // Initialize $questions as an empty array

// Fetch quiz questions with options, limit to 10 random questions
$sql = "SELECT id, question, option1, option2, option3, option4 FROM quiz_questions WHERE quiz_id = ? ORDER BY RAND() LIMIT 10";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $stmt->bind_result($questionId, $question, $option1, $option2, $option3, $option4);
    
    while ($stmt->fetch()) {
        $questions[] = [
            'id' => $questionId,
            'text' => $question,
            'options' => [$option1, $option2, $option3, $option4]
        ];
    }
    $stmt->close();
} else {
    echo "Error fetching quiz questions: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Technique 1 Quiz</title>
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
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }

        .timer.red {
            background-color: red;
            animation: flash 1s infinite;
        }

        @keyframes flash {
            0%, 50%, 100% {
                background-color: red;
            }
            25%, 75% {
                background-color: white;
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
            animation: blink-red 2s;
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
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <li><a href="#question_<?php echo $i; ?>" data-question-id="<?php echo $i; ?>">Question <?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </div>

    <!-- Main Quiz Container -->
    <div class="main-content">
        <div class="container">
            <h1>SQL Technique 1 Quiz</h1>
            <div class="timer" id="timer">Time Left: 20:00</div>
            <div class="quiz-content">
                <form id="quizForm" action="submit_quiz1.php" method="POST">
                    <input type="hidden" name="quiz_id" value="<?php echo $quizId; ?>">
                    <?php foreach ($questions as $index => $question): ?>
                        <div id="question_<?php echo $index + 1; ?>" class="question-container" style="margin-bottom: 20px;">
                            <div class="question-text">
                                <?php echo ($index + 1) . '. ' . htmlspecialchars($question['text']); ?>
                            </div>
                            <div class="options">
                                <?php foreach ($question['options'] as $optionIndex => $option): ?>
                                    <div class="option">
                                        <label>
                                            <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $optionIndex + 1; ?>">
                                            <?php echo htmlspecialchars($option); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="submit-btn">Submit Quiz</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("DOM fully loaded and parsed");

            // Timer logic
            let timerElement = document.getElementById('timer');
            let endTime = new Date().getTime() + 20 * 60 * 1000; // 20 minutes countdown

            function updateTimer() {
                let now = new Date().getTime();
                let timeLeft = endTime - now;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerElement.textContent = "Time's up!";
                    timerElement.classList.add('red');
                    return;
                }

                let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerElement.textContent = `Time Left: ${minutes}:${seconds}`;

                if (timeLeft <= 60000) { // Less than or equal to 1 minute
                    timerElement.classList.add('red');
                }
            }

            // Initial call
            updateTimer();
            // Update timer every second
            const timerInterval = setInterval(updateTimer, 1000);

            // Add event listeners to radio inputs
            document.querySelectorAll('input[type="radio"]').forEach(input => {
                input.addEventListener('change', function() {
                    const questionId = Array.from(document.querySelectorAll('input[name^="question_"]')).find(input => input.checked)?.name.split('_')[1];
                    
                    if (questionId) {
                        const sidebarLink = document.querySelector(`.sidebar ul li a[data-question-id="${questionId}"]`);
                        console.log(`Sidebar link: ${sidebarLink}`);

                        if (sidebarLink) {
                            sidebarLink.classList.add('answered');
                        }
                    }
                });
            });

            // Smooth scrolling for sidebar links
            document.querySelectorAll('.sidebar ul li a').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });

        // Disable back button
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
</body>
</html>
