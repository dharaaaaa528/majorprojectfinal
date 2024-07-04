<?php
// Database connection
$servername = "localhost"; // Replace with your MySQL server host
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "majorproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form inputs
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Vulnerable SQL query allowing batched statements
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password';";

    // Execute the query
    if ($conn->multi_query($sql)) {
        $result .= "<h2>SQL Injection - Batched SQL Statements</h2><br>";
        $result .= "<p>Injected SQL: <code>$sql</code></p><br>";

        do {
            if ($query_result = $conn->store_result()) {
                if ($query_result->num_rows > 0) {
                    $result .= "<table border='1'><tr><th>ID</th><th>Username</th><th>Password</th></tr>";
                    while ($row = $query_result->fetch_assoc()) {
                        $result .= "<tr><td>" . $row["id"] . "</td><td>" . $row["username"] . "</td><td>" . $row["password"] . "</td></tr>";
                    }
                    $result .= "</table>";
                } else {
                    $result .= "<p>No records found for the injected query.</p>";
                }
                $query_result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    } else {
        // Display error message if query execution fails
        $result = "Error: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection - Batched SQL Statements</title>
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
            background-image: url('background.jpg');
            background-size: cover; 
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: black;
            padding: 0;
        }

        html, body {
            height: 100%;
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
        <h1>SQL Injection - Batched SQL Statements</h1>
        <form action="sqltry3.php" method="post">
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
