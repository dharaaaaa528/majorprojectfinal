<?php
// Start output buffering
ob_start();

require_once 'config.php';
require_once 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php'; // Include Composer's autoload file

use setasign\Fpdi\Fpdi; // Use the correct namespace for FPDF

// Check if user is logged in
if ((!isset($_SESSION["login"]) || $_SESSION["login"] !== true) && (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true)) {
    header("Location: login.php");
    exit();
}

// Get the user ID and quiz ID
$userId = $_SESSION["userid"];
$quizId = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : 0;
$templateId = isset($_POST['template_id']) ? intval($_POST['template_id']) : 0;

if ($quizId === 0 || $templateId === 0) {
    echo "Invalid quiz ID or template ID.";
    exit();
}

// Fetch the user's details
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

// Fetch the course name
$courseSql = "SELECT name FROM quizzes WHERE id = ?";
if ($courseStmt = $conn->prepare($courseSql)) {
    $courseStmt->bind_param("i", $quizId);
    $courseStmt->execute();
    $courseStmt->bind_result($courseName);
    $courseStmt->fetch();
    $courseStmt->close();
} else {
    echo "Error fetching course details: " . $conn->error;
    exit();
}

// Fetch the user's highest score and creation timestamp for the quiz
$scoreSql = "SELECT MAX(score) AS highest_score, MAX(created_at) AS created_at FROM quiz_attempts WHERE user_id = ? AND quiz_id = ?";
if ($scoreStmt = $conn->prepare($scoreSql)) {
    $scoreStmt->bind_param("ii", $userId, $quizId);
    $scoreStmt->execute();
    $scoreStmt->bind_result($highestScore, $createdAt);
    $scoreStmt->fetch();
    $scoreStmt->close();
} else {
    echo "Error fetching highest score: " . $conn->error;
    exit();
}

// Clear the output buffer to prevent any output issues
ob_end_clean();

// Create the certificate
$pdf = new Fpdi();

// Choose the correct template based on template ID
$templateFile = '';
switch ($templateId) {
    case 1:
        $templateFile = 'templates/template1.pdf';
        break;
    case 2:
        $templateFile = 'templates/template2.pdf';
        break;
    case 3:
        $templateFile = 'templates/template3.pdf';
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

// Replace these coordinates with the ones from your template
$pdf->SetXY(50, 120); // Coordinates for the name
$pdf->Write(10, "$firstName $lastName");

$pdf->SetXY(50, 140); // Coordinates for the course name
$pdf->Write(10, $courseName);

$pdf->SetXY(50, 160); // Coordinates for the highest score
$pdf->Write(10, "Highest Score: " . $highestScore);

// Add the creation timestamp
$pdf->SetXY(50, 180); // Coordinates for the creation timestamp
$pdf->Write(10, "Date of Completion: " . date('F j, Y', strtotime($createdAt)));

// Save the PDF to a file
$filePath = 'certificates/certificate_' . $userId . '_' . $quizId . '.pdf';
$pdf->Output('F', $filePath); // Save to file

// Save certificate information in the database
$sql = "INSERT INTO certificates (user_id, quiz_id, file_path) VALUES (?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iis", $userId, $quizId, $filePath);
    $stmt->execute();
    $certificateId = $stmt->insert_id; // Get the last inserted ID
    $stmt->close();
} else {
    echo "Error saving certificate details: " . $conn->error;
}

// Redirect to the certificate view page
header("Location: certificate.php?id=" . $certificateId);
exit();
?>
