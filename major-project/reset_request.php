<?php
include('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to the Composer autoload file

session_start();

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $phoneno = trim($_POST['phoneno']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($phoneno) || empty($email)) {
        $error_message = 'Please fill in all fields.';
    } else {
        $stmt = $conn->prepare("SELECT userid, email FROM userinfo WHERE username = ? AND phoneno = ? AND email = ?");
        $stmt->bind_param("sss", $username, $phoneno, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userid = $row['userid'];
            $email = $row['email'];

            // Store the email in session
            $_SESSION['reset_email'] = $email;

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 60; // OTP valid for 5 minutes
            $_SESSION['reset_username'] = $username;

            // Send OTP email using PHPMailer
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
                $mail->Body = "Your OTP for password reset is: <b>$otp</b>. it is valid for 1 minute";

                $mail->send();
                header("Location: verify_otp1.php");
                exit();
            } catch (Exception $e) {
                $error_message = 'Error sending OTP email: ' . $mail->ErrorInfo;
            }

            $stmt->close();
        } else {
            $error_message = 'Invalid username, phone number, or email.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
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
        /* Your existing styles */
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <?php if (isset($error_message)) { echo '<p style="color: red;">' . $error_message . '</p>'; } ?>
        <form action="reset_request.php" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="phoneno">Phone Number:</label>
                <input type="text" id="phoneno" name="phoneno" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" name="submit">Send OTP</button>
        </form>
    </div>
</body>
</html>

