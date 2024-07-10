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
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff; /* Ensure text is visible on dark background */
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            border-radius: 10px;
            margin-top: 50px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .question {
            margin-bottom: 20px;
        }
        .question p {
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        .question-navigation {
            text-align: center;
            margin-top: 20px;
        }
        .question-navigation button {
            margin: 5px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <?php include 'topnav.php'; ?> <!-- Include topnav.php -->

    <div class="container">
        <h1>SQL INJECTION QUIZ</h1>
        <form action="submit_quiz.php" method="post">
            <?php
            $question_numbers = [];
            if ($result->num_rows > 0) {
                $i = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<div class='question' id='question_$i'>";
                    echo "<p>Question $i: " . $row["question_text"] . "</p>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='A'>" . $row["option_a"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='B'>" . $row["option_b"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='C'>" . $row["option_c"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='D'>" . $row["option_d"] . "</label><br>";
                    echo "</div>";
                    $question_numbers[] = $i;
                    $i++;
                }
            } else {
                echo "No questions available";
            }
            ?>
            <div class="question-navigation">
                <?php
                foreach ($question_numbers as $number) {
                    echo "<button type='button' onclick='scrollToQuestion($number)'>Question $number</button>";
                }
                ?>
            </div>
            <input type="submit" value="Submit" class="submit-btn">
        </form>
    </div>

    <script>
        function scrollToQuestion(questionNumber) {
            document.getElementById('question_' + questionNumber).scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>
