<?php
session_start();
ob_start();
include 'server.php';
include 'header.php'; // Ensure this file includes the database connection
include 'check_completion.php';
require_once 'sessiontimeout.php';

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch user details including role
$stmt = $pdo->prepare('SELECT role FROM userinfo WHERE userid = ?');
$stmt->execute([$_SESSION['userid']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('User not found');
}

// Check if the user has the 'admin' role
$isAdmin = ($user['role'] === 'admin');

// Fetch only SQL quizzes from the database
$query = $pdo->prepare('SELECT * FROM quizzes WHERE type = ?');
$query->execute(['SQL']);
$quizzes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inj3ctPractice</title>
    <link rel="stylesheet" href="#">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Light gray background for the whole page */
        }
        .container {
            display: flex;
            min-height: 100vh; /* Ensure the container spans the full height of the viewport */
        }
        .sidebar {
            width: 150px !important;
            background-color:black;
            color: black;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 1070vh; /* Ensure the sidebar spans the full height of the viewport */
            border-right: 2px solid white; /* Optional shadow for visual separation */
            position: fixed;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            letter-spacing: 2px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff; /* White background for content area */
            border-radius: 10px;
            margin-left: 200px;
            color: #000; /* Black text for readability */
            overflow-y: auto; /* Ensure content scrolls if too long */
            position: relative; /* Position relative to place the delete button */
        }
        .technique {
            position: relative;
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff; /* White background for each technique block */
        }
        .technique h2 {
            margin-top: 0;
            color: #000;
        }
        .button-group {
            margin-top: 20px;
        }
        .button-group button {
            margin-right: 10px;
            margin-bottom: 10px; /* Add margin bottom for spacing */
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-group button:hover {
            background-color: #0056b3;
        }
        .create-quiz-button {
            margin-top: 20px;
            text-align: center;
        }
        .create-quiz-button a {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-right: 10px; /* Space between buttons */
        }
        .create-quiz-button .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-right: 10px; /* Space between buttons */
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
        .video-container {
            margin-bottom: 20px;
        }
        .video-container h2 {
            color: #000;
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this content?')) {
                window.location.href = 'delete_content.php?id=' + id;
            }
        }
    </script>
</head>
<body>
    <br><br><br><br><br>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="contentpage.php">SQL techniques</a></li>
                <li><a href="contentpage2.php">XSScript techniques</a></li>
            </ul>
        </div>
        <div class="content">
            <!-- Embedded YouTube video -->
            <div class="video-container">
                <h2>Introduction to SQL Injections</h2>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/4hjrb2ztwM8" frameborder="0" allowfullscreen></iframe>
            </div>

            <?php foreach ($quizzes as $quiz): ?>
            <div class="technique">
                <?php if ($isAdmin): ?>
                <button class="delete-btn" onclick="confirmDelete(<?php echo htmlspecialchars($quiz['id']); ?>)">X</button>
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($quiz['name']); ?></h2>
                <p><?php echo $quiz['description']; // Render HTML content ?></p>
                <div class="button-group">
                    <!-- Assign unique href based on quiz ID -->
                    <form action="sqltry<?php echo htmlspecialchars($quiz['id']); ?>.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="<?php echo htmlspecialchars($quiz['name']); ?>">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                    <?php if ($isAdmin): ?>
                    <form action="edit_content.php" method="get" style="margin: 0;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($quiz['id']); ?>">
                        <button type="submit">Edit Content</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="create-quiz-button">
                <?php
                if ($isAdmin) {
                    echo '<a href="create_quiz.php" class="btn">Create Quiz</a>';
                    echo '<a href="add_content.php" class="btn">Add Content</a>';
                    echo '<a href="create_testquestion.php" class="btn">Create Test Question</a>'; // Add the Create Test button
                    echo '<a href="add_test.php" class="btn">Add Test</a>';
                }
                ?>
            </div>
        </div>
        <?php include 'topnav.php'; ?>
    </div>
</body>
</html>
