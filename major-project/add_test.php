<?php
require_once 'config.php';
require_once 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables for form fields
$test_name = '';
$category = '';
$question_type = ''; // MCQ or Open-ended
$created_by_id = $_SESSION['userid']; // Get the user ID from session

// Define categories and question types
$categories = ['SQL', 'XSS'];
$question_types = ['MCQ', 'Open-ended'];

// Fetch the username associated with the user ID
$username = '';
$sql = "SELECT username FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $created_by_id);
    if ($stmt->execute()) {
        $stmt->bind_result($username);
        $stmt->fetch();
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['test_name'];
    $category = $_POST['category'];
    $question_type = $_POST['question_type'];
    $created_at = $_POST['created_at'];
    $created_by_id = $_POST['created_by']; // Still use user ID in the database
    
    $has_open_ended = ($question_type == 'Open-ended') ? 1 : 0;
    $level = 'basic'; // Default value
    
    $sql = "INSERT INTO tests (name, category, level, has_open_ended, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssisis', $name, $category, $level, $has_open_ended, $created_at, $created_by_id);
        
        if ($stmt->execute()) {
            $success_message = 'Test created successfully' . htmlspecialchars($username) . '!';
            // Clear the form fields
            $test_name = '';
            $category = '';
            $question_type = '';
        } else {
            $error_message = 'Error: ' . $conn->error;
        }
        
        $stmt->close();
    } else {
        $error_message = 'Error: ' . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Test</title>
    <style>
        /* Existing CSS styles */
        :root {
            --container-background-light: #ffffff;
            --container-background-dark: #2c2c2c;
            --text-color-light: black;
            --text-color-dark: #ffffff;
            --button-background-light: #007bff;
            --button-background-dark: #0056b3;
            --button-text-light: #ffffff;
            --button-text-dark: #ffffff;
            --success-background: #d4edda;
            --success-text: #155724;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            background-color: var(--container-background-light);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            transition: background-color 0.3s, color 0.3s;
            margin: auto;
            color: var(--text-color-light);
        }

        .dark-mode .container {
            background-color: var(--container-background-dark);
            color: var(--text-color-dark);
        }

        .dark-mode body {
            color: var(--text-color-dark);
            background-color: var(--container-background-dark);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: inherit;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: inherit;
        }

        input[type="text"],
        select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--button-background-light);
            color: var(--button-text-light);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
         input[type="button"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--button-background-light);
            color: var(--button-text-light);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: var(--button-background-dark);
        }
         input[type="button"]:hover {
            background-color: var(--button-background-dark);
        }
        

        .success-message {
            background-color: var(--success-background);
            color: var(--success-text);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .back-button-link {
            text-decoration: none;
        }

        .back-button {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            text-align: center;
            width: 200px;
            border: none;
            border-radius: 2em;
            height: 56px;
            font-family: sans-serif;
            color: #000000;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s;
            z-index: 1;
        }

        .back-button:hover {
            background-color: #f4f4f5;
        }
        .icon {
            background-color: #4caf50;
            border-radius: 1em;
            height: 40px;
            width: 25%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 5%;
            top: 50%;
            transform: translateY(-50%);
            transition: width 0.5s;
            z-index: 2;
        }

        .back-button:hover .icon {
            width: 90%;
        }

        .icon svg {
            fill: #000000;
        }
         .slider:before {
            position: absolute;
            content: "";
            height: var(--size-of-icon);
            width: var(--size-of-icon);
            border-radius: 20px;
            left: var(--slider-offset);
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(40deg, #ff0080, #ff8c00 70%);
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #303136;
        }

        input:checked + .slider:before {
            left: calc(100% - (var(--size-of-icon) + var(--slider-offset)));
            background: #303136;
            box-shadow: inset -3px -2px 5px -2px #8983f7, inset -10px -4px 0 0 #a3dafb;
        }
        
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Create Test</h2>
        <?php if (isset($success_message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="add_test.php" method="post">
            <div class="form-group">
                <label for="test_name">Test Name</label>
                <input type="text" id="test_name" name="test_name" value="<?php echo htmlspecialchars($test_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($category == $cat) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="question_type">Question Type</label>
                <select id="question_type" name="question_type" required>
                    <?php foreach ($question_types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($question_type == $type) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
            <input type="hidden" name="created_by" value="<?php echo htmlspecialchars($created_by_id); ?>">
            <input type="submit" value="Create Test">
           <button class="back-button" type="button" onclick="window.history.back();">
    <div class="icon">
        <svg width="25px" height="25px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
            <path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"></path>
            <path fill="#000000" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"></path>
        </svg>
    </div>
</button>
           
        </form>
    </div>
</body>
</html>
