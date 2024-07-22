<?php
require_once 'server.php'; // Ensure this includes your database connection file

session_start();

// Set the default timezone to Singapore
date_default_timezone_set('Asia/Singapore');

// Debugging: Check if user is already logged in
if (isset($_SESSION["login"]) && $_SESSION["login"]) {
    header("Location: usermain.php");
    exit();
}

// Check for account lockout
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
    $remainingTime = $_SESSION['lockout_time'] - time();
    if ($remainingTime > 0) {
        $error_message = 'Too many failed login attempts. Please try again after ' . $remainingTime . ' seconds.';
        exit();
    } else {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['lockout_time']);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameemail = $_POST["usernameemail"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM userinfo WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameemail, $usernameemail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["userid"] = $row["userid"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["password"] = $password; // Store the plain text password temporarily
            $_SESSION["role"] = $row["role"];
            session_regenerate_id(true); // Regenerate session ID for security
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);
            
            // Update last login time
            $lastLogin = date('Y-m-d H:i:s'); // Current datetime in Singapore time
            $updateStmt = $conn->prepare("UPDATE userinfo SET last_login = ? WHERE userid = ?");
            $updateStmt->bind_param("si", $lastLogin, $row["userid"]);
            $updateStmt->execute();
            $updateStmt->close();
            
            // Redirect to main page after successful login
            header("Location: usermain.php");
            exit();
        } else {
            $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
            $error_message = 'Wrong Password';
        }
    } else {
        $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
        $error_message = 'User Not Registered';
    }

    // Lock the user out for 10 minutes after 5 failed attempts
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['lockout_time'] = time() + 600; // Lockout for 600 seconds (10 minutes)
        $error_message = 'Too many failed login attempts. Account locked. Please try again after 10 minutes.';
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
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
            background-size: cover; /* Makes the image cover the entire page */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-position: center; /* Centers the image */
            background-attachment: fixed; /* Fixes the image while scrolling */
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
    
        }
        
        html, body {
        height: 100%;
        }
        
        .container {
            background-color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .container h1 {
            margin-bottom: 20px;
            color: white;
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
            color: white;
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
        .container a {
            color: #ff6ff9;
            text-decoration: none;
            margin-top: 10px;
        }
        .container a:hover {
            color: #ff6ff9; /* Change the color when hovered over */
        }
        
        .google-login-btn .icon svg {
            fill: white; /* Change the color of the Google icon */
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
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
        <form action="" method="post">
            <div>
                <label for="usernameemail">Username or Email:</label>
                <input type="text" id="usernameemail" name="usernameemail" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                 <span class="toggle-password" onclick="togglePasswordVisibility()">
                    <img src="eye.png" alt="Toggle password visibility">
                </span>
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
        <br>
        <a href="registration.php">Register</a><br><br>
        <a href="google-oauth.php" class="google-login-btn">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 488 512">
                    <path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/>
                </svg>
            </span>
            Login with Google
        </a>
        <br><br>
        <a href="reset_request.php">Forgot Password?</a> <!-- Forgot Password link added -->
    </div>
    <script>
    	function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var toggleButton = document.querySelector(".toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleButton.innerHTML = "<img src='eye-slash.png' alt='Hide password'>";
            } else {
                passwordField.type = "password";
                toggleButton.innerHTML = "<img src='eye.png' alt='Show password'>";
            }
        }
    </script>
</body>
</html>

