<?php
require_once 'config.php';
require_once 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
$totalQuestions = 10; // Fixed number of questions

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

    // Score and percentage calculations
    $percentage = ($score / $totalQuestions) * 100;
    $pass = $percentage >= 70;

    // Insert the quiz attempt
    $sql_insert_attempt = "INSERT INTO quiz_attempts (user_id, quiz_id, score, created_at) VALUES (?, ?, ?, NOW())";
    if ($stmt_insert_attempt = $conn->prepare($sql_insert_attempt)) {
        $stmt_insert_attempt->bind_param("iii", $userId, $quizId, $score);
        if ($stmt_insert_attempt->execute()) {
            // Set quiz submission session variable
            $_SESSION['quiz_submitted'] = true;

            // Update or insert into userprogress table only if score is 7 or higher
            if ($score >= 7) {
                $sql_upsert_progress = "
                    INSERT INTO userprogress (user_id, quiz_id, status, completed_at)
                    VALUES (?, ?, 'completed', NOW())
                    ON DUPLICATE KEY UPDATE
                        status = 'completed',
                        completed_at = NOW()
                ";
                if ($stmt_upsert_progress = $conn->prepare($sql_upsert_progress)) {
                    $stmt_upsert_progress->bind_param("ii", $userId, $quizId);
                    if (!$stmt_upsert_progress->execute()) {
                        echo "Error executing progress statement: " . $stmt_upsert_progress->error;
                    }
                    $stmt_upsert_progress->close();
                } else {
                    echo "Error preparing progress statement: " . $conn->error;
                }
            }

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
                            color: #333;
                            text-align: center;
                            margin-top: 50px;
                        }
                        .content {
                            background-color: rgba(0, 0, 0, 0.5);
                            padding: 20px;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            display: inline-block;
                            width: 80%;
                            max-width: 1000px;
                            margin: 0 auto;
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
                            width: 100%;
                            margin: 20px auto;
                            border-collapse: collapse;
                            text-align: center;
                        }
                        th, td {
                            padding: 10px;
                            border: 1px solid #ccc;
                        }
                        th {
                            background-color: black;
                        }
                        .highest-score {
                            margin-top: 20px;
                            font-weight: bold;
                        }
                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            font-size: 16px;
                            color: #fff;
                            background-color: #007bff;
                            border: none;
                            border-radius: 5px;
                            text-decoration: none;
                            cursor: pointer;
                            margin-top: 20px;
                        }
                        .button:hover {
                            background-color: #0056b3;
                        }
                    </style>
                </head>
                <body>
                    <div class='content'>
                        <h1>Quiz Result</h1>
                        <div class='result " . ($pass ? "pass" : "fail") . "'>
                            You scored " . $score . " out of 10.<br>
                            Percentage: " . number_format($percentage, 2) . "%<br>
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
                
                echo "<a href='contentpage.php' class='button'>Go Back to Content</a>";
                if ($pass) {
                    echo "<a href='choose_certificate.php?quiz_id=$quizId' class='button'>Generate Certificate</a>";
                }

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
