<?php
require 'vendor/autoload.php';
require_once 'config.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the default timezone to Singapore
date_default_timezone_set('Asia/Singapore');

// Function to send OTP
function sendOtp($email, $username, $otp) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->SMTPDebug = 0; // Set to 0 for no debugging output, 2 for detailed debugging output
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
        $mail->Body    = "Your OTP code is <b>$otp</b>. This code will expire in 3 minutes.";
        $mail->AltBody = "Your OTP code is $otp. This code will expire in 3 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Handle form submission
if (isset($_POST["submit"])) {
    $firstname = trim($_POST["first_name"]);
    $lastname = trim($_POST["last_name"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $phoneno = trim($_POST["phoneno"]);

    // Validate inputs
    if (!preg_match('/^\d{8}$/', $phoneno)) {
        echo "<script>alert('Phone number must be exactly 8 digits');</script>";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\W).{8,}$/', $password)) {
        echo "<script>alert('Password must be at least 8 characters long and include at least one uppercase letter and one special character');</script>";
    } elseif (strlen($username) < 5) {
        echo "<script>alert('Username must be at least 5 characters long');</script>";
    } elseif (strlen($firstname) < 2 || strlen($lastname) < 2) {
        echo "<script>alert('First name and Last name must be at least 2 characters long');</script>";
    } else {
        // Check for duplicate entries
        $duplicate = $conn->prepare("SELECT * FROM userinfo WHERE username = ? OR email = ? OR phoneno = ? OR (first_name = ? AND last_name = ?)");
        if ($duplicate === false) {
            die("MySQL prepare statement error (duplicate): " . $conn->error);
        }
        $duplicate->bind_param("sssss", $username, $email, $phoneno, $firstname, $lastname);
        $duplicate->execute();
        $duplicate->store_result();

        if ($duplicate->num_rows > 0) {
            echo "<script>alert('Username, Email, Phone Number, First Name, or Last Name is already taken');</script>";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Store data in session variables
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_generated_at'] = time(); // Store the current timestamp
            $_SESSION['first_name'] = $firstname;
            $_SESSION['last_name'] = $lastname;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['phoneno'] = $phoneno;
            $_SESSION['created_at'] = date('Y-m-d H:i:s'); // Get current datetime in Singapore time

            // Send OTP to user's email
            if (sendOtp($email, $username, $otp)) {
                header("Location: verify_otp.php");
                exit();
            } else {
                echo "<script>alert('Failed to send OTP. Please try again later.');</script>";
            }
        }
        $duplicate->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
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
        }

        .container button:hover {
            background-color: #3700b3;
        }

        .container a {
            color: #ff6ff9;
            text-decoration: none;
        }

        .container a:hover {
            color: #ff6ff9;
        }

        .requirements {
            font-size: 12px;
            color: #bbb;
            margin-top: -10px;
            margin-bottom: 10px;
        }
        .toggle-password {
            position: relative;
            margin-left: 0px; /* Adjust position as needed */
            cursor: pointer;
            user-select: none;
        }
        
        .toggle-password img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px; /* Adjust icon size */
            height: auto;
            margin-left: -15px;
            margin-top: -5px;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration</h1>
        <form action="" method="post" autocomplete="off">
            <div>
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" required minlength="2">
            </div>
            <div>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required minlength="2">
            </div>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required minlength="5">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required pattern="(?=.*[A-Z])(?=.*\W).{8,}" title="Password must be at least 8 characters long and include at least one uppercase letter and one special character">
                <span class="toggle-password" onclick="togglePasswordVisibility()">
                    <img src="eye.png" alt="Toggle password visibility">
                </span>
            </div>
            <div>
                <label for="phoneno">Phone Number:</label>
                <input type="text" name="phoneno" id="phoneno" required pattern="\d{8}" title="Phone number must be exactly 8 digits">
            </div>
            <button type="submit" name="submit">Register</button>
        </form>
        <br>
        <a href="login.php">Login</a> <br><br>
        <a href="google-oauth.php" class="google-login-btn">
            <span class="icon">
                <!-- Google icon SVG -->
            </span>
            Register with Google
        </a>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const passwordToggleIcon = document.querySelector('.toggle-password img');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggleIcon.src = 'eye-off.png'; // Update with path to your eye-off icon
            } else {
                passwordField.type = 'password';
                passwordToggleIcon.src = 'eye.png'; // Update with path to your eye icon
            }
        }
    </script>
</body>
</html>


