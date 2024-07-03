<?php
require_once 'server.php';


// Initialize the session - is required to check the login state.
session_start();
// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['google_loggedin'])) {
    header('Location: login.php');
    exit;
}
// Retrieve session variables
$stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
$stmt->execute([ $_SESSION['google_id'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);
// Retrieve session variables
$google_loggedin = $_SESSION['google_loggedin'];
$google_email = $account['email'];
$google_name = $account['name'];
$google_picture = $account['picture'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Top Navigation with Dropdowns</title>
    <style>
        /* Basic styling for the navigation */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-colour: black;
        }
        .topnav {
            background-color: #333;
            overflow: hidden;
        }
        .topnav img.logo {
            margin-right: 10px; /* Adjust margin as needed */
            width: 50px; /* Adjust width as needed */
            height: auto; /* Maintain aspect ratio */
            border-radius: 0%; /* Make the image circular */
            object-fit: cover; /* Ensure the image covers the circle */
        }
        .topnav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
         .topnav a.inj3ct-practice {
            font-weight: bold; /* Make the text bold */
            font-size: 25px; /* Adjust font size as needed */
            margin-left: 0px; /* Adjust margin as needed */
        }
        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }
        /* Dropdown container */
        .topnav .dropdown {
            float: left;
            overflow: hidden;
        }
        /* Style the dropdown button */
        .topnav .dropdown .dropbtn {
            font-size: 16px;  
            border: none;
            outline: none;
            color: #f2f2f2;
            padding: 14px 16px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }
        /* Dropdown content (hidden by default) */
        .topnav .dropdown-content {
            display: none;
            position: absolute;
            background-color: #333;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        /* Links inside the dropdown */
        .topnav .dropdown-content a {
            float: none;
            color: #f2f2f2;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        /* Add a background color to dropdown links on hover */
        .topnav .dropdown-content a:hover {
            background-color: #ddd;
            color: black;
        }
        /* Show the dropdown menu on hover */
        .topnav .dropdown:hover .dropdown-content {
            display: block;
        }
        /* Profile picture styling */
        .profile-picture img {
            border-radius: 50%;
        }
        /* Style the active dropdown link */
        .topnav .dropdown-content a.active {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<div class="topnav">
	 <a href="#" class="branding">
        <img src="logo1.jpg" alt="Logo"  class="logo">
        <span class="inj3ct-practice" style="font-size: 24px; margin-left: 0px" >Inj3ctPractice</span>
    </a>
    <a href="homepage.php">Home</a>
    <!-- Content dropdown -->
    <div class="dropdown">
        <button class="dropbtn">Content 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="contentpage.php">Courses</a>
            <a href="#">Tutorials</a>
            <a href="#">Articles</a>
        </div>
    </div>
    <!-- Assessments dropdown -->
    <div class="dropdown">
        <button class="dropbtn">Assessments 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="#">Quizzes</a>
            <a href="test.php">Tests</a>
            <a href="#">Exams</a>
        </div>
    </div>
   
    <div class="dropdown" style="float: right;">
        <button class="dropbtn">
            <img src="<?=$google_picture?>" alt="<?=$google_name?>" width="30" height="30" style="border-radius: 50%;">
            <?=$google_name?>
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content" style="right: 0; left: auto;">
            <a href="#">Profile</a>
            <a href="#">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

</body>
</html>