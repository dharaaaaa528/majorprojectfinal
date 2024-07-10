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

$number_of_questions = 10; // Specify the number of questions you want to fetch
$sql = "SELECT * FROM sqlquiz1 ORDER BY RAND() LIMIT $number_of_questions";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
</head>
<body>
    <form action="submit_quiz.php" method="post">
        <?php
        if ($result->num_rows > 0) {
            $i = 1;
            while($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<p>Question $i: " . $row["question_text"] . "</p>";
                echo "<input type='radio' name='question_" . $row["id"] . "' value='A'>" . $row["option_a"] . "<br>";
                echo "<input type='radio' name='question_" . $row["id"] . "' value='B'>" . $row["option_b"] . "<br>";
                echo "<input type='radio' name='question_" . $row["id"] . "' value='C'>" . $row["option_c"] . "<br>";
                echo "<input type='radio' name='question_" . $row["id"] . "' value='D'>" . $row["option_d"] . "<br>";
                echo "</div>";
                $i++;
            }
        } else {
            echo "No questions available";
        }
        ?>
        <input type="submit" value="Submit Quiz">
    </form>
</body>
</html>
<?php
$conn->close();
?>
