<?php
include('config.php'); // Ensure this file exists and is correctly referenced

session_start();

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $phoneno = trim($_POST['phoneno']);

    // Validate input
    if (empty($username) || empty($phoneno)) {
        $error_message = 'Please fill in both fields.';
    } else {
        $stmt = $conn->prepare("SELECT userid FROM userinfo WHERE username = ? AND phoneno = ?");
        $stmt->bind_param("ss", $username, $phoneno);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['reset_username'] = $username;
            $_SESSION['reset_phoneno'] = $phoneno;
            header("Location: reset_password.php");
            exit();
        } else {
            $error_message = 'Invalid username or phone number.';
        }

        $stmt->close();
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
            <button type="submit" name="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>