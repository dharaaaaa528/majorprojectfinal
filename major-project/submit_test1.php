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

if (!isset($_POST['test_id'])) {
    header("Location: sqltest.php");
    exit();
}

$userId = $_SESSION['userid'];
$testId = intval($_POST['test_id']);

// Check if quiz was previously submitted in this session
if (isset($_SESSION['quiz_submitted']) && $_SESSION['quiz_submitted'] === true) {
    header("Location: contentpage.php");
    exit();
}

// Initialize flag for redirection
$_SESSION['quiz_submitted'] = true;

// Handle quiz completion and score calculations
$correctAnswers = [];
$sql = "SELECT question_id, correct_option FROM test_options WHERE test_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $testId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $correctAnswers[$row['question_id']] = $row['correct_option'];
        }
        $stmt->close();
    } else {
        echo "Error executing statement: " . $stmt->error;
        exit();
    }
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Calculate score
$score = 0;
$totalQuestions = count($correctAnswers);
foreach ($correctAnswers as $questionId => $correctOption) {
    $selectedOption = isset($_POST["question_$questionId"]) ? $_POST["question_$questionId"] : null;
    if ($selectedOption == $correctOption) {
        $score++;
    }
}
$percentage = ($score / $totalQuestions) * 100;

// Insert the attempt into test_attempts table
$sql = "INSERT INTO test_attempts (user_id, test_id, score) VALUES (?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iii", $userId, $testId, $score);
    if ($stmt->execute()) {
        $stmt->close();
    } else {
        echo "Error executing statement: " . $stmt->error;
        exit();
    }
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Fetch all attempts
$attempts = [];
$sql = "SELECT score, attempt_date FROM test_attempts WHERE user_id = ? AND test_id = ? ORDER BY attempt_id";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $userId, $testId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $attempts[] = $row;
        }
        $stmt->close();
    } else {
        echo "Error executing statement: " . $stmt->error;
        exit();
    }
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Get the test category
$category = '';
$sql = "SELECT category FROM tests WHERE test_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $testId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $category = $row['category'];
        }
        $stmt->close();
    } else {
        echo "Error executing statement: " . $stmt->error;
        exit();
    }
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Check if score is 40 or more and insert or update in test_progress
if ($score >= 40) {
    $status = 'Completed';
    $attemptsCount = count($attempts);

    // Check if the record exists
    $sql = "SELECT * FROM test_progress WHERE user_id = ? AND test_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $userId, $testId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Update the existing record
                $sql = "UPDATE test_progress SET score = ?, status = ?, attempts = ?, created_at = NOW() WHERE user_id = ? AND test_id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("isiii", $score, $status, $attemptsCount, $userId, $testId);
                    if ($stmt->execute()) {
                        $stmt->close();
                    } else {
                        echo "Error executing statement: " . $stmt->error;
                        exit();
                    }
                } else {
                    echo "Error preparing statement: " . $conn->error;
                    exit();
                }
            } else {
                // Insert a new record
                $sql = "INSERT INTO test_progress (user_id, test_id, score, status, attempts, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("iiisi", $userId, $testId, $score, $status, $attemptsCount);
                    if ($stmt->execute()) {
                        $stmt->close();
                    } else {
                        echo "Error executing statement: " . $stmt->error;
                        exit();
                    }
                } else {
                    echo "Error preparing statement: " . $conn->error;
                    exit();
                }
            }
        } else {
            echo "Error executing statement: " . $stmt->error;
            exit();
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }
}

// Count the number of failed attempts
$failedAttemptsCount = 0;
$sql = "SELECT COUNT(*) FROM test_attempts WHERE user_id = ? AND test_id = ? AND score < 40";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $userId, $testId);
    if ($stmt->execute()) {
        $stmt->bind_result($failedAttemptsCount);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Error executing statement: " . $stmt->error;
        exit();
    }
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// Calculate attempts left
$totalAttemptsAllowed = 3;
$attemptsCount = count($attempts);
$remainingAttempts = $totalAttemptsAllowed - $attemptsCount;

