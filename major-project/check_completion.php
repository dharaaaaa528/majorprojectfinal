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

// Get the latest completion times for SQL and XSS quizzes
$latestSQLCompletion = getLatestCompletionTime($conn, $userId, [1, 2, 3, 4]);
$latestXSSCompletion = getLatestCompletionTime($conn, $userId, [5, 6, 7, 8]);

// Initialize pop-up scripts
$sqlPopupScript = '';
$xssPopupScript = '';

// Check if SQL quizzes completion pop-up should be shown
if ($latestSQLCompletion > $_SESSION['last_sql_check']) {
    // Check if all SQL quizzes are completed
    $sqlQuizzes = [1, 2, 3, 4];
    $sql = "SELECT COUNT(*) as completed FROM userprogress WHERE user_id = ? AND quiz_id IN (" . implode(',', $sqlQuizzes) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $completedSQLQuizzes = $row['completed'] == count($sqlQuizzes);

    if ($completedSQLQuizzes) {
        $sqlPopupScript = '<script>alert("The SQL test is now unlocked!");</script>';
        $_SESSION['last_sql_check'] = $latestSQLCompletion;
    }
}

// Check if XSS quizzes completion pop-up should be shown
if ($latestXSSCompletion > $_SESSION['last_xss_check']) {
    // Check if all XSS quizzes are completed
    $xssQuizzes = [5, 6, 7, 8];
    $sql = "SELECT COUNT(*) as completed FROM userprogress WHERE user_id = ? AND quiz_id IN (" . implode(',', $xssQuizzes) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $completedXSSQuizzes = $row['completed'] == count($xssQuizzes);

    if ($completedXSSQuizzes) {
        $xssPopupScript = '<script>alert("The XSS test is now unlocked!");</script>';
        $_SESSION['last_xss_check'] = $latestXSSCompletion;
    }
}

// Echo the scripts to include them in the HTML
echo $sqlPopupScript . $xssPopupScript;
?>
