<?php 
require_once 'server.php';
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
            background-image: url('background.jpg');
            background-size: cover; /* Makes the image cover the entire page */
            background-size: cover; /* Makes the image cover the entire page */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-position: center; /* Centers the image */
            background-attachment: fixed; /* Fixes the image while scrolling */
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
    
        }
        
        html, body {
        height: 100%;
        }
        .topnav {
            background-color: #333;
            overflow: hidden;
            display: flex;
            align-items: center; 
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
            padding: 14px 20px;
            text-decoration: none;
             font-size: 20px;
        }
     
        
        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }
        /* Dropdown container */
        .topnav .dropdown {
            float: left;
            overflow: hidden;
            margin-left: 50px;
        }
        /* Style the dropdown button */
        .topnav .dropdown .dropbtn {
            font-size: 20px;  
            border: none;
            outline: none;
            color: #f2f2f2;
            padding: 14px 20px;
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
        /* Style for the buttons */
        .topnav .button {
            float: right;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            background-color: #6200ea;
            border-radius: 5px;
            margin: 8px;
        }
        .topnav .button:hover {
            background-color: #3700b3;
        }
        
        .topnav a[href="about.php"] {
            margin-left: 50px; /* Adjust as needed */
        }
        .right-nav {
            margin-left: auto; /* Pushes the content to the right */
            display: flex;
        }
    </style>
</head>
<body>

<div class="topnav">
	<a href="#" class="branding">
        <img src="logo3.jpg" alt="Logo"  class="logo">
        
    </a>
    <a href="main.php">Home</a>
    <!-- Content dropdown -->
    <div class="dropdown">
        <button class="dropbtn">Content 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="contentpagemain.php">Courses</a>
            <a href="#">Tutorials</a>
            <a href="#">Articles</a>
        </div>
    </div>
    <!-- Group Contact and About links -->
    
    <a href="about.php">About</a>
    <!-- Registration and Login buttons -->
     <div class="right-nav">
        <a href="registration.php" class="button">Register</a>
        <a href="login.php" class="button">Login</a>
    </div>
</div>

</body>
</html>

