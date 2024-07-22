<?php
require_once 'checkfile.php'; // Adjust paths as per your project structure
require_once 'config.php';
require_once 'header.php';
// Adjust paths as per your project structure

$query = "";    // Initialize $query variable
$result = "";   // Initialize $result variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["query"])) {
        $query = $_POST["query"];

        // Execute the query
        $res = $conn->query($query);

        if ($res) {
            $result = "<table border='1'><tr><th>ID</th><th>Username</th><th>Password</th></tr>";
            while ($row = $res->fetch_assoc()) {
                $result .= "<tr><td>" . $row["id"] . "</td><td>" . $row["username"] . "</td><td>" . $row["password"] . "</td></tr>";
            }
            $result .= "</table>";
        } else {
            $result = "Error executing query: " . $conn->error;
        }
    } else {
        $result = "Query cannot be empty!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Demo</title>
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
            z-index: 1; /* Ensure the container is above the background image */
            color: black;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center elements inside the form */
        }
        .container textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
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
            background-color: #000000;
            border: 1px solid #000000;
            border-radius: 5px;
            color: white; /* Changed font color to white */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            color: white; /* Ensure table text color is also white */
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .back-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            text-align: center;v
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SQL Injection Demo</h1>
        <p>Enter an SQL query into the text area below to see how SQL Injection works. For example, try entering:</p>
        <p><strong>SELECT * FROM users WHERE '1'='1';</strong></p>
        <form action="sqltry1.php" method="post">
            <textarea name="query" placeholder="Enter your SQL query here"><?php echo htmlspecialchars($query); ?></textarea><br>
            <button type="submit">Run SQL</button>
        </form>
        <div class="result">
            <?php echo isset($result) ? $result : ''; ?>
        </div>
    </div>
    <a href="contentpage.php" class="back-button">Go Back to Content</a>
</body>
</html>
