<?php
require_once 'header.php';
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

// Fetch tests for dropdown
$sql = "SELECT test_id, name, has_open_ended FROM tests";
$result = $conn->query($sql);

// Check if tests are available
$tests = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tests[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Test Question</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

form {
    width: 100%;
}

label {
    display: block;
    margin-bottom: 10px;
    color: #333;
    font-weight: bold;
}

input[type="text"],
select {
    width: calc(100% - 22px);
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

#mcq_options,
#open_ended_options {
    margin-top: 20px;
}

#mcq_options label,
#open_ended_options label {
    margin-bottom: 5px;
    font-weight: normal;
}

#mcq_options input[type="text"],
#open_ended_options input[type="text"] {
    margin-bottom: 10px;
}

#correct_option {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
}
    
        /* Include the CSS here */
    </style>
    <script>
        function toggleOptions() {
            var testSelect = document.getElementById('test_id');
            var mcqOptions = document.getElementById('mcq_options');
            var openEndedOptions = document.getElementById('open_ended_options');
            
            var testId = testSelect.value;
            var selectedOption = testSelect.options[testSelect.selectedIndex];
            var hasOpenEnded = selectedOption.dataset.hasOpenEnded;

            if (hasOpenEnded == '1') {
                mcqOptions.style.display = 'none';
                openEndedOptions.style.display = 'block';
            } else {
                mcqOptions.style.display = 'block';
                openEndedOptions.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Create Test Question</h1>
        <form action="store_testquestion.php" method="post">
            <label for="test_id">Select Test:</label>
            <select name="test_id" id="test_id" onchange="toggleOptions()" required>
                <option value="">Select a test</option>
                <?php foreach ($tests as $test): ?>
                    <option value="<?php echo $test['test_id']; ?>" data-has-open-ended="<?php echo $test['has_open_ended']; ?>">
                        <?php echo htmlspecialchars($test['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label for="question_text">Question:</label>
            <input type="text" id="question_text" name="question_text" required>
            <br><br>

            <!-- MCQ Options -->
            <div id="mcq_options" style="display:none;">
                <label for="option_1">Option 1:</label>
                <input type="text" id="option_1" name="option_1">
                <br><br>
                <label for="option_2">Option 2:</label>
                <input type="text" id="option_2" name="option_2">
                <br><br>
                <label for="option_3">Option 3:</label>
                <input type="text" id="option_3" name="option_3">
                <br><br>
                <label for="option_4">Option 4:</label>
                <input type="text" id="option_4" name="option_4">
                <br><br>
                <label for="correct_option">Correct Option:</label>
                <select id="correct_option" name="correct_option">
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            </div>

            <!-- Open-ended Options -->
            <div id="open_ended_options" style="display:none;">
                <label for="expected_keywords">Expected Keywords:</label>
                <input type="text" id="expected_keywords" name="expected_keywords">
            </div>

            <br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
