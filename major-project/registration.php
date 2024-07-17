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
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration</h1>
        <form action="send_otp.php" method="post" autocomplete="off">
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
                <!-- Google icon SVG -->
            </span>
            Register with Google
        </a>
    </div>
</body>
</html>



