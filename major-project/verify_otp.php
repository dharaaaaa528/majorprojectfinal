<?php
require_once 'config.php';
session_start();

// Set the default timezone to Singapore
date_default_timezone_set('Asia/Singapore');

// Handle OTP verification
if (isset($_POST["verify"])) {
    $entered_otp = trim($_POST["otp"]);

    // Check if entered OTP matches the generated OTP
    if ($entered_otp == $_SESSION['otp']) {
        // Insert new user into the database
        $insertQuery = $conn->prepare("INSERT INTO userinfo (username, password, email, phoneno, created_at) VALUES (?, ?, ?, ?, ?)");
        if ($insertQuery === false) {
            die("MySQL prepare statement error (insert): " . $conn->error);
        }

        $insertQuery->bind_param("sssss", $_SESSION['username'], $_SESSION['password'], $_SESSION['email'], $_SESSION['phoneno'], $_SESSION['created_at']);
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Verify OTP</h1>
        <form action="verify_otp.php" method="post" autocomplete="off">
            <div>
                <label for="otp">Enter OTP:</label>
                <input type="text" name="otp" id="otp" required>
            </div>
            <button type="submit" name="verify">Verify</button>
        </form>
    </div>
</body>
</html>
