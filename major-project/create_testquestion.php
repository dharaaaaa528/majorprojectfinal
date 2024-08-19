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
</body>
</html>
