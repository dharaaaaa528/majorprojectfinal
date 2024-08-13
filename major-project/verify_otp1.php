<?php
include('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to the Composer autoload file

session_start();

// Function to send OTP email
function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.yahoo.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dgandhi50@yahoo.com'; // Your email
        $mail->Password = 'hkrnqbzyizzxtcsi'; // Your email password or app password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption
        $mail->Port = 587;

        $mail->setFrom('dgandhi50@yahoo.com', 'dhara gandhi');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body = "Your OTP for password reset is: <b>$otp</b>. It is valid for 1 minute";

        $mail->send();
    } catch (Exception $e) {
        return 'Error sending OTP email: ' . $mail->ErrorInfo;
    }
    return null;
}

if (isset($_POST['submit'])) {
    $otp = trim($_POST['otp']);

    if ($otp == $_SESSION['otp'] && time() <= $_SESSION['otp_expiry']) {
        header("Location: reset_password.php");
        exit();
    } else {
        $error_message = 'Invalid or expired OTP.';
    }
}

if (isset($_POST['resend'])) {
    // Check if the reset_email session variable is set
    if (isset($_SESSION['reset_email'])) {
        // Check the cooldown time
        if (isset($_SESSION['last_otp_request']) && time() - $_SESSION['last_otp_request'] < 30) {
            $error_message = 'Please wait before requesting a new OTP.';
        } else {
            // Regenerate OTP and expiry time
            $otp = rand(100000, 999999); // Example OTP generation
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 60; // 1 minute expiry time
            $_SESSION['last_otp_request'] = time(); // Update last OTP request time

            // Send OTP via email
            $email = $_SESSION['reset_email']; // Ensure this session variable is set properly
            $error_message = sendOtpEmail($email, $otp);

            if (!$error_message) {
                $success_message = 'OTP has been resent. Please check your email or SMS.';
            } else {
                $error_message = 'Error resending OTP: ' . $error_message;
            }
        }
    } else {
        $error_message = 'Email address not found. Please request OTP again.';
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
            background-color: #121212;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            background-color: #1e1e1e;
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
            margin-bottom: 10px;
        }
        .container label {
            width: 120px;
            text-align: right;
            margin-right: 15px;
            color: #ffffff;
        }
        .container input {
            flex: 1;
            padding: 8px;
            border: 1px solid #555;
            border-radius: 10px;
            background-color: #333;
            color: #fff;
        }
        .container button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            background-color: #6200ea;
            color: #fff;
            cursor: pointer;
            width: auto;
            margin-top: 10px;
        }
        .container button:hover {
            background-color: #3700b3;
        }
        .message {
            color: #00ff00; /* Color for success message */
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        #resend-button {
            position: relative;
        }
        #countdown {
            color: #ffffff;
            font-weight: bold;
            margin-top: 10px;
            display: none;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var resendButton = document.getElementById('resend-button');
            var countdown = document.getElementById('countdown');
            var lastRequestTime = <?php echo isset($_SESSION['last_otp_request']) ? $_SESSION['last_otp_request'] : '0'; ?>;
            var currentTime = Math.floor(Date.now() / 1000);
            var remainingTime = Math.max(0, 30 - (currentTime - lastRequestTime));

            if (remainingTime > 0) {
                resendButton.disabled = true;
                countdown.textContent = 'You can request a new OTP in ' + remainingTime + ' seconds';
                countdown.style.display = 'block';

                var interval = setInterval(function() {
                    remainingTime--;
                    if (remainingTime <= 0) {
                        clearInterval(interval);
                        resendButton.disabled = false;
                        countdown.textContent = '';
                        countdown.style.display = 'none';
                    } else {
                        countdown.textContent = 'You can request a new OTP in ' + remainingTime + ' seconds';
                    }
                }, 1000);
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Verify OTP</h1>
        <?php if (isset($error_message)) { echo '<p class="error">' . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . '</p>'; } ?>
        <?php if (isset($success_message)) { echo '<p class="message">' . htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') . '</p>'; } ?>
        <form action="verify_otp1.php" method="post">
            <div>
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" required>
            </div>
            <button type="submit" name="submit">Verify OTP</button>
        </form>
        <form action="verify_otp1.php" method="post" style="margin-top: 10px;">
            <button type="submit" id="resend-button" name="resend">Resend OTP</button>
            <div id="countdown"></div>
        </form>
    </div>
</body>
</html>
