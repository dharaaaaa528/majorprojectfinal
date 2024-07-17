<?php
ob_start();
require_once 'server.php';
require_once 'topnav.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in (either through Google or regular login)
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userid'];
$isGoogleLoggedIn = isset($_SESSION['google_loggedin']) && $_SESSION['google_loggedin'] == 1;

// Fetch user info from the database based on the login type
if ($isGoogleLoggedIn) {
    $query = $conn->prepare("SELECT username, email FROM userinfo WHERE userid = ?");
} else {
    $query = $conn->prepare("SELECT username, email, password FROM userinfo WHERE userid = ?");
}

if ($query === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$query->bind_param("i", $userId);
$query->execute();

if ($isGoogleLoggedIn) {
    $query->bind_result($username, $email);
} else {
    $query->bind_result($username, $email, $password);
}

$query->fetch();
$query->close();

$_SESSION['username'] = $username;
$_SESSION['email'] = $email;

// Mask the password with 8 asterisks by default
$maskedPassword = str_repeat('*', 8);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #000;
            color: #fff;
        }
        
        .profile-picture img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .sidebar {
            width: 200px;
            background-color: #000;
            height: calc(100vh - 50px);
            position: absolute;
            top: 100px;
            left: 0;
            padding-top: 20px;
            color: #fff;
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
        
        .sidebar a.profile-link {
            color: #56C2DD;
        }

        .content {
            color: white;
            margin-left: 200px;
            padding: 20px;
            width: calc(100% - 200px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
            background: url('background.jpg') no-repeat center center;
            background-size: cover;
        }

        .content-inner {
            text-align: center;
        }
        .profile-info {
            background-color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            width: 300px;
            text-align: left;
        }

        .profile-info p {
            margin: 5px 0;
            color: black;
        }

        .profile-edit {
            background-color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            margin-left: 70px;
            width: 150px;
            text-align: center;
        }

        .profile-edit a {
            color: #56C2DD;
            text-decoration: none;
        }

        .profile-edit a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <a href="progress.php"><u>Progress</u></a>
        <a href="#"><u>Certifications</u></a>
    </div>  
    <div class="content">
        <div class="content-inner">
            <h1>PROFILE</h1>
            <div class="profile-picture">
                <img src="profile.png" alt="User Profile Picture">
            </div>
          
            <div class="profile-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
            </div>
            <div class="profile-info">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
            <div class="profile-info">
                <p><strong>Password:</strong> <?php echo $maskedPassword; ?></p>
            </div>
            <div class="profile-edit">
                <a href="updateprofile.php">Edit Profile</a>
            </div>
        </div>
    </div>
</body>
</html>
