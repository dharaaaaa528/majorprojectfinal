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
    // If the form has not been submitted, but the page is accessed directly, redirect to sqltest.php
    header("Location: sqltest.php");
    exit();
}

$userId = $_SESSION['userid'];
$testId = $_POST['test_id'];

// Check if resubmission
$isResubmission = isset($_POST['resubmission']) && $_POST['resubmission'] === 'true';

if ($isResubmission) {
    // Redirect to sqltest.php on resubmission
    header("Location: sqltest.php?test_id=$testId");
    exit();
}

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

if ($totalQuestions === 0) {
    echo "No questions found for this test.";
    exit();
}

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

// Check if score is 40 or more and insert into test_progress
if ($score >= 40) {
    $status = 'Completed'; // or whatever status you want to set
    $attemptsCount = count($attempts);

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

// Delete rows from userprogress table if failed attempts count is 3 or more
if ($failedAttemptsCount >= 3) {
    // Determine which quizzes/tests are associated with the failed test
    $sqlQuizIds = [1, 2, 3, 4];
    $xssQuizIds = [5, 6, 7, 8];

    if (in_array($testId, $sqlQuizIds)) {
        $relatedQuizIds = implode(',', $sqlQuizIds);
    } elseif (in_array($testId, $xssQuizIds)) {
        $relatedQuizIds = implode(',', $xssQuizIds);
    } else {
        $relatedQuizIds = '';
    }

    if ($relatedQuizIds) {
        $sql = "DELETE FROM userprogress WHERE user_id = ? AND quiz_id IN ($relatedQuizIds)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $userId);
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
                    <th>Attempt No</th>
                    <th>Score</th>
                    <th>Attempt Date</th>
                </tr>
                <?php foreach ($attempts as $index => $attempt): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $attempt['score']; ?>/<?php echo $totalQuestions; ?></td>
                    <td><?php echo $attempt['attempt_date']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script>
        // Prevent navigation and refresh
        window.addEventListener('popstate', function () {
            history.go(1); // Prevent backward navigation
        });

        // Prevent page reload and redirect to sqltest.php
        window.addEventListener('beforeunload', function (e) {
            var testId = <?php echo json_encode($testId); ?>;
            e.preventDefault(); // Prevent default reload behavior
            e.returnValue = ''; // Required for some browsers
            window.location.href = 'sqltest.php?test_id=' + testId;
        });

        // Preventing the user from using the back button to navigate away from the page
        history.pushState(null, null, location.href);
    </script>
</body>
</html>

