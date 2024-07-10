<?php
require_once 'server.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

// Get current user info
$userId = $_SESSION['userid'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $new_username = !empty($_POST['username']) ? trim($_POST['username']) : $username;
    $new_email = !empty($_POST['email']) ? trim($_POST['email']) : $email;

    // Update user info in the database
    $query = $conn->prepare("UPDATE userinfo SET username = ?, email = ? WHERE userid = ?");
    $query->bind_param("ssi", $new_username, $new_email, $userId);
    
    if ($query->execute()) {
        // Update session variables
        $_SESSION['username'] = $new_username;
        $_SESSION['email'] = $new_email;
        
        // Redirect back to profile page
        header("Location: profile.php");
        exit();
    } else {
        die("Error updating record: " . $conn->error);
    }
    $query->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        /* Basic styling for the form */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: url('background.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: black;
        }

        .form-container h1 {
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"] {
            width: 93%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #56C2DD;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #449bb5;
        }

        .form-container .cancel-button {
            background-color: #d9534f;
            color: white;
            text-align: center;
            text-decoration: none;
        }

        .form-container .cancel-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Profile</h1>
        <form method="POST" action="updateprofile.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <div class="button-container">
                <button type="submit">Save Changes</button>
                <a href="profile.php" class="cancel-button" style="text-decoration: none; padding: 10px 30px; border-radius: 4px; color: white; display: inline-block; background-color: #d9534f; text-align: centre; margin-left: 100px">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
