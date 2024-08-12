<?php
ob_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'server.php';
require_once 'header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Get current user info
$userId = $_SESSION['userid'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        // Validate input
        $new_username = !empty($_POST['username']) ? trim($_POST['username']) : $username;
        $new_password = !empty($_POST['password']) ? trim($_POST['password']) : null;
        $current_password = !empty($_POST['current_password']) ? trim($_POST['current_password']) : null;
        $otp = !empty($_POST['otp']) ? trim($_POST['otp']) : null;

        // Validate new password
        if ($new_password && !preg_match('/^(?=.*[A-Z])(?=.*\W).{8,}$/', $new_password)) {
            echo "<script>alert('Password must be at least 8 characters long and include at least one uppercase letter and one special character');</script>";
        } else {
            // Check current password against database
            $check_password = $conn->prepare("SELECT password FROM userinfo WHERE userid = ?");
            if ($check_password === false) {
                die("MySQL prepare statement error (check password): " . $conn->error);
            }
            $check_password->bind_param("i", $userId);
            $check_password->execute();
            $check_password->store_result();

            if ($check_password->num_rows == 1) {
                $check_password->bind_result($hashed_password);
                $check_password->fetch();

                // Verify current password
                if (!password_verify($current_password, $hashed_password)) {
                    echo "<script>alert('Current password is incorrect');</script>";
                } else {
                    // Validate OTP and check expiration
                    if (isset($_SESSION['otp']) && isset($_SESSION['otp_time'])) {
                        $current_time = time();
                        $otp_lifetime = 60; // 3 minutes in seconds
                        
                        if ($current_time - $_SESSION['otp_time'] > $otp_lifetime) {
                            echo "<script>alert('OTP has expired. Please request a new OTP.');</script>";
                        } elseif ($_SESSION['otp'] == $otp) {
                            // Proceed to update username and password
                            // Check for duplicate username
                            $duplicate = $conn->prepare("SELECT userid FROM userinfo WHERE username = ? AND userid != ?");
                            if ($duplicate === false) {
                                die("MySQL prepare statement error (duplicate): " . $conn->error);
                            }
                            $duplicate->bind_param("si", $new_username, $userId);
                            $duplicate->execute();
                            $duplicate->store_result();

                            if ($duplicate->num_rows > 0) {
                                echo "<script>alert('Username is already taken');</script>";
                            } else {
                                // Prepare query to update user info
                                if ($new_password) {
                                    // Hash the new password
                                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                                    // Update username and password
                                    $query = $conn->prepare("UPDATE userinfo SET username = ?, password = ? WHERE userid = ?");
                                    $query->bind_param("ssi", $new_username, $new_password_hashed, $userId);
                                } else {
                                    // Update only username
                                    $query = $conn->prepare("UPDATE userinfo SET username = ? WHERE userid = ?");
                                    $query->bind_param("si", $new_username, $userId);
                                }

                                // Execute query and update session variables
                                if ($query->execute()) {
                                    $_SESSION['username'] = $new_username;
                                    if ($new_password) {
                                        $_SESSION['password'] = $new_password; // Store the plain text password temporarily
                                    }

                                    // Redirect back to profile page
                                    header("Location: profile.php");
                                    exit();
                                } else {
                                    die("Error updating record: " . $conn->error);
                                }
                                $query->close();
                            }
                            $duplicate->close();
                        } else {
                            echo "<script>alert('Invalid OTP');</script>";
                        }
                    } else {
                        echo "<script>alert('OTP not found or session expired.');</script>";
                    }
                }
            } else {
                echo "<script>alert('Error fetching password');</script>";
            }
            $check_password->close();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['send_otp'])) {
    header('Content-Type: application/json'); // Set content type to JSON
    ob_clean();
    // Handle OTP request via AJAX
    // Generate OTP
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    $_SESSION['otp'] = $otp; // Store OTP in session for verification later
    $_SESSION['otp_time'] = time(); // Store OTP generation time in session
    
    // Send OTP email
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.yahoo.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dgandhi50@yahoo.com'; // Your SMTP username
        $mail->Password = 'hkrnqbzyizzxtcsi'; // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        $mail->setFrom('dgandhi50@yahoo.com', 'dhara gandhi');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is <b>$otp</b>. This code will expire in 1 minute.";
        $mail->AltBody = "Your OTP code is $otp. This code will expire in 1 minute.";
        
        $mail->send();
        $_SESSION['otp_sent'] = true;
        echo json_encode(['status' => 'success', 'message' => 'OTP has been sent to your email.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
    ob_end_flush();
    exit();
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
        .form-container input[type="password"],
        .form-container input[type="text"] {
            width: 93%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #449bb5;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px; /* Added space between buttons */
        }
        .form-container .send-button {
            background-color: black;
            color: white;
            text-align: center;
            text-decoration: none;
            padding: 10px;
            border-radius: 4px;
            display: block;
            margin-bottom: 10px;
        }

        .form-container button:hover {
            background-color: #449bb5;
        }
         .form-container .send-button:hover {
            background-color: grey;
        }
        

        .form-container .cancel-button {
            background-color: #d9534f;
            color: white;
            text-align: center;
            text-decoration: none;
            padding: 10px;
            border-radius: 4px;
            display: block;
            margin-top: 10px;
        }

        .form-container .cancel-button:hover {
            background-color: #c9302c;
        }
        .toggle-password {
            position: relative;
            margin-left: 0px; /* Adjust position as needed */
            cursor: pointer;
            user-select: none;
        }
        
        .toggle-password img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px; /* Adjust icon size */
            height: auto;
            margin-left: -15px;
            
        }
    </style>
    <script>
function sendOTP(event) {
    event.preventDefault(); // Prevent form submission
    var sendButton = document.querySelector('.send-button');
    
    // Disable the button and start the countdown
    sendButton.disabled = true;
    startCountdown(sendButton, 30);

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "?send_otp=true", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                console.log(response); // Debugging line to log response
                if (response.status === 'success') {
                    alert(response.message);
                } else {
                    alert("Failed to send OTP. Please try again.");
                }
            } catch (e) {
                console.error("Error parsing response:", e); // Log parsing error
                console.error("Response received:", xhr.responseText); // Log raw response
                alert("An error occurred while processing the OTP request.");
            }
        } else if (xhr.readyState == 4) {
            // Handle non-200 status codes
            alert("Failed to send OTP. Server returned status: " + xhr.status);
        }
    };
    xhr.send();
}

