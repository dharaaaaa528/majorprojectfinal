<?php
require_once 'dbconfig.php';
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

// Function to fetch quizzes based on type
function getQuizzesByType($pdo, $user_id, $type) {
    $stmt = $pdo->prepare("SELECT q.id, q.name, 
                            (SELECT COUNT(*) FROM userprogress up WHERE up.quiz_id = q.id AND up.user_id = ? AND up.status = 'completed') AS completed
                           FROM quizzes q 
                           WHERE q.type = ?");
    $stmt->execute([$user_id, $type]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch tests based on category
function getTestsByCategory($pdo, $user_id, $category) {
    $stmt = $pdo->prepare("SELECT t.test_id, t.name, 
                            (SELECT COUNT(*) FROM test_progress tp WHERE tp.test_id = t.test_id AND tp.user_id = ? AND tp.status = 'completed') AS completed
                           FROM tests t 
                           WHERE t.category = ?");
    $stmt->execute([$user_id, $category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch user ID
$user_id = $_SESSION['userid'];

// Fetch quizzes by type
$sql_quizzes = getQuizzesByType($pdo, $user_id, 'SQL');
$xss_quizzes = getQuizzesByType($pdo, $user_id, 'XSS');

// Fetch tests by category
$sql_tests = getTestsByCategory($pdo, $user_id, 'SQL');
$xss_tests = getTestsByCategory($pdo, $user_id, 'XSS');

// Calculate completed and incomplete quizzes and tests
$completed_sql_quizzes = count(array_filter($sql_quizzes, fn($quiz) => $quiz['completed'] > 0));
$completed_xss_quizzes = count(array_filter($xss_quizzes, fn($quiz) => $quiz['completed'] > 0));
$completed_sql_tests = count(array_filter($sql_tests, fn($test) => $test['completed'] > 0));
$completed_xss_tests = count(array_filter($xss_tests, fn($test) => $test['completed'] > 0));

$total_sql_quizzes = count($sql_quizzes);
$total_xss_quizzes = count($xss_quizzes);
$total_sql_tests = count($sql_tests);
$total_xss_tests = count($xss_tests);

$incomplete_sql_quizzes = array_filter($sql_quizzes, fn($quiz) => $quiz['completed'] == 0);
$incomplete_xss_quizzes = array_filter($xss_quizzes, fn($quiz) => $quiz['completed'] == 0);
$incomplete_sql_tests = array_filter($sql_tests, fn($test) => $test['completed'] == 0);
$incomplete_xss_tests = array_filter($xss_tests, fn($test) => $test['completed'] == 0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Progress</title>
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
            position: fixed;
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
         .topnav {
            
            z-index: 1000;
        }
        /* Styles go here */
        /* Same as before */
    </style>
</head>
<body>

<div class="sidebar">
    <a href="profile.php" class="profile-link"><u>Profile</u></a>
    <div class="sub-menu">
        <a href="certificate_details.php" class="details-link"><u>Certificate Details</u></a>
    </div>
    <div class="sub-menu">
        <a href="delete_account.php" class="details1-link"><u>Delete Account</u></a>
    </div>
    <a href="progress.php" class="progress-link"><u>Progress</u></a>
    <a href="certificate.php"><u>Quiz Certifications</u></a>
    <a href="test_certificate.php"><u>Test Certifications</u></a>
    <a href="settings.php"><u>Settings</u></a>
</div> 
<div class="main-content">
    <br><br><br><br><br>
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

    <h2>SQL Tests (Will unlock after all the SQL quizzes are completed)</h2>
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

    <h2>XSS Tests (Will unlock after all the XSS quizzes are completed)</h2>
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
<?php include 'topnav.php';?>
