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
$quizId = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : (isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0);
$templateId = isset($_POST['template_id']) ? intval($_POST['template_id']) : (isset($_GET['template_id']) ? intval($_GET['template_id']) : 0);

if ($quizId === 0 || $templateId === 0) {
    echo "Invalid quiz ID or template ID.";
    exit();
}

// Check if the certificate already exists or if a similar attempt was made
$certSql = "SELECT id, attempt_created_at FROM certificates WHERE user_id = ? AND quiz_id = ? ORDER BY attempt_created_at DESC";
if ($certStmt = $conn->prepare($certSql)) {
    $certStmt->bind_param("ii", $userId, $quizId);
    $certStmt->execute();
    $certStmt->store_result();
    $certStmt->bind_result($existingCertId, $existingAttemptCreatedAt);
    $certExists = $certStmt->num_rows > 0;
    
    if ($certExists) {
        $certStmt->fetch();
        // Check if an attempt with the same timestamp exists
        $attemptSql = "SELECT MAX(created_at) AS latest_attempt FROM quiz_attempts WHERE user_id = ? AND quiz_id = ?";
        if ($attemptStmt = $conn->prepare($attemptSql)) {
            $attemptStmt->bind_param("ii", $userId, $quizId);
            $attemptStmt->execute();
            $attemptStmt->bind_result($latestAttemptCreatedAt);
            $attemptStmt->fetch();
            $attemptStmt->close();
            
            if ($latestAttemptCreatedAt === $existingAttemptCreatedAt) {
                // Same timestamp found, show "Return to Content Page" button with a message
                echo '<h2>Certificate Already Generated</h2>';
                echo '<p>You have already generated a certificate for this quiz. You can return to the content page.</p>';
                echo '<a href="contentpage.php"><button>Return to Content Page</button></a>';
                exit();
            }
        } else {
            echo "Error checking quiz attempts: " . $conn->error;
            exit();
        }
    }
    $certStmt->close();
} else {
    echo "Error checking certificate: " . $conn->error;
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

// If first name or last name is not set, prompt the user to enter them
if (empty($firstName) || empty($lastName)) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['first_name']) && isset($_POST['last_name'])) {
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);

        // Update user info in the database
        $updateUserSql = "UPDATE userinfo SET first_name = ?, last_name = ? WHERE userid = ?";
        if ($updateUserStmt = $conn->prepare($updateUserSql)) {
            $updateUserStmt->bind_param("ssi", $firstName, $lastName, $userId);
            if ($updateUserStmt->execute()) {
                // Redirect to the certificate generation page after updating user info
                header("Location: " . $_SERVER['PHP_SELF'] . "?quiz_id=" . $quizId . "&template_id=" . $templateId);
                exit();
            } else {
                echo "Error updating user details: " . $updateUserStmt->error;
            }
            $updateUserStmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?quiz_id=' . $quizId . '&template_id=' . $templateId . '">';
        echo 'Please enter your first name and last name:<br>';
        echo 'First Name: <input type="text" name="first_name" required><br>';
        echo 'Last Name: <input type="text" name="last_name" required><br>';
        echo '<input type="submit" value="Submit">';
        echo '</form>';
        exit();
    }
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
    $scoreStmt->bind_result($highestScore, $attemptCreatedAt);
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
$coordinates = [];
switch ($templateId) {
    case 1:
        $templateFile = 'templates/template1.pdf';
        $coordinates = [
            'name' => [85, 135],
            'course' => [35, 170],
            'date' => [135, 170]
        ];
        break;
    case 2:
        $templateFile = 'templates/template2.pdf';
        $coordinates = [
            'name' => [85, 140],
            'course' => [30, 180],
            'date' => [130, 180]
        ];
        break;
    case 3:
        $templateFile = 'templates/template3.pdf';
        $coordinates = [
            'name' => [30, 120],
            'course' => [20, 180],
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

// Replace these coordinates with the ones from your template
$pdf->SetXY($coordinates['name'][0], $coordinates['name'][1]);
$pdf->Write(10, "$firstName $lastName");

$pdf->SetXY($coordinates['course'][0], $coordinates['course'][1]);
$pdf->Write(10, $courseName);

$pdf->SetXY($coordinates['date'][0], $coordinates['date'][1]);
$pdf->Write(10, date('j F Y', strtotime($attemptCreatedAt)));

// Save the PDF to a file
$filePath = 'certificates/certificate_' . $userId . '_' . $quizId . '_' . $templateId . '_' . time() . '.pdf';
$pdf->Output('F', $filePath); // Save to file

// Save certificate information in the database
$sql = "INSERT INTO certificates (user_id, quiz_id, template_id, file_path, attempt_created_at, course_name, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iiisssss", $userId, $quizId, $templateId, $filePath, $attemptCreatedAt, $courseName, $firstName, $lastName);
    if ($stmt->execute()) {
        $certificateId = $stmt->insert_id;
        // Redirect to the certificate.php page with a success message
        header("Location: certificate.php?cert_id=" . $certificateId);
        exit();
    } else {
        echo "Error saving certificate information: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

$conn->close();
?>
