<?php
require 'vendor/autoload.php';
require_once 'config.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the default timezone to Singapore
date_default_timezone_set('Asia/Singapore');

// Handle form submission
if (isset($_POST["submit"])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $phoneno = trim($_POST["phoneno"]);

    // Validate phone number
    if (!preg_match('/^\d{8}$/', $phoneno)) {
        echo "<script>alert('Phone number must be exactly 8 digits');</script>";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\W).{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and include at least one uppercase letter and one special character');</script>";
    } elseif (strlen($username) < 5) {
        echo "<script>alert('Username must be at least 5 characters long');</script>";
    } else {
        // Check for duplicate entries
        $duplicate = $conn->prepare("SELECT * FROM userinfo WHERE username = ? OR email = ? OR phoneno = ?");
        if ($duplicate === false) {
            die("MySQL prepare statement error (duplicate): " . $conn->error);
        }
        $duplicate->bind_param("sss", $username, $email, $phoneno);
        $duplicate->execute();
        $duplicate->store_result();

        if ($duplicate->num_rows > 0) {
            echo "<script>alert('Username or Email or PhoneNo Is Already Taken');</script>";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Store data in session variables
            $_SESSION['otp'] = $otp;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['phoneno'] = $phoneno;
            $_SESSION['created_at'] = date('Y-m-d H:i:s'); // Get current datetime in Singapore time

            // Send OTP to user's email
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->SMTPDebug = 2; // Set to 0 for no debugging output, 2 for detailed debugging output
                $mail->isSMTP();
                $mail->Host = 'smtp.mail.yahoo.com';
                $mail->SMTPAuth = true;
                
                // Replace with your actual email and app-specific password
                $mail->Username = 'dgandhi50@yahoo.com'; 
                $mail->Password = 'hkrnqbzyizzxtcsi';
                
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('dgandhi50@yahoo.com', 'dhara gandhi');
                $mail->addAddress($email, $username);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body    = "Your OTP code is <b>$otp</b>";
                $mail->AltBody = "Your OTP code is $otp";

                $mail->send();
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        $duplicate->close();
    }
    $conn->close();
}
?>

