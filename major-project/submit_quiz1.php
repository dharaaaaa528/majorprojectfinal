<?php
require_once 'config.php';
session_start();

if (isset($_SESSION['quiz_submitted']) && $_SESSION['quiz_submitted'] === true) {
    header("Location: contentpage.php");
    exit();
}

// Redirect to the login page if not logged in (either traditional login or Google login)
if ((!isset($_SESSION["login"]) || $_SESSION["login"] !== true) && (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true)) {
    header("Location: login.php");
    exit();
}


$userId = $_SESSION["userid"];
$quizId = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : 0;

if ($quizId === 0) {
    echo "Invalid quiz ID.";
    exit();
}

$score = 0;
$totalQuestions = 0;

// Fetch quiz questions
$sql = "SELECT id, correct_option FROM quiz_questions WHERE quiz_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $stmt->bind_result($questionId, $correctOption);
    
    $questions = [];
    while ($stmt->fetch()) {
        $questions[] = [
            'id' => $questionId,
            'correct_option' => $correctOption
        ];
        $totalQuestions++;
    }
    $stmt->close();
}

// Handle quiz submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if quiz submission session variable is set
    if (isset($_SESSION['quiz_submitted']) && $_SESSION['quiz_submitted'] === true) {
        // Prevent resubmission
        header("Location: contentpage.php"); // Redirect to content page or any other page
        exit();
    }

    foreach ($questions as $question) {
        $questionId = $question['id'];
        $correctOption = $question['correct_option'];
        $selectedOption = isset($_POST['question_' . $questionId]) ? intval($_POST['question_' . $questionId]) : 0;

        if ($selectedOption == $correctOption) {
            $score++;
        }
    }

    // Calculate percentage
    $percentage = ($score / $totalQuestions) * 100;
    $pass = $percentage >= 70;

    // Insert the quiz attempt
    $sql_insert_attempt = "INSERT INTO quiz_attempts (user_id, quiz_id, score, created_at) VALUES (?, ?, ?, NOW())";
    if ($stmt_insert_attempt = $conn->prepare($sql_insert_attempt)) {
        $stmt_insert_attempt->bind_param("iii", $userId, $quizId, $score);
        if ($stmt_insert_attempt->execute()) {
            // Set quiz submission session variable
            $_SESSION['quiz_submitted'] = true;

            // Fetch all attempts for the user
            $sql_fetch_attempts = "SELECT id, score, created_at FROM quiz_attempts WHERE user_id = ? AND quiz_id = ?";
            if ($stmt_fetch_attempts = $conn->prepare($sql_fetch_attempts)) {
                $stmt_fetch_attempts->bind_param("ii", $userId, $quizId);
                $stmt_fetch_attempts->execute();
                $stmt_fetch_attempts->bind_result($attemptId, $attemptScore, $attemptCreatedAt);

                // Display quiz result and attempts table
                echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <title>Quiz Result</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f0f0f0;
                            color: #333;
                            text-align: center;
                            margin-top: 50px;
                        }
                        .result {
                            font-size: 24px;
                            margin: 20px;
                        }
                        .pass {
                            color: green;
                        }
                        .fail {
                            color: red;
                        }
                        table {
                            width: 60%;
                            margin: 20px auto;
                            border-collapse: collapse;
                            text-align: center;
                        }
                        th, td {
                            padding: 10px;
                            border: 1px solid #ccc;
                        }
                        th {
                            background-color: #f0f0f0;
                        }
                        .highest-score {
                            margin-top: 20px;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <h1>Quiz Result</h1>
                    <div class='result " . ($pass ? "pass" : "fail") . "'>
                        You scored $score out of $totalQuestions.<br>
                        Percentage: $percentage%<br>
                        Status: " . ($pass ? "Pass" : "Fail") . "
                    </div>
                    <h2>Quiz Attempts</h2>
                    <table>
                        <tr>
                            <th>Attempt Number</th>
                            <th>Score</th>
                            <th>Date Done</th>
                        </tr>";
                
                $highestScore = 0;
                $attemptNumber = 1;
                while ($stmt_fetch_attempts->fetch()) {
                    echo "<tr>
                            <td>$attemptNumber</td>
                            <td>$attemptScore</td>
                            <td>$attemptCreatedAt</td>
                        </tr>";
                    // Track highest score
                    if ($attemptScore > $highestScore) {
                        $highestScore = $attemptScore;
                    }
                    $attemptNumber++;
                }
                echo "</table>";
                
                // Display highest score
                echo "<div class='highest-score'>Highest Score: $highestScore</div>";

                // JavaScript to disable back button and handle navigation
                echo "<script>
                        history.pushState(null, null, location.href);
                        window.onpopstate = function () {
                            history.go(1);
                        };
                        // End quiz session to prevent back navigation
                        sessionStorage.setItem('quiz_submitted', true);
                    </script>";

                $stmt_fetch_attempts->close();
            } else {
                echo "Error fetching attempts: " . $conn->error;
            }
            $stmt_insert_attempt->close();
        } else {
            echo "Error storing quiz attempt: " . $stmt_insert_attempt->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>


