<?php
include('config.php'); // Ensure this file exists and is correctly referenced

session_start();

if (!isset($_SESSION['reset_username'])) {
    header("Location: reset_request.php");
    exit();
}

if (isset($_POST['submit'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate input
    if (empty($new_password) || empty($confirm_password)) {
        $error_message = 'Please fill in both fields.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        $username = $_SESSION['reset_username'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE userinfo SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashed_password, $username);
        
        if ($stmt->execute()) {
            $success_message = 'Password has been reset successfully.';
            unset($_SESSION['reset_username']);
            unset($_SESSION['otp']);
            unset($_SESSION['otp_expiry']);
        } else {
            $error_message = 'Error resetting password.';
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password</title>
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
        <h1>Set New Password</h1>
        <?php if (isset($error_message)) { echo '<p style="color: red;">' . $error_message . '</p>'; } ?>
        <?php if (isset($success_message)) { echo '<p style="color: green;">' . $success_message . '</p>'; } ?>
        <form action="reset_password.php" method="post">
            <div>
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="submit">Set Password</button>
            <button type="button" onclick="window.location.href='login.php';">Go Back To Login Page</button>
        </form>
    </div>
</body>
</html>
