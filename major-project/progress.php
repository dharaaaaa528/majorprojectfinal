<?php
require_once 'dbconfig.php';
require_once 'topnav.php';
require_once 'header.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION["login"]) && !isset($_SESSION["google_loggedin"])) {
    header("Location: login.php");
    exit();
}

// Function to fetch completed quizzes count and last completed quiz ID
function getCompletedQuizzesCount($pdo, $user_id, $quiz_ids) {
    $inClause = implode(',', array_fill(0, count($quiz_ids), '?'));
    $stmt = $pdo->prepare("SELECT COUNT(*) as count, MAX(quiz_id) as max_quiz_id FROM userprogress WHERE user_id = ? AND quiz_id IN ($inClause) AND status = 'completed'");
    $stmt->execute(array_merge([$user_id], $quiz_ids));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to fetch completed tests count
function getCompletedTestsCount($pdo, $user_id, $test_ids) {
    $inClause = implode(',', array_fill(0, count($test_ids), '?'));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM test_attempts WHERE user_id = ? AND test_id IN ($inClause) AND score >= 7"); // Assuming passing score is 7
    $stmt->execute(array_merge([$user_id], $test_ids));
    return $stmt->fetchColumn();
}

// Function to get the list of incomplete quizzes
function getIncompleteQuizzes($pdo, $user_id, $quiz_ids) {
    $inClause = implode(',', array_fill(0, count($quiz_ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name FROM quizzes WHERE id IN ($inClause) AND id NOT IN (SELECT quiz_id FROM userprogress WHERE user_id = ? AND status = 'completed')");
    $stmt->execute(array_merge($quiz_ids, [$user_id]));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get the list of incomplete tests
function getIncompleteTests($pdo, $user_id, $test_ids) {
    $inClause = implode(',', array_fill(0, count($test_ids), '?'));
    $stmt = $pdo->prepare("SELECT test_id, name FROM tests WHERE test_id IN ($inClause) AND test_id NOT IN (SELECT test_id FROM test_attempts WHERE user_id = ? AND score >= 7)");
    $stmt->execute(array_merge($test_ids, [$user_id]));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Define quiz and test IDs for SQL and XSS
$sql_quiz_ids = [1, 2, 3, 4];
$xss_quiz_ids = [5, 6, 7, 8];
$sql_test_ids = [1, 2, 3, 4];
$xss_test_ids = [5, 6, 7, 8];

// Fetch completed quizzes and tests counts
$user_id = $_SESSION['userid'];

// Fetch completed quizzes and last completed quiz ID for SQL
$sql_quiz_data = getCompletedQuizzesCount($pdo, $user_id, $sql_quiz_ids);
$completed_sql_quizzes = $sql_quiz_data['count'];
$last_completed_sql_quiz_id = $sql_quiz_data['max_quiz_id'];

// Fetch completed quizzes and last completed quiz ID for XSS
$xss_quiz_data = getCompletedQuizzesCount($pdo, $user_id, $xss_quiz_ids);
$completed_xss_quizzes = $xss_quiz_data['count'];
$last_completed_xss_quiz_id = $xss_quiz_data['max_quiz_id'];

$completed_sql_tests = getCompletedTestsCount($pdo, $user_id, $sql_test_ids);
$completed_xss_tests = getCompletedTestsCount($pdo, $user_id, $xss_test_ids);

// Fetch incomplete quizzes and tests
$incomplete_sql_quizzes = getIncompleteQuizzes($pdo, $user_id, $sql_quiz_ids);
$incomplete_xss_quizzes = getIncompleteQuizzes($pdo, $user_id, $xss_quiz_ids);
$incomplete_sql_tests = getIncompleteTests($pdo, $user_id, $sql_test_ids);
$incomplete_xss_tests = getIncompleteTests($pdo, $user_id, $xss_test_ids);

// Total quizzes and tests counts
$total_sql_quizzes = count($sql_quiz_ids);
$total_xss_quizzes = count($xss_quiz_ids);
$total_sql_tests = count($sql_test_ids);
$total_xss_tests = count($xss_test_ids);

// Initialize last completed quiz IDs in session if not set
if (!isset($_SESSION['completed_sql_quiz_ids'])) {
    $_SESSION['completed_sql_quiz_ids'] = [];
}
if (!isset($_SESSION['completed_xss_quiz_ids'])) {
    $_SESSION['completed_xss_quiz_ids'] = [];
}

// Update session if quiz ID changes
$update_sql_progress = false;
$update_xss_progress = false;

if ($last_completed_sql_quiz_id && !in_array($last_completed_sql_quiz_id, $_SESSION['completed_sql_quiz_ids'])) {
    $_SESSION['completed_sql_quiz_ids'][] = $last_completed_sql_quiz_id;
    $update_sql_progress = true;
}

if ($last_completed_xss_quiz_id && !in_array($last_completed_xss_quiz_id, $_SESSION['completed_xss_quiz_ids'])) {
    $_SESSION['completed_xss_quiz_ids'][] = $last_completed_xss_quiz_id;
    $update_xss_progress = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        .progress-bar-container {
            width: 80%;
            background-color: #f3f3f3;
            border-radius: 25px;
            margin: 20px 0;
            position: relative;
        }

        .progress-bar {
            height: 30px;
            background-color: #4caf50;
            border-radius: 25px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress-bar span {
            color: black;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
            z-index: 1;
        }

        .sidebar {
            width: 200px;
            background-color: #000;
            height: calc(100vh - 20px);
            position: absolute;
            top: 99px;
            left: 0;
            padding-top: 20px;
            color: #fff;
            border-right: 2px solid white;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .sidebar a.progress-link {
            color: #56C2DD;
        }

        .main-content {
            margin-left: 200px; /* Space for the side navigation */
            padding: 20px;
            width: calc(100% - 200px); /* Adjust width based on sidebar */
            box-sizing: border-box;
            height: calc(100vh - 50px); /* Adjust based on top nav height */
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.7);
            height: 100vh;
        }
         .sub-menu {
            padding-left: 30px;
        }

        .sub-menu a {
            font-size: 16px;
        }
    </style>
</head>
<body>



<div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <div class="sub-menu">
            <a href="certificate_details.php" class="details-link"><u>Certificate Details</u></a>
        </div>
         <a href="progress.php" class="progress-link"><u>Progress</u></a>
        <a href="certificate.php"><u>Certifications</u></a>
        <a href="settings.php"><u>Settings</u></a>
    </div> 
<div class="main-content">
    <h1>Progress</h1>

    <h2>SQL Quizzes</h2>
    <div class="progress-bar-container">
        <div class="progress-bar" style="width: <?= ($completed_sql_quizzes / $total_sql_quizzes) * 100 ?>%">
            <span><?= $completed_sql_quizzes ?> / <?= $total_sql_quizzes ?> completed</span>
        </div>
    </div>
    <?php if ($incomplete_sql_quizzes): ?>
        <p>Incomplete SQL Quizzes:</p>
        <ul>
            <?php foreach ($incomplete_sql_quizzes as $quiz): ?>
                <li><?= htmlspecialchars($quiz['name']) ?> </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>All SQL Quizzes are completed!</p>
    <?php endif; ?>

    <h2>XSS Quizzes</h2>
    <div class="progress-bar-container">
        <div class="progress-bar" style="width: <?= ($completed_xss_quizzes / $total_xss_quizzes) * 100 ?>%">
            <span><?= $completed_xss_quizzes ?> / <?= $total_xss_quizzes ?> completed</span>
        </div>
    </div>
    <?php if ($incomplete_xss_quizzes): ?>
        <p>Incomplete XSS Quizzes:</p>
        <ul>
            <?php foreach ($incomplete_xss_quizzes as $quiz): ?>
                <li><?= htmlspecialchars($quiz['name']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>All XSS Quizzes are completed!</p>
    <?php endif; ?>

    <h2>SQL Tests</h2>
    <div class="progress-bar-container">
        <div class="progress-bar" style="width: <?= ($completed_sql_tests / $total_sql_tests) * 100 ?>%">
            <span><?= $completed_sql_tests ?> / <?= $total_sql_tests ?> completed</span>
        </div>
    </div>
    <?php if ($incomplete_sql_tests): ?>
        <p>Incomplete SQL Tests:</p>
        <ul>
            <?php foreach ($incomplete_sql_tests as $test): ?>
                <li><?= htmlspecialchars($test['name']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>All SQL Tests are completed!</p>
    <?php endif; ?>

    <h2>XSS Tests</h2>
    <div class="progress-bar-container">
        <div class="progress-bar" style="width: <?= ($completed_xss_tests / $total_xss_tests) * 100 ?>%">
            <span><?= $completed_xss_tests ?> / <?= $total_xss_tests ?> completed</span>
        </div>
    </div>
    <?php if ($incomplete_xss_tests): ?>
        <p>Incomplete XSS Tests:</p>
        <ul>
            <?php foreach ($incomplete_xss_tests as $test): ?>
                <li><?= htmlspecialchars($test['name']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>All XSS Tests are completed!</p>
    <?php endif; ?>

</div>

</body>
</html>

