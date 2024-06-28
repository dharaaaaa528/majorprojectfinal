<?php
session_start();
include 'db.php';

// Assume user is logged in and user ID is stored in session
$userId = $_SESSION['userid'];

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM userinfo WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inj3ctPractice User Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'topnav.php'; ?>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="#">Profile<style>background-color:#56C2DD</style></a></li>
                <li><a href="#">Progress</a></li>
                <li><a href="#">Certifications</a></li>
                <li><a href="#">Tests</a></li>
            </ul>
        </div>
</body>
<body>
    <h1><?php echo htmlspecialchars($user['username']); ?>'s Profile</h1>
    <?php if ($user['profile_picture']): ?>
        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
    <?php endif; ?>
    <a href="updateprofile.php">Edit Profile</a>
</body>
</html>
