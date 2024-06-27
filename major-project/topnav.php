<?php

session_start();


// Check if user is logged in
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch user information if needed
$username = $_SESSION["username"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Main</title>
    <style>
        /* Basic styling for the navigation */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #000; /* Set background color to black */
            color: #000; /* Adjust text color for visibility */
        }
        .topnav {
            background-color: #333;
            overflow: hidden;
        }
        
        .topnav img.logo {
            margin-right: 10px; /* Adjust margin as needed */
            width: 100px; /* Adjust width as needed */
            height: 25px; /* Adjust height as needed */
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
            font-size: 16px; /* Adjust font size as needed */
            margin-left: 180px; /* Adjust margin as needed */
            margin-top: 5px;
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
        /* Style the active dropdown link */
        .topnav .dropdown-content a.active {
            background-color: #4CAF50;
            color: white;
        }
        /* Profile picture styling */
        .profile-picture img {
            border-radius: 50%;
        }
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="topnav">

	<a href="#">
        <img src="logo1.jpg" alt="Logo" class="logo">
        <span class="inj3ct-practice">Inj3ctPractice</span>
    </a>
	
	
    <a href="usermain.php">Home</a>
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
    <div class="dropdown">
        <button class="dropbtn">Assessments 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="#">Quizzes</a>
            <a href="#">Tests</a>
            <a href="#">Exams</a>
        </div>
    </div>
    <a href="#">Contact</a>
    <a href="#">About</a>
    <div class="dropdown" style="float: right;">
        <button class="dropbtn">
            <img src="profile.png" alt="" width="30" height="30" style="border-radius: 50%;">
            <?= htmlspecialchars($username) ?> 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content" style="right: 0; left: auto;">
            <a href="#">Profile</a>
            <a href="#">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

<div class="content home">
    <!-- Additional content can be placed here -->
</div>
</body>
</html>



