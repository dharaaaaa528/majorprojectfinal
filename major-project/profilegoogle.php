<?php
require_once 'server.php';
require_once 'topnavgoogle.php';
// Initialize the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in via Google
if (!isset($_SESSION['google_id'])) {
    header("Location: login.php");
    exit();
}

// Get current user info from the database
$googleId = $_SESSION['google_id'];

try {
    $stmt = $pdo->prepare("SELECT name, email, picture FROM accounts WHERE id = ?");
    $stmt->execute([$googleId]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        throw new Exception('User not found in database.');
    }

    $username = $account['name'];
    $email = $account['email'];
    $picture = $account['picture'];
} catch (PDOException $e) {
    // Handle database errors
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    // Handle other exceptions
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
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
            width: calc(100% - 200px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
            background: url('background.jpg') no-repeat center center;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="profilegoogle.php" class="profile-link"><u>Profile</u></a>
        <a href="#"><u>Progress</u></a>
        <a href="#"><u>Certifications</u></a>
    </div>  
    <div class="content">
        <div class="content-inner">
            <h1>PROFILE</h1>
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($picture); ?>" alt="User Profile Picture">
            </div>
          
            <div class="profile-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
            </div>
            <div class="profile-info">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
        </div>
    </div>
</body>
</html>



