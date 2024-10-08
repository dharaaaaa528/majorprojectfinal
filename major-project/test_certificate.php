<?php
require_once 'config.php';
require_once 'header.php';
require_once 'sessiontimeout.php';

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
$sql = "SELECT certificate_id, test_id, file_path, certificate_date FROM test_certificates WHERE user_id = ?";
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

// Fetch all test categories
$categories = [];
$sql = "SELECT test_id, category FROM tests WHERE test_id IN (SELECT test_id FROM test_certificates WHERE user_id = ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[$row['test_id']] = $row['category'];
    }
    $stmt->close();
} else {
    echo "Error fetching test categories: " . $conn->error;
    exit();
}

// Organize certificates by category
$certificatesByCategory = [
    'SQL' => [],
    'XSS' => []
];

foreach ($certificates as $certificate) {
    $testId = $certificate['test_id'];
    if (isset($categories[$testId])) {
        $category = $categories[$testId];
        if (array_key_exists($category, $certificatesByCategory)) {
            $certificatesByCategory[$category][] = $certificate;
        }
    }
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
            border-radius: 25px;
            margin: 20px 0;
            position: relative;
        }
        .certificate-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .certificate-table th, .certificate-table td {
            border: 3px solid black;
            padding: 8px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.3);
        }
        .certificate-table th {
            background-color: #f2f2f2;
            color: black;
        }
        .preview-button {
            padding: 8px 16px;
            background-color: #56C2DD;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .preview-button:hover {
            background-color: #3b9db1;
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
        .sub-menu {
            padding-left: 30px;
        }
        .sub-menu a {
            font-size: 16px;
        }
    </style>
</head>
<body>
<br><br><br><br><br>
    <div class="sidebar">
        <a href="profile.php" class="profile-link"><u>Profile</u></a>
        <div class="sub-menu">
            <a href="certificate_details.php" class="details-link"><u>Certificate Details</u></a>
        </div>
        <div class="sub-menu">
            <a href="delete_account.php" class="details1-link"><u>Delete Account</u></a>
        </div>
        <a href="progress.php" class="progress-link"><u>Progress</u></a>
        <a href="certificate.php"><u>Quiz Certifications</u></a>
        <a href="test_certificate.php" class="certificate-link"><u>Test Certifications</u></a>
        <a href="settings.php"><u>Settings</u></a>
    </div>
    <div class="main-content">
        <div class="certificate-container">
            <h1>Your Certificates</h1>
            <?php
            foreach ($certificatesByCategory as $category => $certificates) {
                echo "<h2>$category Certificates</h2>";
                echo "<table class='certificate-table'>";
                echo "<thead><tr><th>Preview</th><th>Course</th><th>Date Issued</th><th>Download</th></tr></thead>";
                echo "<tbody>";

                if (empty($certificates)) {
                    echo "<tr><td colspan='4'>No certificates found for this category.</td></tr>";
                } else {
                    foreach ($certificates as $certificate) {
                        // Fetch the course name
                        $courseSql = "SELECT name FROM tests WHERE test_id = ?";
                        if ($courseStmt = $conn->prepare($courseSql)) {
                            $courseStmt->bind_param("i", $certificate['test_id']);
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

                        // Format date and time
                        $createdAt = date('d-m-Y H:i:s', strtotime($certificate['certificate_date'])); // Format date and time

                        echo "<tr>";
                        echo "<td><button class='preview-button' onclick=\"window.open('" . htmlspecialchars($filePath) . "', '_blank')\">Click here to preview</button></td>";
                        echo "<td>" . htmlspecialchars($courseName) . "</td>";
                        echo "<td>" . htmlspecialchars($createdAt) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($filePath) . "' download>Download</a></td>";
                        echo "</tr>";
                    }
                }

                echo "</tbody></table>";
            }
            ?>
        </div>
    </div>
    <?php include 'topnav.php';?>
</body>
</html>
