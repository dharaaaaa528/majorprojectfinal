<?php
require_once 'topnav.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
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
         

    </style>
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
            <p>Name: <?php echo $username; ?></p>
            <p>Email: <?php echo $email; ?></p>
        </div>
    </div>

</div>
</body>
</html>