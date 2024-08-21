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

// Fetch all certificates for the user with quiz type, course name, and creation date, sorted by date
$sql = "
    SELECT c.id, c.quiz_id, c.file_path, c.created_at, q.name AS course_name, q.type AS category
    FROM certificates c
    JOIN quizzes q ON c.quiz_id = q.id
    WHERE c.user_id = ?
    ORDER BY category, c.created_at ASC
";

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
        <a href="certificate.php" class="certificate-link"><u>Quiz Certifications</u></a>
        <a href="test_certificate.php"><u>Test Certifications</u></a>
        <a href="settings.php"><u>Settings</u></a>
    </div>
    <div class="main-content">
        <div class="certificate-container">
            <h1>Your Certificates</h1>
            <?php
            // Initialize arrays to hold certificates by category
            $sqlCertificates = [];
            $xssCertificates = [];
            
            foreach ($certificates as $certificate) {
                $category = $certificate['category'];
                if ($category === 'SQL') {
                    $sqlCertificates[] = $certificate;
                } elseif ($category === 'XSS') {
                    $xssCertificates[] = $certificate;
                }
            }

            // Display SQL certificates
            echo "<h2>SQL Certificates</h2>";
            echo "<table class='certificate-table'>";
            echo "<thead><tr><th>Preview</th><th>Course</th><th>Date Issued</th><th>Download</th></tr></thead>";
            echo "<tbody>";

            if (count($sqlCertificates) > 0) {
                foreach ($sqlCertificates as $certificate) {
                    $createdAt = date('d-m-Y H:i:s', strtotime($certificate['created_at'])); // Format date and time
                    echo "<tr>";
                    echo "<td><button class='preview-button' onclick=\"window.open('" . htmlspecialchars($certificate['file_path']) . "', '_blank')\">Click here to preview</button></td>";
                    echo "<td>" . htmlspecialchars($certificate['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($createdAt) . "</td>";
                    echo "<td><a href='" . htmlspecialchars($certificate['file_path']) . "' download>Download</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No SQL certificates found.</td></tr>";
            }

            echo "</tbody></table>";

            // Display XSS certificates
            echo "<h2>XSS Certificates</h2>";
            echo "<table class='certificate-table'>";
            echo "<thead><tr><th>Preview</th><th>Course</th><th>Date Issued</th><th>Download</th></tr></thead>";
            echo "<tbody>";

            if (count($xssCertificates) > 0) {
                foreach ($xssCertificates as $certificate) {
                    $createdAt = date('d-m-Y H:i:s', strtotime($certificate['created_at'])); // Format date and time
                    echo "<tr>";
                    echo "<td><button class='preview-button' onclick=\"window.open('" . htmlspecialchars($certificate['file_path']) . "', '_blank')\">Click here to preview</button></td>";
                    echo "<td>" . htmlspecialchars($certificate['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($createdAt) . "</td>";
                    echo "<td><a href='" . htmlspecialchars($certificate['file_path']) . "' download>Download</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No XSS certificates found.</td></tr>";
            }

            echo "</tbody></table>";
            ?>
        </div>
    </div>
    <?php include 'topnav.php';?>
</body>
</html>

