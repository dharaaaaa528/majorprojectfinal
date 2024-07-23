<?php
// Set the time zone to Singapore
date_default_timezone_set('Asia/Singapore');

// Database connection setup (update with your database details)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "majorproject";

// Start session to access session variables
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if quiz_id is set and is numeric
if (isset($_POST['quiz_id']) && is_numeric($_POST['quiz_id'])) {
    $quiz_id = intval($_POST['quiz_id']);
} else {
    die("Invalid Quiz ID.");
}

// Get the current user's username from the session
if (isset($_SESSION['username'])) {
    $created_by = $_SESSION['username'];
} else {
    die("User not logged in.");
}

// Get the current timestamp
$created_date = date("Y-m-d H:i:s");

// Check if 'questions' array is set and is an array
if (isset($_POST['questions']) && is_array($_POST['questions'])) {
    foreach ($_POST['questions'] as $index => $questionData) {
        // Initialize question variables with a default empty string
        $question = isset($questionData['question']) ? $questionData['question'] : '';
        $option1 = isset($questionData['option1']) ? $questionData['option1'] : '';
        $option2 = isset($questionData['option2']) ? $questionData['option2'] : '';
        $option3 = isset($questionData['option3']) ? $questionData['option3'] : '';
        $option4 = isset($questionData['option4']) ? $questionData['option4'] : '';
        $correct_option = isset($questionData['correct_option']) ? intval($questionData['correct_option']) : 0;
        
        // Validate correct_option
        if ($correct_option < 1 || $correct_option > 4) {
            die("Invalid correct option for question " . ($index + 1) . ". Please provide a number between 1 and 4.");
        }
        
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO quiz_questions (quiz_id, question, option1, option2, option3, option4, correct_option, created_by, created_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssiss", $quiz_id, $question, $option1, $option2, $option3, $option4, $correct_option, $created_by, $created_date);
        
        // Execute SQL statement
        if (!$stmt->execute()) {
            die("Error: " . $stmt->error);
        }
    }
    
    echo "Quiz questions successfully added!";
} else {
    die("No questions data received.");
}

// Close connection
$conn->close();
?>
