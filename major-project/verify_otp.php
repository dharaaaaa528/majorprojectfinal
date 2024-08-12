<?php
require_once 'config.php';
require 'vendor/autoload.php'; // Include Composer autoload
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Set the default timezone to Singapore
date_default_timezone_set('Asia/Singapore');

// Handle OTP verification
if (isset($_POST["verify"])) {
    $entered_otp = trim($_POST["otp"]);
    $currentTime = time();
    $otpGeneratedTime = isset($_SESSION['otp_generated_at']) ? $_SESSION['otp_generated_at'] : 0;
    $otp = trim(isset($_SESSION['otp']) ? $_SESSION['otp'] : '');

    // Check if OTP is expired (3 minutes = 180 seconds)
    if (($currentTime - $otpGeneratedTime) > 180) {
        echo "<script>alert('OTP has expired. Please request a new one.');</script>";
        unset($_SESSION['otp']);
        unset($_SESSION['otp_generated_at']);
    } elseif ($entered_otp === $otp) {
        // Insert new user into the database
        $insertQuery = $conn->prepare("INSERT INTO userinfo (first_name, last_name, username, email, password, phoneno, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($insertQuery === false) {
            die("MySQL prepare statement error (insert): " . $conn->error);
        }
        
        $insertQuery->bind_param(
            "sssssss",
            $_SESSION['first_name'],
            $_SESSION['last_name'],
            $_SESSION['username'],
            $_SESSION['email'],
            $_SESSION['password'],
            $_SESSION['phoneno'],
            $_SESSION['created_at']
        );
        if ($insertQuery->execute()) {
            // Registration successful, get the user id
            $userId = $insertQuery->insert_id;
            
            // Set session variables
            $_SESSION["login"] = true;
            $_SESSION["userid"] = $userId;
            $_SESSION["username"] = $_SESSION['username'];
            $_SESSION["email"] = $_SESSION['email'];
            
            // Clear sensitive session data
            unset($_SESSION['otp']);
            unset($_SESSION['first_name']);
            unset($_SESSION['last_name']);
            unset($_SESSION['password']);
            unset($_SESSION['phoneno']);
            unset($_SESSION['created_at']);
            
            // Redirect to usermain.php
            header("Location: usermain.php");
            exit();
        } else {
            die("Error executing query: " . $insertQuery->error);
        }
        
        $insertQuery->close();
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}

// Handle OTP resending
if (isset($_POST["resend"])) {
    // Generate new OTP
    $otp = rand(100000, 999999);

    // Store new OTP and generation time in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_generated_at'] = time();

    // Send new OTP to user's email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->SMTPDebug = 0; // Set to 0 for no debugging output, 2 for detailed debugging output
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.yahoo.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dgandhi50@yahoo.com'; 
        $mail->Password = 'hkrnqbzyizzxtcsi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('dgandhi50@yahoo.com', 'dhara gandhi');
        $mail->addAddress($_SESSION['email'], $_SESSION['username']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your new OTP code is <b>$otp</b>. Please note that this OTP will expire in 3 minutes.";
        $mail->AltBody = "Your new OTP code is $otp. Please note that this OTP will expire in 3 minutes.";

        $mail->send();
        echo "<script>alert('New OTP has been sent to your email.');</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 0;
            height: 100vh;
            align-items: center;
            justify-content: center;
            display: flex;
        }

        html, body {
            height: 100%;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        .container h1 {
            margin-bottom: 20px;
        }

        .container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container form div {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .container label {
            width: 120px;
            text-align: right;
            margin-right: 10px;
        }

        .container input {
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #6200ea;
            color: #fff;
            cursor: pointer;
            margin: 5px;
        }

        .container button:hover {
            background-color: #3700b3;
        }

        #resendButton:disabled {
            background-color: grey;
            cursor: not-allowed;
        }
    </style>
    <script>
        let countdownTime = 30;

        function startTimer() {
            const resendButton = document.getElementById('resendButton');
            const timerDisplay = document.getElementById('timerDisplay');

            resendButton.disabled = true;

            const countdownInterval = setInterval(() => {
                countdownTime--;
                timerDisplay.textContent = `(${countdownTime}s)`;

                if (countdownTime <= 0) {
                    clearInterval(countdownInterval);
                    resendButton.disabled = false;
                    timerDisplay.textContent = '';
                }
            }, 1000);
        }

        window.onload = startTimer;
    </script>
</head>
<body>
    <div class="container">
        <h1>Verify OTP (sent to your email)</h1>
        <form action="verify_otp.php" method="post" autocomplete="off">
            <div>
                <label for="otp">Enter OTP:</label>
                <input type="text" name="otp" id="otp" required>
            </div>
            <button type="submit" name="verify">Verify</button>
        </form>
        <form action="verify_otp.php" method="post" autocomplete="off" style="margin-top: 10px;">
            <button type="submit" name="resend" id="resendButton">Resend OTP <span id="timerDisplay"></span></button>
        </form>
    </div>
</body>
</html>

