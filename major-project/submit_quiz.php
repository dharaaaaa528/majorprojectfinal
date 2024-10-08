<?php
require_once 'server.php';

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$score = 0;
$total_questions = 10;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
}

// Calculate percentage score
$percentage_score = ($score / $total_questions) * 100;

// Determine pass or fail
if ($score >= 7) {
    $result_message = "Pass";
    $result_color = "green";
} else {
    $result_message = "Fail";
    $result_color = "red";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Submission</title>
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
            text-align: center;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.5em;
        }
        .score {
            font-size: 3em;
            margin: 20px 0;
            color: <?php echo $result_color; ?>;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Include topnav.php if needed -->
    
    <div class="container">
        <h1>CONGRATULATIONS!</h1>
        <p>You have successfully submitted the quiz!</p>
        <div class="score">Here is your score:<br>
            <?php echo "$score/10"; ?><br>
            Percentage: <?php echo "$percentage_score%"; ?><br>
            Result: <span style="color: <?php echo $result_color; ?>;"><?php echo $result_message; ?></span>
        </div>
        <?php
        // Check if $_SESSION['google_loggedin'] is set and redirect accordingly
        if (isset($_SESSION['google_loggedin']) && $_SESSION['google_loggedin']) {
            echo '<a href="contentpagegoogle.php" class="back-btn">Back to Content page</a>';
        } else {
            echo '<a href="contentpage.php" class="back-btn">Back to Content page</a>';
        }
        ?>
    </div>
</body>
</html>




