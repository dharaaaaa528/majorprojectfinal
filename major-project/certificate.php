<?php
require_once 'config.php';
require_once 'header.php';
require_once 'topnav.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if ((!isset($_SESSION["login"]) || $_SESSION["login"] !== true) && (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true)) {
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$userId = $_SESSION["userid"];

// Fetch all certificates for the user
$sql = "SELECT id, quiz_id, file_path FROM certificates WHERE user_id = ?";
$certificates = [];
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
    $stmt->close();
} else {
    echo "Error fetching certificates: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificates</title>
    <style>
        .certificate-container {
            text-align: center;
            margin-top: 20px;
            position: absolute;
            top: 120px; /* Adjust based on your layout */
            left: 220px; /* Adjust based on your sidebar width */
            width: calc(100% - 220px); /* Adjust width based on sidebar */
             /* Ensure it's on top */
        }
        .certificate-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
       
        .certificate-image {
            width: 100%;
            height: auto;
            cursor: pointer;
        }
        .course-name {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .sidebar {
            width: 200px;
            background-color: #000;
            height: calc(100vh - 20px);
            position: absolute;
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
        .sidebar a.certificate-link {
            color: #56C2DD;
        }
        .main-content {
            margin-left: 200px; /* Space for the side navigation */
            padding: 20px;
            width: calc(100% - 200px); /* Adjust width based on sidebar */
            box-sizing: border-box;
            height: calc(100vh - 50px); /* Adjust based on top nav height */
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.7);
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <a href="progress.php" class="progress-link"><u>Progress</u></a>
        <a href="certificate.php" class="certificate-link"><u>Certifications</u></a>
        <a href="settings.php"><u>Settings</u></a>
    </div>
    <div class="main-content">
        <div class="certificate-container">
            <h1>Your Certificates</h1>
            <div class="certificate-grid">
                <?php if (count($certificates) > 0): ?>
                    <?php foreach ($certificates as $certificate): ?>
                        <?php
                        // Fetch the course name
                        $courseSql = "SELECT name FROM quizzes WHERE id = ?";
                        if ($courseStmt = $conn->prepare($courseSql)) {
                            $courseStmt->bind_param("i", $certificate['quiz_id']);
                            $courseStmt->execute();
                            $courseStmt->bind_result($courseName);
                            $courseStmt->fetch();
                            $courseStmt->close();
                        } else {
                            $courseName = "Unknown Course";
                        }

                        // Check if the file exists
                        $filePath = $certificate['file_path'];
                        if (!file_exists($filePath)) {
                            // If file does not exist, use a placeholder
                            $filePath = 'path/to/placeholder.jpg'; // Make sure this placeholder file exists
                        }
                        ?>
                        <div class="certificate-item">
                            <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($filePath); ?>" alt="Certificate Image" class="certificate-image">
                            </a>
                            <p class="course-name">Course: <?php echo htmlspecialchars($courseName); ?></p>
                            <a href="<?php echo htmlspecialchars($filePath); ?>" download>Download Certificate</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No certificates found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>




