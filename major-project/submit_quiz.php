<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "majorproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    foreach ($_POST as $question_id => $user_answer) {
        // Extract only the numeric part of the question_id
        $id = substr($question_id, 9);
        $sql = "SELECT correct_option FROM sqlquiz1 WHERE id=$id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["correct_option"] == $user_answer) {
                $score++;
            }
        }
    }
    echo "Your score is: $score";
}

$conn->close();
?>
