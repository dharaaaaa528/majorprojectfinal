<?php
ob_start();
require 'vendor/autoload.php';  // Include Composer's autoloader
require_once 'server1.php'; // Ensure this file defines DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE
require_once 'topnav.php';
require_once 'header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userid'];

// Initialize error and success messages
$error = '';
$successMessage = '';

// Retrieve the user's email address from the database
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$query = "SELECT email FROM userinfo WHERE userid = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($userEmail);
$stmt->fetch();
$stmt->close();
$mysqli->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newFirstName = trim(htmlspecialchars($_POST['first_name']));
    $newLastName = trim(htmlspecialchars($_POST['last_name']));
    $reason = trim(htmlspecialchars($_POST['reason']));
    $document = $_FILES['document'];

    // Validation function
    function validateName($name) {
        if (strlen($name) < 2 || strlen($name) > 50) {
            return "Name must be between 2 and 50 characters.";
        }
        if (!preg_match("/^[a-zA-ZÀ-ÿ '-]+$/", $name)) {
            return "Name contains invalid characters. Only letters, apostrophes, hyphens, and spaces are allowed.";
        }
        return true;
    }

    // Validate first and last names
    $firstNameError = validateName($newFirstName);
    $lastNameError = validateName($newLastName);

    // Validate file upload (only PDF)
    if ($document['error'] == UPLOAD_ERR_OK) {
        $allowedMimeTypes = ['application/pdf'];
        if (!in_array($document['type'], $allowedMimeTypes)) {
            $error = 'Only PDF files are allowed.';
        }
    } else {
        $error = 'Document upload error.';
    }

    if ($firstNameError === true && $lastNameError === true && $error === '') {
        // Proceed with file upload and email sending
        $uploadDir = 'documents/';
        $uploadFile = $uploadDir . basename($document['name']);

        // Move the uploaded file
        if (move_uploaded_file($document['tmp_name'], $uploadFile)) {
            // Prepare PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.mail.yahoo.com'; // Yahoo SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'dgandhi50@yahoo.com'; // Your Yahoo address
                $mail->Password = 'hkrnqbzyizzxtcsi'; // Your Yahoo App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
                $mail->Port = 587; // TCP port to connect to

                // Recipients
                $mail->setFrom('dgandhi50@yahoo.com', 'Name Change Request'); // Fixed From address
                $mail->addAddress('dgandhi50@yahoo.com'); // Receiver email
                $mail->addReplyTo($userEmail, 'User Name'); // Reply-To header with user's email address

                // Content
                $mail->isHTML(false);
                $mail->Subject = 'Name Change Request';
                $mail->Body    = "User ID: $userId\n\n"
                . "User Email: $userEmail\n\n" // Add user's email to the message
                . "New First Name: $newFirstName\n"
                . "New Last Name: $newLastName\n"
                . "Reason for Change:\n$reason\n\n"
                . "Document: $uploadFile";

                // Attachments
                $mail->addAttachment($uploadFile);

                $mail->send();
                $_SESSION['successMessage'] = 'Request sent successfully!';

                // Redirect to prevent form resubmission
                header("Location: request_name_change.php");
                exit();
            } catch (Exception $e) {
                $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        } else {
            $error = 'Failed to upload document. Please try again.';
        }
    } else {
        $error = $firstNameError !== true ? $firstNameError : ($lastNameError !== true ? $lastNameError : $error);
    }
}

// Retrieve success message from session
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Name Change</title>
    <style>
        /* Basic reset for margins and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            padding: 20px;
        }

        /* Content container */
        .content {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding-top:30px;
            margin-top:30px;
        }

        /* Header styling */
        h1 {
            color: black; /* Change header color to black */
            margin-bottom: 20px;
        }

        /* Form styling */
        .update-form {
            display: flex;
            flex-direction: column;
        }

        .update-form label {
            font-weight: bold;
            margin-bottom: 5px;
            color: black; /* Change label color to black */
        }

        .update-form input[type="text"],
        .update-form textarea,
        .update-form input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: black; /* Change input and textarea text color to black */
        }

        .update-form textarea {
            resize: vertical;
        }

        .update-form button,
        .cancel-button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px; /* Space between buttons */
            margin-top: 10px;
            
        }

        .update-form button {
            background-color: #5cb85c;
            color: white;
        }

        .update-form button:hover {
            background-color: #4cae4c;
        }

        .cancel-button {
            background-color: #d9534f; /* Red background for cancel button */
            color: white;
            margin-top: 10px;
            text-align: center;
        }

        .cancel-button:hover {
            background-color: #c9302c;
        }

        /* Error and success messages */
        .error-message,
        .success-message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: black; /* Change error and success message text color to black */
        }

        .error-message {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .update-form input[type="text"],
            .update-form textarea,
            .update-form input[type="file"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="content-inner">
            <h1>Request Name Change</h1>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <p><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($successMessage): ?>
                <div class="success-message">
                    <p><?php echo htmlspecialchars($successMessage); ?></p>
                </div>
            <?php endif; ?>
            
            <form class="update-form" method="post" enctype="multipart/form-data">
                <label for="first_name">New First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
                
                <label for="last_name">New Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
                
                <label for="reason">Reason for Change:</label>
                <textarea name="reason" id="reason" rows="4" required></textarea>
                
                <label for="document">Upload Document:</label>
                <input type="file" name="document" id="document" accept=".pdf" required>
                
                <button type="submit">Submit Request</button>
                <a href="certificate_details.php" class="cancel-button">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
