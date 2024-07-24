<?php
session_start();
include 'server.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch user details including role and username
$stmt = $pdo->prepare('SELECT role, username FROM userinfo WHERE userid = ?');
$stmt->execute([$_SESSION['userid']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('User not found');
}

// Check if the user has the 'admin' role
$isAdmin = ($user['role'] === 'admin');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $type = $_POST['type'] ?? '';
    $technique_no = $_POST['technique_no'] ?? '';
    
    // Validate the input
    if (empty($name) || empty($type) || empty($technique_no)) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO quizzes (name, description, type, technique_no, created_by, updated_by, delete_by) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $result = $stmt->execute([$name, $description, $type, $technique_no, $user['username'], $user['username'], '']);
        
        if ($result) {
            $success = 'Content added successfully!';
        } else {
            $error = 'Failed to add quiz. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Content</title>
    <link rel="stylesheet" href="#">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Light gray background for the whole page */
            color: #000; /* Black text color for better readability */
        }
        .container {
            display: flex;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background-color: #fff; /* White background for the form container */
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #000; /* Black text color within the form */
        }
        .form-container h1 {
            margin-top: 0;
            color: #007BFF; /* Blue color for the heading */
        }
        .form-container input, .form-container textarea, .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box; /* Ensure padding is included in the element's total width and height */
        }
        .form-container button {
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 10px;
            font-weight: bold;
        }
        .message.success {
            color: #28a745; /* Green color for success messages */
        }
        .message.error {
            color: #dc3545; /* Red color for error messages */
        }
    </style>
</head>
<body>
    <?php 
    include 'header.php'; // Ensure this file includes navigation
    ?>
    <div class="container">
        <div class="form-container">
            <h1>Add New Content</h1>

            <?php if (isset($success)): ?>
                <div class="message success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="add_content.php" method="post">
                <label for="name">Content Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"></textarea>

                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="SQL">SQL</option>
                    <option value="XSS">XSS</option>
                </select>

                <label for="technique_no">Technique Number:</label>
                <input type="number" id="technique_no" name="technique_no" required>

                <button type="submit">Add Quiz</button>
            </form>
        </div>
    </div>
</body>
</html>
