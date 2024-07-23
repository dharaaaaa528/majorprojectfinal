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
$quizId = 7; // Update with your actual quiz ID for Technique 1

if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time();
}

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
    <title>Script Technique 3 Quiz</title>
    <style>
        body {
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff; /* Ensure text is visible on dark background */
            display: flex;
        }

        html, body {
            height: 100%;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Transparent background color */
            z-index: -1; /* Ensure it's behind other content */
        }

        .quiz-container {
            flex: 1;
            padding: 20px;
            margin-left: 220px; /* Adjusted space for the sidebar */
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
        .side-nav {
            width: 200px;
            position: fixed;
            top: 45%;
            left: 0;
            transform: translateY(-50%);
            padding: 20px;
            background: rgba(0, 0, 0, 0.5); /* Transparent background color */
        }

        .side-nav a {
            display: block;
            padding: 12px 0;
            text-decoration: none;
            color: white;
            background-color: grey;
            margin-bottom: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .side-nav a.answered {
            background-color: green;
        }

        .side-nav a:hover {
            background-color: #e0e0e0;
            color: black;
        }

        .side-nav a.flash {
            animation: flashRed 0.5s;
        }

        @keyframes flashRed {
            0%, 50%, 100% {
                color: red;
            }
            25%, 75% {
                color: white;
            }
        }

        .active {
            font-weight: bold;
            color: #007bff;
        }

        .question-container.flash {
            animation: flashRed 0.5s;
        }

        @keyframes flashRed {
            0%, 50%, 100% {
                background-color: red;
            }
            25%, 75% {
                background-color: white;
            }
        }
    </style>
    <script>
        let timerDuration = 20 * 60; // 20 minutes in seconds
        let timerElement;

        function startTimer() {
            timerElement = document.getElementById("timer");
            setInterval(updateTimer, 1000);
        }

        function updateTimer() {
            let minutes = Math.floor(timerDuration / 60);
            let seconds = timerDuration % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            timerElement.textContent = `Time Left: ${minutes}:${seconds}`;

            if (timerDuration > 0) {
                timerDuration--;
                if (timerDuration <= 2 * 60) { // When 2 minutes or less are left
                    timerElement.classList.add('red');
                }
            } else {
                document.getElementById("quizForm").submit();
            }
        }

        function validateForm(event) {
            let form = document.getElementById("quizForm");
            let inputs = form.querySelectorAll("input[type=radio]");
            let checked = Array.from(inputs).some(input => input.checked);

            if (!checked) {
                alert("Please answer all questions before submitting the quiz.");
                event.preventDefault();
            }
        }

        window.onload = function() {
            startTimer();

            let quizForm = document.getElementById("quizForm");
            quizForm.addEventListener("submit", validateForm);

            // Add click event to side nav links
            document.querySelectorAll(".side-nav a").forEach(link => {
                link.addEventListener("click", function() {
                    // Remove flash class from all question containers
                    document.querySelectorAll(".question-container").forEach(container => container.classList.remove("flash"));

                    // Get the corresponding question container
                    let questionId = this.getAttribute("href").substring(1);
                    let questionContainer = document.getElementById(questionId);

                    // Add flash class to the corresponding question container
                    questionContainer.classList.add("flash");

                    // Remove flash class after 0.5 seconds
                    setTimeout(() => {
                        questionContainer.classList.remove("flash");
                    }, 500);
                });
            });

            // Change the color of the side nav link to green once the user answers the question
            document.querySelectorAll(".option input").forEach(input => {
                input.addEventListener("change", function() {
                    let questionId = this.name.split('_')[1];
                    let link = document.querySelector(`.side-nav a[href='#question_${questionId}']`);
                    if (link) {
                        link.classList.add('answered');
                    }
                });
            });

            // Handle page reload or navigation away
            window.addEventListener("beforeunload", function (e) {
                fetch('end_quiz_session.php', { method: 'POST' });
            });

            window.addEventListener("popstate", function (event) {
                fetch('end_quiz_session.php', { method: 'POST' });
                window.location.href = 'quizstart.php';
            });
        }
    </script>
</head>
<body>
    <!-- Transparent Overlay -->
    <div class="overlay"></div>

    <!-- Side Navigation Panel -->
    <div class="side-nav">
        <h2>Questions</h2>
        <?php foreach ($questions as $index => $question): ?>
            <a href="#question_<?php echo $index + 1; ?>">Question <?php echo $index + 1; ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Main Quiz Container -->
    <div class="quiz-container">
        <h1>Script Technique 3 Quiz</h1>
        <div class="timer" id="timer">Time Left: 20:00</div>
        <div class="quiz-content">
            <form id="quizForm" action="submit_quiz1.php" method="POST">
                <?php foreach ($questions as $index => $question): ?>
                    <div id="question_<?php echo $index + 1; ?>" class="question-container" style="margin-bottom: 20px;">
                        <div class="question-text">
                            <span class="question-number">Question <?php echo $index + 1; ?>:</span> <?php echo $question['text']; ?>
                        </div>
                        <div class="options">
                            <?php foreach ($question['options'] as $key => $option): ?>
                                <label class="option"><input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $key + 1; ?>"> <?php echo $option; ?></label><br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <input type="hidden" name="quiz_id" value="<?php echo $quizId; ?>">
                <input type="submit" class="submit-btn" value="Submit Quiz">
            </form>
        </div>
    </div>
</body>
</html>