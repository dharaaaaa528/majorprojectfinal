<?php
ob_start();
require_once 'server.php';
require_once 'topnav.php';
require_once 'header.php';
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
$query = $conn->prepare("SELECT first_name, last_name FROM userinfo WHERE userid = ?");
if ($query === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$query->bind_param("i", $userId);
$query->execute();
$query->bind_result($firstName, $lastName);
$query->fetch();
$query->close();

$error = '';

// Retrieve error message from session
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$_SESSION['error'] = ''; // Clear the error message for future requests

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* Existing styles */
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
            height: calc(100vh - 20px);
            position: fixed;
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
        
        .sidebar a.profile-link {
            color: #56C2DD;
        }

        .sub-menu {
            padding-left: 30px;
        }

        .sub-menu a {
            font-size: 16px;
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
            background-color: rgba(0, 0, 0, 0.5);
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

        .sidebar a:hover {
            background-color: #575757;
        }
        
        .sidebar a.details-link {
            color: lightblue;
        }
         .sidebar a.profile-link {
            color: #56C2DD;
        }

        .upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .upload-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .upload-container input[type="file"] {
            margin-bottom: 10px;
        }

        .update-form {
            display: none;
            background-color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            width: 300px;
            text-align: left;
            color: black;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 300px;
            text-align: center;
            font-size: 16px;
        }
        /* Button styling */
.profile-edit {
    background-color: #56C2DD;
    width: 300px; /* Set the desired width for the button */
    padding: 10px 20px; /* Adjust padding for better spacing */
    border-radius: 8px;
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-align: center; /* Ensure text is centered horizontally */
    border: none;
    cursor: pointer;
    display: inline-block;
    transition: background-color 0.3s, box-shadow 0.3s;
    margin: 10px;
    line-height: normal; /* Reset line-height to default */
    white-space: nowrap; /* Prevent text from wrapping */
    margin-left:-20px;
}

.profile-edit:hover {
    background-color: #45a1b1;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.profile-edit:active {
    background-color: #357a8e;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
        
    </style>
    <script>
        function showUpdateForm() {
            document.getElementById('updateForm').style.display = 'block';
            document.getElementById('updateButton').style.display = 'none';
        }
    </script>
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
    <div class="content">
        <div class="content-inner">
            <h1>CERTIFICATE DETAILS</h1>
            <div class="profile-picture">
                <img src="<?php echo isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'profile.png'; ?>" alt="User Profile Picture">
            </div>
            
            <div class="profile-info">
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstName); ?></p>
            </div>
            <div class="profile-info">
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastName); ?></p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <p><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Button to request a name change -->
   <button class="profile-edit" onclick="location.href='request_name_change.php'">Request Name Change</button>
   
           
        </div>
    </div>
</body>
</html>

