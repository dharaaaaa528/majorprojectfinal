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
$testId = $_POST['test_id'];

// Fetch correct answers
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
$sql = "SELECT attempt_id, score, attempt_date FROM test_attempts WHERE user_id = ? AND test_id = ?";
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
            padding: 10px;
            border: 1px solid #ddd;
        }

        .attempts-table th {
            background-color: black;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result">
            <h1>Test Result</h1>
            <p style="font-size: 1.5em; color: <?php echo ($score >= ($totalQuestions * 0.8)) ? 'green' : 'red'; ?>">
                Score: <?php echo $score; ?>/<?php echo $totalQuestions; ?>
            </p>
            <p style="font-size: 1.5em; color: <?php echo ($score >= ($totalQuestions * 0.8)) ? 'green' : 'red'; ?>">
                Percentage: <?php echo round($percentage, 2); ?>%
            </p>
            <p class="<?php echo ($score >= ($totalQuestions * 0.8)) ? 'pass' : 'fail'; ?>">
                <?php echo ($score >= ($totalQuestions * 0.8)) ? 'Pass' : 'Fail'; ?>
            </p>
        </div>
        <div class="attempts">
            <h2>Previous Attempts</h2>
            <table class="attempts-table">
                <tr>
                    <th>Attempt ID</th>
                    <th>Score</th>
                    <th>Attempt Date</th>
                </tr>
                <?php foreach ($attempts as $attempt): ?>
                <tr>
                    <td><?php echo $attempt['attempt_id']; ?></td>
                    <td><?php echo $attempt['score']; ?>/<?php echo $totalQuestions; ?></td>
                    <td><?php echo $attempt['attempt_date']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>

