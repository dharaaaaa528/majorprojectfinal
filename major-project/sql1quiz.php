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
            display: flex;
        }
        .container {
            flex: 1;
            padding: 20px;
            margin-left: 250px; /* Space for the sidebar */
        }
        .sidebar {
            width: 200px;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
        }
        .sidebar h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
            background: #007bff;
            border-radius: 5px;
            text-align: center;
        }
        .sidebar ul li a:hover {
            background: #0056b3;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .question {
            margin-bottom: 20px;
        }
        .question p {
            font-size: 1.2em;
        }
        .submit-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Questions</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                $result->data_seek(0); // Reset the result pointer to the beginning
                while($row = $result->fetch_assoc()) {
                    echo "<li><a href='#question_$i'>Question $i</a></li>";
                    $i++;
                }
            }
            ?>
        </ul>
    </div>

    <div class="container">
        <h1>SQL INJECTION QUIZ</h1>
        <form action="submit_quiz.php" method="post">
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                $result->data_seek(0); // Reset the result pointer to the beginning
                while($row = $result->fetch_assoc()) {
                    echo "<div class='question' id='question_$i'>";
                    echo "<p>Question $i: " . $row["question_text"] . "</p>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='A'>" . $row["option_a"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='B'>" . $row["option_b"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='C'>" . $row["option_c"] . "</label><br>";
                    echo "<label><input type='radio' name='question_" . $row["id"] . "' value='D'>" . $row["option_d"] . "</label><br>";
                    echo "</div>";
                    $i++;
                }
            } else {
                echo "No questions available";
            }
            ?>
            <input type="submit" value="Submit" class="submit-btn">
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>
