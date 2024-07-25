<?php
require_once 'config.php';
require_once 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ((!isset($_SESSION["login"]) || $_SESSION["login"] !== true) && (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true)) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["userid"];
$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if ($quizId === 0) {
    echo "Invalid quiz ID.";
    exit();
}

$templates = [
    ['id' => 1, 'name' => 'Template 1', 'image' => 'template1.jpg'],
    ['id' => 2, 'name' => 'Template 2', 'image' => 'template2.jpg'],
    ['id' => 3, 'name' => 'Template 3', 'image' => 'template3.jpg']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Certificate Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .content {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 80%;
            max-width: 1000px;
            margin: 0 auto;
        }
        .template {
            display: inline-block;
            margin: 20px;
        }
        .template img {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #ccc;
        }
        .template-name {
            margin-top: 10px;
            font-size: 18px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Choose Your Certificate Template</h1>
        <form action="generate_certificate.php" method="post">
            <input type="hidden" name="quiz_id" value="<?php echo $quizId; ?>">
            <?php foreach ($templates as $template) { ?>
                <div class="template">
                    <img src="<?php echo $template['image']; ?>" alt="<?php echo $template['name']; ?>">
                    <div class="template-name"><?php echo $template['name']; ?></div>
                    <input type="radio" name="template_id" value="<?php echo $template['id']; ?>" required>
                </div>
            <?php } ?>
            <br>
            <button type="submit" class="button">Generate Certificate</button>
        </form>
    </div>
</body>
</html>
