<?php
session_start();
?>

<head>
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
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            height: 100vh;
            position: fixed;
            top: 0;
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
            margin-left: 200px;
            padding: 20px;
            width: 100%;
            color: #F6EEEE;
        }
         

    </style>
<body>
    <div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <a href="#"><u>Progress</u></a>
        <a href="#"><u>Certifications</u></a>
    </div>
    <div class="content">
        <h1>Welcome to your profile!</h1>
        <p>This is your profile content area.</p>
    </div>
</body>
</html>