<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


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
   
    <style>
        /* Basic styling for the navigation */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: black;
    color: white;
}

.topnav {
    background-color: #333;
    overflow: hidden;
    display: flex;
    align-items: center; /* Center items vertically */
}

.topnav img.logo {
    margin-right: 20px; /* Adjust margin as needed */
    width: 80px; /* Increase width */
    height: auto; /* Maintain aspect ratio */
    border-radius: 0%; /* Make the image circular */
    object-fit: cover; /* Ensure the image covers the circle */
}

.topnav a {
    float: left;
    display: block;
    color: #f2f2f2;
    text-align: center;
    padding: 14px 20px; /* Adjust padding for spacing */
    text-decoration: none;
    font-size: 20px; /* Increase font size */
}

.topnav a:hover {
    background-color: #ddd;
    color: black;
}

/* Dropdown container */
.topnav .dropdown {
    float: left;
    overflow: hidden;
    margin-left: 40px; /* Add margin between dropdowns */
}

/* Style the dropdown button */
.topnav .dropdown .dropbtn {
    font-size: 20px;  /* Increase font size */
    border: none;
    outline: none;
    color: #f2f2f2;
    padding: 14px 20px; /* Adjust padding for spacing */
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
        <img src="logo3.jpg" alt="Logo"  class="logo">
        
    </a>
    <a href="usermain.php">Home</a>
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
   
    <div class="dropdown" style="margin-left: auto;">
        <button class="dropbtn">
            <img src="profile.png" alt="" width="30" height="30" style="border-radius: 50%;">
            <?= htmlspecialchars($username) ?> 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content" style="right: 0; left: auto;">
            <a href="profile.php">Profile</a>
            <a href="#">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

</body>
</html>