<?php
require_once 'config.php';

// Function to simulate getting request parameters (for demonstration)
$result = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form inputs
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Vulnerable SQL query
    $sql = "SELECT * FROM users WHERE Name='$username' AND Pass='$password'";
    
    // Execute the query
    $query_result = $conn->query($sql);
    
    if ($query_result && $query_result->num_rows > 0) {
        // Display user data if login successful
        $result = "<h2>Login Successful</h2><br><table border='1'><tr><th>ID</th><th>Name</th><th>Password</th></tr>";
        while ($row = $query_result->fetch_assoc()) {
            $result .= "<tr><td>" . $row["ID"] . "</td><td>" . $row["Name"] . "</td><td>" . $row["Pass"] . "</td></tr>";
        }
        $result .= "</table>";
    } else {
        // Display error message if login fails
        $result = "<h2>Login Failed</h2>";
    }
}

// Close connection (simulated)
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Testing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: black;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 600px;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container input[type="text"], .container input[type="password"] {
            width: 100%;
            height: 30px;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: black;
        }
        .container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #ffcccc;
            border: 1px solid #cc0000;
            border-radius: 5px;
            color: #cc0000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SQL Injection Testing</h1>
        <form action="sqltry2.php" method="post">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <button type="submit">Login</button>
        </form>
        <div class="result">
            <?php echo $result; ?>
        </div>
    </div>
</body>
</html>
