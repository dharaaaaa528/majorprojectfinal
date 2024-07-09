<?php
ob_start(); 
// Ensure no whitespace or output before <?php tag

// Include server.php for database connection and other necessary files
require_once 'server.php';
require_once 'topnavgoogle.php';

// Initialize the session - is required to check the login state.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['google_loggedin'])) {
    header('Location: login.php');
    exit;
}

// Retrieve session variables
$stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
$stmt->execute([ $_SESSION['google_id'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Retrieve session variables
$google_loggedin = $_SESSION['google_loggedin'];
$google_email = $account['email'];
$google_name = $account['name'];
$google_picture = $account['picture'];

// Check if the modal has been shown in this session
$modalShown = isset($_SESSION['modal_shown']) && $_SESSION['modal_shown'];

// If the modal hasn't been shown yet, mark it as shown
if (!$modalShown) {
    $_SESSION['modal_shown'] = true;
}

// Example code with potential error at line 39
if (isset($_GET['search'])) {
    $searchQuery = strtolower(trim($_GET['search']));
    
    switch ($searchQuery) {
        case 'sql injection':
            header("Location: contentpagegoogle.php");
            exit();
        case 'script injection':
            header("Location: contentpage2google.php");
            exit();
        case 'sql':
            header("Location: contentpagegoogle.php");
            exit();
        case 'script':
            header("Location: contentpage2google.php");
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
    <title>Profile</title>
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .modal-content h1 {
            color: black; /* Change the color of the heading text */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .content {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for better readability */
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
            color: #f2f2f2;
        }
        .search-bar-container {
            text-align: center;
            margin: 20px auto;
            background-color: transparent;
        }

        .search-bar input[type="text"] {
            width: 40%;
            padding: 10px;
            font-size: 16px;
            border-radius: 25px;
            border: 1px solid #ccc;
        }

        .search-bar input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 25px;
            border: none;
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .search-bar input[type="submit"]:hover {
            background-color: #555;
        }
        .button-container {
            display: flex;
            justify-content: center; /* Align the button to the left */
            position: absolute;
            bottom: 150px; /* Position the button 150px from the bottom of the page */
            width: 100%;
            padding-left: 0px; /* Add padding to create some space from the left edge */
        }

        .button {
            padding: 10px 20px;
            font-size: 50px;
            color: white;
            background-color: grey;
            text-decoration: none;
            border-radius: 30px;
        }

        .button:hover {
            background-color: darkgrey;
        }
    </style>
</head>
<body>

<!-- The Modal -->
<?php if (!$modalShown): ?>
    <div id="welcomeModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h1>Welcome, <?= htmlspecialchars($google_name) ?>!</h1>
      </div>
    </div>
<?php endif; ?>

<script>
    // Get the modal
    var modal = document.getElementById("welcomeModal");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the page loads, open the modal if it hasn't been shown yet
    window.onload = function() {
        <?php if (!$modalShown): ?>
            modal.style.display = "block";
        <?php endif; ?>
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<div class="content">
    <h1>Welcome to Our Website</h1>
    <p>This is a sample text content to show how you can add text to your webpage. You can include paragraphs, headings, lists, images, and more to enhance the content of your site. This text block is styled with a semi-transparent background and rounded corners for better readability against the background image.</p>
    <p>Feel free to customize the styling and content to fit your needs.</p>
</div>
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
<div class="button-container">
    <a href="contentpage.php" class="button">START LEARNING NOW</a>
</div>

</body>
</html>