function startCountdown(button, seconds) {
    var remainingTime = seconds;

    button.textContent = "Send OTP (" + remainingTime + "s)";
    
    var countdownInterval = setInterval(function() {
        remainingTime--;
        button.textContent = "Send OTP (" + remainingTime + "s)";
        
        if (remainingTime <= 0) {
            clearInterval(countdownInterval);
            button.textContent = "Send OTP";
            button.disabled = false;
        }
    }, 1000);
}

function togglePasswordVisibility(fieldId, toggleButtonId) {
    var passwordField = document.getElementById(fieldId);
    var toggleButton = document.getElementById(toggleButtonId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.innerHTML = "<img src='eye-slash1.png' alt='Hide password'>";
    } else {
        passwordField.type = "password";
        toggleButton.innerHTML = "<img src='eye1.png' alt='Show password'>";
    }
}
</script>
    
</head>
<body>
    <div class="form-container">
        <h1>Edit Profile</h1>
        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required minlength="5">

            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>
            <span class="toggle-password" id="toggleCurrentPassword" onclick="togglePasswordVisibility('current_password', 'toggleCurrentPassword')">
                    <img src="eye1.png" alt="Toggle password visibility">
                </span>

            <label for="password">New Password (leave blank to keep current password)</label>
            <input type="password" id="password" name="password" pattern="(?=.*[A-Z])(?=.*\W).{8,}" title="Password must be at least 8 characters long and include at least one uppercase letter and one special character">
			<span class="toggle-password" id="toggleNewPassword" onclick="togglePasswordVisibility('password', 'toggleNewPassword')">
                    <img src="eye1.png" alt="Toggle password visibility">
                </span>
            <label for="otp">OTP</label>
            <input type="text" id="otp" name="otp">

            <div class="button-container">
                <button type="button" class="send-button" onclick="sendOTP(event)">Send OTP</button>
                <button type="submit" name="submit">Save Changes</button>
                <a href="profile.php" class="cancel-button">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
