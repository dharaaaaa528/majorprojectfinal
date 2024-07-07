<?php
require_once 'server.php';
require_once 'topnav.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Get current user info from the database
$userId = $_SESSION['userid'];

$query = $conn->prepare("SELECT username, email FROM userinfo WHERE userid = ?");
$query->bind_param("i", $userId);
$query->execute();
$query->bind_result($username, $email);
$query->fetch();
$query->close();

// Update session variables
$_SESSION['username'] = $username;
$_SESSION['email'] = $email;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* Basic styling for the navigation */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #000; /* Set background color to black */
            color: #000; /* Adjust text color for visibility */
        }
        
        /* Profile picture styling */
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
            color: white ;
            margin-left: 200px;
            padding: 20px;
            width: calc(100% - 200px); /* Adjust width considering the sidebar width */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full height of the viewport */
            box-sizing: border-box; /* Include padding and border in element's total width and height */
            background: url('background.jpg') no-repeat center center; /* Add background image */
            background-size: cover; /* Cover the entire content area */
        }

        .content-inner {
            text-align: center; /* Center text within the content area */
        }
        .profile-info {
            background-color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            width: 300px; /* Adjust width as needed */
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
            width: 150px; /* Adjust width as needed */
            text-align: centre;
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
        <a href="#"><u>Progress</u></a>
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
            <div class="profile-edit">
                <a href="updateprofile.php">Edit Profile</a>
            </div>
        </div>
    </div>
</body>
</html>




