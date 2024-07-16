<?php
require_once 'dbconfig.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in through Google or regular login
if (!isset($_SESSION["login"]) && !isset($_SESSION["google_loggedin"])) {
    header("Location: login.php");
    exit();
}

// Fetch user information
if (isset($_SESSION["google_loggedin"]) && $_SESSION["google_loggedin"] === TRUE) {
    // Fetch Google user info
    $stmt = $pdo->prepare('SELECT username FROM userinfo WHERE userid = ?');
    $stmt->execute([$_SESSION['google_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user['username'];
} else {
    $username = $_SESSION["username"];
}

// Handle search functionality
if (isset($_GET['search'])) {
    $searchQuery = strtolower(trim($_GET['search']));

    switch ($searchQuery) {
        case 'sql injection':
            header("Location: contentpage.php");
            exit();
        case 'script injection':
            header("Location: contentpage2.php");
            exit();
        case 'sql':
            header("Location: contentpage.php");
            exit();
        case 'script':
            header("Location: contentpage2.php");
            exit();
        default:
            $searchError = "No results found for '$searchQuery'. Please search for 'SQL Injection' or 'Script Injection'.";
    }
}
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
            margin-left: 50px; /* Add margin between dropdowns */
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

        .topnav a[href="about.php"] {
            margin-left: 50px; /* Adjust as needed */
        }

        .topnav .search-bar {
            margin-left: 100px; /* Add space between About link and search bar */
        }

        .topnav .search-bar input[type="text"] {
            padding: 10px;
            font-size: 17px;
            border: none;
            border-radius: 20px;
            margin-right: 10px;
            width: 400px; /* Make the search bar longer */
        }

        .topnav .search-bar input[type="submit"] {
            padding: 6px 10px;
            font-size: 17px;
            border: none;
            border-radius: 4px;
            background-color: #ddd;
            cursor: pointer;
        }

        .topnav .search-bar input[type="submit"]:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<div class="topnav">
    <a href="usermain.php" class="branding">
        <img src="logo3.jpg" alt="Logo" class="logo">
    </a>
    <a href="usermain.php">Home</a>
    <!-- Content dropdown -->
    <div class="dropdown">
        <button class="dropbtn">Content 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="contentpage.php">Courses</a>
        </div>
    </div>
    <!-- Assessments dropdown -->
    <div class="dropdown">
        <button class="dropbtn">Assessments 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="test.php">Tests</a>
        </div>
    </div>
    
    <a href="about.php">About</a>

    <!-- Search bar -->
    <div class="search-bar-container">
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Type your search query here">
                <input type="submit" value="Search">
            </form>
            <?php
            if (isset($searchError)) {
                echo "<p>$searchError</p>";
            }
            ?>
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
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

</body>
</html>
