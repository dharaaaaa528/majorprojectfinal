<?php
require_once 'server.php';
session_start();

// Check if already logged in
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: usermain.php");
    exit();
}

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
            // Insert new user
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = $conn->prepare("INSERT INTO userinfo (username, password, email, phoneno) VALUES (?, ?, ?, ?)");
            if ($insertQuery === false) {
                die("MySQL prepare statement error (insert): " . $conn->error);
            }

            $insertQuery->bind_param("ssss", $username, $passwordHashed, $email, $phoneno);
            if ($insertQuery->execute()) {
                // Registration successful, get the user id
                $userId = $insertQuery->insert_id;

                // Set session variables
                $_SESSION["login"] = true;
                $_SESSION["userid"] = $userId;
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $email;
                $_SESSION["password"] = $password; // Store the unhashed password (not recommended for production)

                // Redirect to usermain.php
                header("Location: usermain.php");
                exit();
            } else {
                die("Error executing query: " . $insertQuery->error);
            }

            $insertQuery->close();
        }
        $duplicate->close();
        $conn->close();
    }
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
            background-size: cover; /* Makes the image cover the entire page */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-position: center; /* Centers the image */
            background-attachment: fixed; /* Fixes the image while scrolling */
        }

        .container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px; /* Adjust width as needed */
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
            display: flex; /* Use flexbox for label and input alignment */
            align-items: center; /* Center items vertically */
            justify-content: center;
            margin-bottom: 15px; /* Increased margin between input rows */
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
            color: #ff6ff9; /* Change the color of the hyperlink */
            text-decoration: none; /* Optional: remove underline */
        }
        .container a:hover {
            color: #ff6ff9; /* Change the color when hovered over */
        }
        .google-login-btn .icon svg {
            fill: white; /* Change the color of the Google icon */
        }
        .requirements {
            font-size: 12px;
            color: #bbb;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration</h1>
        <form action="registration.php" method="post" autocomplete="off">
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
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 488 512"><path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/></svg>
            </span>
            Register with Google
        </a>
    </div>
</body>
</html>


