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

// Initialize session variables for tracking the latest completion
if (!isset($_SESSION['last_sql_check'])) {
    $_SESSION['last_sql_check'] = 0;
}
if (!isset($_SESSION['last_xss_check'])) {
    $_SESSION['last_xss_check'] = 0;
}

// Function to check the latest quiz completion time
function getLatestCompletionTime($conn, $userId, $quizIds) {
    $sql = "SELECT MAX(completed_at) as latest_completion FROM userprogress WHERE user_id = ? AND quiz_id IN (" . implode(',', $quizIds) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['latest_completion'];
}

// Fetch quiz IDs based on type
function fetchQuizIdsByType($conn, $type) {
    $sql = "SELECT id FROM quizzes WHERE type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $quizIds = [];
    while ($row = $result->fetch_assoc()) {
        $quizIds[] = $row['id'];
    }
    return $quizIds;
}

// Get quiz IDs by type
$sqlQuizIds = fetchQuizIdsByType($conn, 'SQL');
$xssQuizIds = fetchQuizIdsByType($conn, 'XSS');

// Get the latest completion times for SQL and XSS quizzes
$latestSQLCompletion = getLatestCompletionTime($conn, $userId, $sqlQuizIds);
$latestXSSCompletion = getLatestCompletionTime($conn, $userId, $xssQuizIds);

// Initialize pop-up scripts
$sqlPopupScript = '';
$xssPopupScript = '';

// Check if SQL quizzes completion pop-up should be shown
if ($latestSQLCompletion > $_SESSION['last_sql_check']) {
    // Check if all SQL quizzes are completed
    $sql = "SELECT COUNT(*) as completed FROM userprogress WHERE user_id = ? AND quiz_id IN (" . implode(',', $sqlQuizIds) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $completedSQLQuizzes = $row['completed'] == count($sqlQuizIds);

    if ($completedSQLQuizzes) {
        $sqlPopupScript = '<script>alert("The SQL test is now unlocked!");</script>';
        $_SESSION['last_sql_check'] = $latestSQLCompletion;
    }
}

// Check if XSS quizzes completion pop-up should be shown
if ($latestXSSCompletion > $_SESSION['last_xss_check']) {
    // Check if all XSS quizzes are completed
    $sql = "SELECT COUNT(*) as completed FROM userprogress WHERE user_id = ? AND quiz_id IN (" . implode(',', $xssQuizIds) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $completedXSSQuizzes = $row['completed'] == count($xssQuizIds);

    if ($completedXSSQuizzes) {
        $xssPopupScript = '<script>alert("The XSS test is now unlocked!");</script>';
        $_SESSION['last_xss_check'] = $latestXSSCompletion;
    }
}

// Echo the scripts to include them in the HTML
echo $sqlPopupScript . $xssPopupScript;
?>
