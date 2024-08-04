<?php
session_start();
include 'server.php';
include 'header.php'; // Ensure this file includes the database connection

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

if (!$isAdmin) {
    exit('Unauthorized'); // Redirect or show error if not admin
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    
    if ($id <= 0 || empty($name) || empty($description)) {
        exit('Invalid data');
    }
    
    // Update quiz content in the database
    $stmt = $pdo->prepare('UPDATE quizzes SET name = ?, description = ? WHERE id = ?');
    $stmt->execute([$name, $description, $id]);
    
    // Redirect to testpage2.php
    header('Location: testpage2.php');
    exit;
}

// Fetch quiz details for editing
$quizId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($quizId <= 0) {
    exit('Invalid quiz ID'); // Display error if ID is not valid
}

// Fetch quiz details from the database
$stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = ?');
$stmt->execute([$quizId]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    exit('Quiz not found'); // Display error if quiz ID is not found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
    <script src="https://cdn.tiny.cloud/1/7ftyh23mussj5lq3rapie4ao0yw95h1pp6jrgbr7uoxzo6gs/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help',
            content_css: 'https://www.tiny.cloud/css/codepen.min.css'
        });
    </script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .edit-content {
            width: 100%;
            max-width: 1200px;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .edit-content h1 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group textarea {
            min-height: 200px;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'topnav.php'; ?>
    <div class="container">
        <div class="edit-content">
            <h1>Edit Quiz Content</h1>
            <form action="edit_content.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($quiz['id']); ?>">
                <div class="form-group">
                    <label for="name">Quiz Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($quiz['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="editor" name="description"><?php echo htmlspecialchars($quiz['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Update Content</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
