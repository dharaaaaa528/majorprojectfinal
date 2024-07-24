<?php
include('config.php'); // Ensure this file exists and is correctly referenced

session_start();

if (isset($_POST['submit'])) {
    $otp = trim($_POST['otp']);

    if ($otp == $_SESSION['otp'] && time() <= $_SESSION['otp_expiry']) {
        header("Location: reset_password.php");
        exit();
    } else {
        $error_message = 'Invalid or expired OTP.';
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
        /* Your existing styles */
    </style>
</head>
<body>
    <div class="container">
        <h1>Verify OTP</h1>
        <?php if (isset($error_message)) { echo '<p style="color: red;">' . $error_message . '</p>'; } ?>
        <form action="verify_otp1.php" method="post">
            <div>
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" required>
            </div>
            <button type="submit" name="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>
