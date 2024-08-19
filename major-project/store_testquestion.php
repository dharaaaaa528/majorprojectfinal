<?php
// Database connection setup
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_id = $_POST['test_id'];
    $question_text = $_POST['question_text'];
    $expected_keywords = isset($_POST['expected_keywords']) ? trim($_POST['expected_keywords']) : NULL;
    
    // Fetch the test type
    $stmt = $conn->prepare("SELECT has_open_ended FROM tests WHERE test_id = ?");
    $stmt->bind_param("i", $test_id);
    $stmt->execute();
    $stmt->bind_result($has_open_ended);
    $stmt->fetch();
    $stmt->close();
    
    // Determine question type
    $question_type = $has_open_ended ? 'open_ended' : 'mcq';
    
    // If the question type is MCQ, set expected_keywords to NULL
    if ($question_type == 'mcq') {
        $expected_keywords = NULL;
    }
    
    // Insert into test_questions
    $stmt = $conn->prepare("INSERT INTO test_questions (test_id, question_text, question_type, expected_keywords) VALUES (?, ?, ?, ?)");
    
    // Bind parameters
    $stmt->bind_param("isss", $test_id, $question_text, $question_type, $expected_keywords);
    
    if ($stmt->execute()) {
        $question_id = $stmt->insert_id; // Get the last inserted question_id
        
        // If MCQ, insert options
        if ($question_type == 'mcq') {
            $option_1 = $_POST['option_1'];
            $option_2 = $_POST['option_2'];
            $option_3 = $_POST['option_3'];
            $option_4 = $_POST['option_4'];
            $correct_option = $_POST['correct_option'];
            
            $stmt = $conn->prepare("INSERT INTO test_options (question_id, test_id, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssss", $question_id, $test_id, $option_1, $option_2, $option_3, $option_4, $correct_option);
            if ($stmt->execute()) {
                echo "<p>MCQ question and options added successfully.</p>";
            } else {
                echo "<p>Error adding options: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Open-ended question added successfully.</p>";
        }
    } else {
        echo "<p>Error adding question: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
}

$conn->close();
