<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch quiz ID for Technique 1 (Assuming quiz ID 2 for Technique 1)
$quizId = 1; // Update with your actual quiz ID for Technique 1

// Fetch quiz questions with options
$sql = "SELECT id, question, option1, option2, option3, option4 FROM quiz_questions WHERE quiz_id = ?";
$questions = [];
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $stmt->bind_result($questionId, $question, $option1, $option2, $option3, $option4);

    while ($stmt->fetch()) {
        $questions[] = [
            'id' => $questionId,
            'text' => $question,
            'options' => [$option1, $option2, $option3, $option4]
        ];
    }
    $stmt->close();
} else {
    echo "Error fetching quiz questions: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Technique 1 Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            text-align: center;
            margin-top: 50px;
        }
        .quiz-container {
            width: 60%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .question {
            margin-bottom: 20px;
        }
        .question-text {
            font-weight: bold;
        }
        .options {
            margin-top: 10px;
        }
        .option {
            margin-bottom: 10px;
        }
        .option input {
            margin-right: 10px;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h1>SQL Technique 1 Quiz</h1>
        <form action="submit_quiz1.php" method="POST">
            <?php foreach ($questions as $question): ?>
                <div class="question">
                    <div class="question-text"><?php echo $question['text']; ?></div>
                    <div class="options">
                        <?php foreach ($question['options'] as $key => $option): ?>
                            <label class="option"><input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $key + 1; ?>"> <?php echo $option; ?></label><br>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="quiz_id" value="<?php echo $quizId; ?>">
            <input type="submit" class="submit-btn" value="Submit Quiz">
        </form>
    </div>
</body>
</html>
