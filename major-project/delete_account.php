<?php
require_once 'config.php';
require_once 'header.php';
require_once 'topnav.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userid'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        // Prepare and execute deletion query
        $sql = "DELETE FROM userinfo WHERE userid = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $userId);
            if ($stmt->execute()) {
                // Unset session and redirect to a confirmation page or login
                session_unset();
                session_destroy();
                header("Location: main.php"); // Redirect to a confirmation page
                exit();
            } else {
                $error = "Error deleting account: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Error preparing statement: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Account</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #000;
            color: #fff;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            color:black;
            justify-content: center;
            align-items: center;
            display: flex;
            flex-direction: column;
             margin-left: 600px;
             margin-top: 200px;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .container p {
            margin-bottom: 20px;
        }
        .container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: red;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: darkred;
        }
        .container a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .container a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
        }
        .sidebar {
            width: 200px;
            background-color: #000;
            height: calc(100vh - 20px);
            position: absolute;
            top: 99px;
            left: 0;
            padding-top: 20px;
            color: #fff;
            border-right: 2px solid white;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }
        
        .sidebar a.details1-link {
            color: #56C2DD;
        }
        .sub-menu {
            padding-left: 30px;
        }

        .sub-menu a {
            font-size: 16px;
        }
        
    </style>
</head>
<body>
<div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <div class="sub-menu">
            <a href="certificate_details.php" class="details-link"><u>Certificate Details</u></a>
        </div>
        <div class="sub-menu">
        <a href="delete_account.php" class="details1-link"><u>Delete Account</u></a>
    </div>
        <a href="progress.php"><u>Progress</u></a>
        <a href="certificate.php"><u>Quiz Certifications</u></a>
        <a href="test_certificate.php"><u>Test Certifications</u></a>
        <a href="settings.php"><u>Settings</u></a>
    </div>  
    <div class="container">
        <h1>Delete Your Account</h1>
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="confirm_delete">Delete Account</button>
        </form>
        <a href="profile.php">Cancel</a>
    </div>
</body>
</html>
