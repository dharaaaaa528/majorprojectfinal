<?php
// Database configuration
$db_hostname = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_database = "majorproject";

// Create connection
$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

try {
    // Connect to the MySQL database using PDO
    $pdo = new PDO('mysql:host=' . $db_hostname . ';dbname=' . $db_database . ';charset=utf8', $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Testing Editor</title>
</head>
<body>
    <h1>XSS Testing Editor</h1>
    <form action="xss.php" method="POST">
        <label for="xss_input">Enter your script:</label><br>
        <textarea id="xss_input" name="xss_input" rows="10" cols="50"></textarea><br>
        <input type="submit" value="Test Script">
    </form>

    <?php
    if (isset($_POST['xss_input'])) {
        $xss_input = $_POST['xss_input'];
        echo "<h1>XSS Test Output</h1>";
        echo "<div>$xss_input</div>";
    } else {
        echo "No input received.";
    }
    ?>
</body>
</html>
