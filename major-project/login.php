<?php
require_once 'server.php'; // Ensure this includes your database connection file

session_start();

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

// Debugging: Form submission check
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameemail = $_POST["usernameemail"];
    $password = $_POST["password"];

    // Debugging: Display input values
    echo "Username/Email: $usernameemail<br>";
    echo "Password: $password<br>";

    $stmt = $conn->prepare("SELECT * FROM userinfo WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameemail, $usernameemail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging: Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Debugging: Verify password
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["userid"] = $row["userid"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["email"] = $row["email"];
            session_regenerate_id(true); // Regenerate session ID for security
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);
            
            // Debugging: Before redirection
            echo "Login successful. Redirecting to usermain.php...";
            header("Location: usermain.php");
            exit();
        } else {
            $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
            $error_message = 'Wrong Password';
            echo $error_message;
        }
    } else {
        $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
        $error_message = 'User Not Registered';
        echo $error_message;
    }

    // Lock the user out for 10 minutes after 5 failed attempts
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['lockout_time'] = time() + 600; // Lockout for 600 seconds (10 minutes)
        $error_message = 'Too many failed login attempts. Account locked. Please try again after 10 minutes.';
        echo $error_message;
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
        .container a {
            color: #bb86fc;
            text-decoration: none;
            margin-top: 10px;
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
</body>
</html>