// Fetch quiz IDs based on the test category
$quizIds = [];
$sql = "SELECT id FROM quizzes WHERE type = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $category);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $quizIds[] = $row['id'];
        }
        $stmt->close();
    } else {
        echo "Error executing statement: " . $stmt->error;
        exit();
    }
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

// If the user fails the quiz three times, delete all relevant records
if ($failedAttemptsCount >= 3 && $score < 40) {
    if (!empty($quizIds)) {
        $quizIdsPlaceholder = implode(',', array_fill(0, count($quizIds), '?'));
        
        // Delete from userprogress table
        $sql = "DELETE FROM userprogress WHERE user_id = ? AND quiz_id IN ($quizIdsPlaceholder)";
        if ($stmt = $conn->prepare($sql)) {
            $types = str_repeat('i', count($quizIds) + 1);
            $params = array_merge([$userId], $quizIds);
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute()) {
                $stmt->close();
            } else {
                echo "Error executing statement: " . $stmt->error;
                exit();
            }
        } else {
            echo "Error preparing statement: " . $conn->error;
            exit();
        }
        
        // Delete from test_attempts table
        $sql = "DELETE FROM test_attempts WHERE user_id = ? AND test_id = ? AND score < 40";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ii", $userId, $testId);
            if ($stmt->execute()) {
                $stmt->close();
            } else {
                echo "Error executing statement: " . $stmt->error;
                exit();
            }
        } else {
            echo "Error preparing statement: " . $conn->error;
            exit();
        }
    }
}

// Display the results page
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Results</title>
    <style>
        .body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .result {
            text-align: center;
            margin-bottom: 20px;
        }

        .result h1 {
            font-size: 2em;
        }

        .result p {
            font-size: 1.2em;
        }

        .result p.pass {
            color: green;
            font-weight: bold;
        }

        .result p.fail {
            color: red;
            font-weight: bold;
        }

        .attempts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attempts-table th, .attempts-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .attempts-table th {
            background-color: #4CAF50;
            color: white;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin: 5px;
        }

        .button:hover {
            background-color: #45a049;
        }

        .button.reload {
            background-color: #f44336;
        }

        .button.reload:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result">
            <h1>Test Results</h1>
            <p><?php echo "Score: $score / $totalQuestions"; ?></p>
            <p><?php echo "Percentage: " . number_format($percentage, 2) . "%"; ?></p>
            <p class="<?php echo $score >= 40 ? 'pass' : 'fail'; ?>">
                <?php echo $score >= 40 ? 'Congratulations! You have passed the test.' : 'Sorry, you have failed the test.'; ?>
            </p>
            <?php if ($score >= 40): ?>
                <p>Click the button below to generate your certificate.</p>
            <?php else: ?>
                <?php if ($attemptsCount < 3): ?>
                    <p>You have <?php echo $remainingAttempts; ?> attempt(s) left.</p>
                <?php else: ?>
                    <p>You have failed the test three times. You must redo the quizzes.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <table class="attempts-table">
            <tr>
                <th>Attempt</th>
                <th>Score</th>
                <th>Date</th>
            </tr>
            <?php foreach ($attempts as $index => $attempt): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $attempt['score']; ?></td>
                    <td><?php echo date("d-m-Y H:i:s", strtotime($attempt['attempt_date'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="button-container">
            <?php if ($score >= 0): ?>
                <a href="choose_certificate1.php?test_id=<?php echo $testId; ?>" class="button">Generate Certificate</a>
            <?php endif; ?>
            <a href="contentpage.php" class="button reload">Reload</a>
        </div>
    </div>
</body>
 <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle page reload or navigation
            window.addEventListener('beforeunload', function() {
                // Clear the quiz submission flag on unload
                sessionStorage.removeItem('quiz_submitted');
            });
        });
    </script>
</html>
