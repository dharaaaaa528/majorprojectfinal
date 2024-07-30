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
$testId = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
$templateId = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;

if ($testId === 0 || $templateId === 0) {
    echo "Invalid test ID or template ID.";
    exit();
}

require 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;

// Fetch user details
$userSql = "SELECT first_name, last_name FROM userinfo WHERE userid = ?";
if ($userStmt = $conn->prepare($userSql)) {
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userStmt->bind_result($firstName, $lastName);
    $userStmt->fetch();
    $userStmt->close();
} else {
    echo "Error fetching user details: " . $conn->error;
    exit();
}

// Prompt user for first name and last name if not set
if (empty($firstName) || empty($lastName)) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['first_name']) && isset($_POST['last_name'])) {
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);

        // Update user info in the database
        $updateUserSql = "UPDATE userinfo SET first_name = ?, last_name = ? WHERE userid = ?";
        if ($updateUserStmt = $conn->prepare($updateUserSql)) {
            $updateUserStmt->bind_param("ssi", $firstName, $lastName, $userId);
            if ($updateUserStmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?test_id=" . $testId . "&template_id=" . $templateId);
                exit();
            } else {
                echo "Error updating user details: " . $updateUserStmt->error;
            }
            $updateUserStmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?test_id=' . $testId . '&template_id=' . $templateId . '">';
        echo 'Please enter your first name and last name:<br>';
        echo 'First Name: <input type="text" name="first_name" required><br>';
        echo 'Last Name: <input type="text" name="last_name" required><br>';
        echo '<input type="submit" value="Submit">';
        echo '</form>';
        exit();
    }
}

// Fetch test details
$testSql = "SELECT name, category FROM tests WHERE test_id = ?";
if ($testStmt = $conn->prepare($testSql)) {
    $testStmt->bind_param("i", $testId);
    $testStmt->execute();
    $testStmt->bind_result($testName, $category);
    $testStmt->fetch();
    $testStmt->close();
} else {
    echo "Error fetching test details: " . $conn->error;
    exit();
}

// Fetch user's highest score and the creation timestamp for the test
$scoreSql = "SELECT MAX(score) AS highest_score, MAX(attempt_date) AS attempt_date FROM test_attempts WHERE user_id = ? AND test_id = ?";
if ($scoreStmt = $conn->prepare($scoreSql)) {
    $scoreStmt->bind_param("ii", $userId, $testId);
    $scoreStmt->execute();
    $scoreStmt->bind_result($highestScore, $attemptCreatedAt);
    $scoreStmt->fetch();
    $scoreStmt->close();
} else {
    echo "Error fetching highest score: " . $conn->error;
    exit();
}

// Create the certificate PDF
$pdf = new Fpdi();
$templateFile = '';
$coordinates = [];

// Select the correct template based on template ID
switch ($templateId) {
    case 1:
        $templateFile = 'templates/template1.pdf';
        $coordinates = [
            'name' => [85, 135],
            'test' => [35, 170],
            'date' => [135, 170]
        ];
        break;
    case 2:
        $templateFile = 'templates/template2.pdf';
        $coordinates = [
            'name' => [85, 140],
            'test' => [30, 180],
            'date' => [130, 180]
        ];
        break;
    case 3:
        $templateFile = 'templates/template3.pdf';
        $coordinates = [
            'name' => [30, 120],
            'test' => [20, 180],
            'date' => [80, 180]
        ];
        break;
    default:
        echo "Invalid template ID.";
        exit();
}

// Add the template page
$pdf->AddPage();
$pdf->setSourceFile($templateFile);
$templatePage = $pdf->importPage(1);
$pdf->useTemplate($templatePage, null, null, 210, 297, true);

// Set font and write the details on the certificate
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY($coordinates['name'][0], $coordinates['name'][1]);
$pdf->Write(10, "$firstName $lastName");

$pdf->SetXY($coordinates['test'][0], $coordinates['test'][1]);
$pdf->Write(10, "$testName ($category)");

$pdf->SetXY($coordinates['date'][0], $coordinates['date'][1]);
$pdf->Write(10, date('j F Y', strtotime($attemptCreatedAt)));

$filePath = 'certificates/certificate_' . $userId . '_' .  $templateId . '.pdf';
$pdf->Output('F', $filePath);

// Save certificate information in the database
$sql = "INSERT INTO test_certificates 
    (user_id, test_id, template_id, first_name, last_name,  file_path, attempt_date, test_name) 
    VALUES (?, ?, ?, ?, ?, ?, ?,  ?)";

// Prepare the SQL statement
if ($stmt = $conn->prepare($sql)) {
    // Bind parameters: i - integer, s - string, d - double, b - blob
    $certificateDate = date('Y-m-d H:i:s'); // Current timestamp for certificate_date
    $stmt->bind_param(
        "iiisssss",
        $userId,
        $testId,
        $templateId,
        $firstName,
        $lastName,
        $filePath,
        $attemptCreatedAt,
        $testName
    );

    // Execute the statement
    if ($stmt->execute()) {
        // Get the last inserted ID
        $certificateId = $stmt->insert_id;
        $stmt->close();
        
        // Redirect
        header("Location: test_certificate.php?id=" . $certificateId);
        exit();
    } else {
        echo "Error executing statement: " . $stmt->error;
    }
} else {
    echo "Error preparing statement: " . $conn->error;
}
?>

