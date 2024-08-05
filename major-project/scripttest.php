<?php
include 'topnav.php';  // Make sure this path is correct

// Connect to the database
require_once 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userid'];

// Function to check if the user has completed quizzes 5, 6, 7, and 8
function hasCompletedRequiredQuizzes($conn, $userId) {
    $requiredQuizzes = [5, 6, 7, 8];
    $placeholders = implode(',', array_fill(0, count($requiredQuizzes), '?'));
    $sql = "SELECT COUNT(*) FROM userprogress WHERE user_id = ? AND quiz_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $types = str_repeat('i', count($requiredQuizzes) + 1);
        $stmt->bind_param($types, $userId, ...$requiredQuizzes);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count == count($requiredQuizzes);
    } else {
        return false;
    }
}

// Function to check if the user has any progress for a specific test
function hasTestProgress($conn, $userId, $testId) {
    $sql = "SELECT COUNT(*) FROM test_progress WHERE user_id = ? AND test_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $testId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0; // Return true if there is progress
    } else {
        return false;
    }
}

$completedRequiredQuizzes = hasCompletedRequiredQuizzes($conn, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        nav {
            background-color: #333;
            width: 100%;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .container {
            text-align: center;
            background-color: darkgrey;
            color: black;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 80%;
            width: 500px;
        }

        .container h1 {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .container label {
            font-size: 1em;
            font-weight: bold;
        }

        .container button {
            margin-top: 20px;
            padding: 25px 80px;
            font-size: 1.5em;
            border-radius: 10px;
            border: none;
            background-color: black;
            color: white;
            cursor: pointer;
            width: 100%;
            max-width: 100%;
        }

        .container button:hover {
            background-color: grey;
        }

        .container button:disabled {
            background-color: #555;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <nav>
        <?php include 'header.php'; ?>
    </nav>
    <div class="main-content">
        <div class="container">
            <h1>SCRIPT INJECTION TEST</h1>
            <label for="technique">Select a level you would like to attempt:</label>
            <br>
            <?php 
            // Test IDs for different levels
            $tests = [
                'basic' => 5,
                'intermediate' => 6,
                'advanced' => 7,
                
            ];

            // Display buttons conditionally
            foreach ($tests as $level => $testId) {
                $hasTestProgress = hasTestProgress($conn, $userId, $testId);
                $disabled = $hasTestProgress ? 'disabled' : '';
                $buttonText = strtoupper($level);
                $valueText = "XSS Test " . ucfirst($level);
                $formAction = "teststart$level.php";
                echo "<form action='$formAction' method='get'>
                        <button type='submit' name='technique' value='$valueText' $disabled>$buttonText</button>
                      </form>";
            }
            ?>
        </div>
    </div>
</body>
</html>
