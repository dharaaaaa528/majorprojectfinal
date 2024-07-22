<?php
session_start();
include 'server.php';
include 'header.php';// Ensure this file includes the database connection

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch user details including role
$stmt = $pdo->prepare('SELECT role FROM userinfo WHERE userid = ?');
$stmt->execute([$_SESSION['userid']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('User not found');
}

// Check if the user has the 'admin' role
$isAdmin = ($user['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inj3ctPractice</title>
    <link rel="stylesheet" href="#">
    <style>
        body {
            
            margin: 0;
            padding: 0;
            color: #fff; /* Ensure text is visible on dark background */
        }
        .container {
            display: flex;
            min-height: 100vh; /* Ensure the container spans the full height of the viewport */
        }
        .sidebar {
            width: 150px !important;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 450vh; /* Ensure the sidebar spans the full height of the viewport */
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            letter-spacing: 2px;
            line-height: 1;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            color: #000; /* Black text for better readability on light background */
            background-color: rgba(255, 255, 255, 0.9); /* Light background for content area */
            border-radius: 10px;
            margin: 20px;
            width: 100%;
        }
        .technique {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .technique h2 {
            margin-top: 0;
        }
        .button-group {
            margin-top: 20px;
        }
        .button-group button {
            margin-right: 10px;
            margin-bottom: 10px; /* Add margin bottom for spacing */
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .example {
            background-color: #333;
            padding: 5px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .example pre {
            margin: 0;
            color: #fff; /* Ensure code text is visible on dark background */
        }
        .create-quiz-button {
            margin-top: 20px;
            text-align: center;
        }
        .create-quiz-button a {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php 
    include 'topnav.php'; 
    ?>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="contentpage.php">SQL techniques</a></li>
                <li><a href="contentpage2.php">XSScript techniques</a></li>
            </ul>
        </div>       
        <div class="content">
            <div class="technique" id="technique1">
                <h2>Technique 1: SQL Injection Based on 1=1 is Always True</h2>
                <p>
                    This technique leverages the fact that the condition <code>1=1</code> is always true, allowing attackers to manipulate SQL queries to return all rows from a table.
                </p>
                <h3>Example</h3>
                <div class="example">
                    <pre><code>
txtUserId = getRequestString("UserId");
txtSQL = "SELECT * FROM Users WHERE UserId = " + txtUserId;

If 'txtUserId' is set to '105 OR 1=1', the query becomes:

SELECT * FROM users WHERE '1'='1';
                    </code></pre>
                </div>
                <h3>Mitigation</h3>
                <p>
                    To prevent this type of SQL injection, you should:
                </p>
                <ul>
                    <li>Use prepared statements and parameterized queries.</li>
                    <li>Implement proper input validation and sanitization.</li>
                    <li>Use stored procedures.</li>
                </ul>
                <h4>Example of Prepared Statement</h4>
                <div class="example">
                    <pre><code>
txtUserId = getRequestString("UserId");
sql = "SELECT * FROM Users WHERE UserId = ?";
preparedStmt = conn.prepareStatement(sql);
preparedStmt.setInt(1, Integer.parseInt(txtUserId));
resultSet = preparedStmt.executeQuery();
                    </code></pre>
                </div>
                <div class="button-group">
                    <form action="sqltry1.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="SQL Technique 1">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique2">
                <h2>Technique 2: SQL Injection Based on ""="" is Always True</h2>
                <p>
                    This technique uses the condition <code>""=""</code> which is always true, allowing attackers to gain unauthorized access to data.
                </p>
                <h3>How does it work?</h3>
                <p>
                    The “OR” “=” SQL injection is a method to take advantage of the fact the in SQL queries the input from user is not properly sanitized or escaped. By injecting an always-true condition the attacker can manipulate the query to return unintended results or bypass authentication mechanisms.
                </p>
                <h3>Injection Examples</h3>
                <p>An attacker could input the following:</p>
                <p>Username: ‘admin’ or ‘=’</p>
                <p>Password: ‘anything’</p>
                <h3>Resulting SQL Query</h3>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin' OR ''='' AND password = 'anything';
                    </code></pre>
                </div>
                <p>This effectively means that if the user inputs 'admin' or '=' which is always true, the attacker would gain access to the account with the username 'admin'.</p>
                <div class="example">
                    <pre><code>
uName = getRequestString("username");
uPass = getRequestString("userpassword");
sql = 'SELECT * FROM Users WHERE Name = "' + uName + '" AND Pass = "' + uPass + '"';

If 'uName' and 'uPass' are set to '=' or '='='', the query becomes:

SELECT * FROM Users WHERE Name = "" OR ""="" AND Pass = "" OR ""="";
                    </code></pre>
                </div>
                <h3>Mitigation</h3>
                <p>
                    To prevent this type of SQL injection, you should:
                </p>
                <ul>
                    <li>Use prepared statements and parameterized queries.</li>
                    <li>Implement proper input validation and sanitization.</li>
                    <li>Use stored procedures.</li>
                </ul>
                <h4>Example of Prepared Statement</h4>
                <div class="example">
                    <pre><code>
uName = getRequestString("username");
uPass = getRequestString("userpassword");
sql = "SELECT * FROM Users WHERE Name = ? AND Pass = ?";
preparedStmt = conn.prepareStatement(sql);
preparedStmt.setString(1, uName);
preparedStmt.setString(2, uPass);
resultSet = preparedStmt.executeQuery();
                    </code></pre>
                </div>
                <div class="button-group">
                    <form action="sqltry2.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="SQL Technique 2">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <!-- Add more technique sections here as needed -->
            <div class="technique" id="technique3">
                <h2>Technique 3: SQL Injection Based on Batched SQL Statements</h2>
                <p>
                    This method exploits the ability to run multiple SQL statements in a single query, potentially allowing an attacker to execute arbitrary commands.
                </p>
                <h3>Example</h3>
                <div class="example">
                    <pre><code>
txtUserId = getRequestString("UserId");
txtSQL = "SELECT * FROM Users WHERE UserId = " + txtUserId + "; DROP TABLE Students";

If 'txtUserId' is set to '105; DROP TABLE Students', the query becomes:

SELECT * FROM Users WHERE UserId = 105; DROP TABLE Students;
                    </code></pre>
                </div>
                <h3>Mitigation</h3>
                <p>
                    To prevent this type of SQL injection, you should:
                </p>
                <ul>
                    <li>Use prepared statements and parameterized queries.</li>
                    <li>Implement proper input validation and sanitization.</li>
                    <li>Use stored procedures.</li>
                </ul>
                <h4>Example of Prepared Statement</h4>
                <div class="example">
                    <pre><code>
txtUserId = getRequestString("UserId");
sql = "SELECT * FROM Users WHERE UserId = ?";
preparedStmt = conn.prepareStatement(sql);
preparedStmt.setInt(1, Integer.parseInt(txtUserId));
resultSet = preparedStmt.executeQuery();
                    </code></pre>
                </div>
                <div class="button-group">
                    <form action="sqltry3.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="SQL Technique 3">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
  
                        <div class="technique" id="technique4">
                <h2>Technique 4: SQL Injection Based on Blind SQL Injection</h2>
                <p>
                    This method is used when the attacker cannot see the result of the SQL query directly, but can infer information based on the behavior of the application.
                </p>
                <h3>Example</h3>
                <p>
                    Consider a web application that displays a generic error message when a query fails. An attacker can inject SQL that causes a query to fail and observe the application's response.
                </p>
                <h4>Injection Example</h4>
                <div class="example">
                    <pre><code>
Original Query:
SELECT * FROM Users WHERE UserId = '105';

Injection:
105' AND 1=1 -- (true condition, query succeeds)
105' AND 1=2 -- (false condition, query fails)
                    </code></pre>
                </div>
                <h4>Resulting Behavior</h4>
                <p>
                    The attacker can determine whether the injection was successful based on the application's response to each query.
                </p>
                <h3>Mitigation</h3>
                <p>
                    To prevent blind SQL injection, you should:
                </p>
                <ul>
                    <li>Use prepared statements and parameterized queries.</li>
                    <li>Implement proper input validation and sanitization.</li>
                    <li>Use web application firewalls (WAF) to detect and block SQL injection attempts.</li>
                </ul>
                <h4>Example of Prepared Statement</h4>
                <div class="example">
                    <pre><code>
txtUserId = getRequestString("UserId");
sql = "SELECT * FROM Users WHERE UserId = ?";
preparedStmt = conn.prepareStatement(sql);
preparedStmt.setInt(1, Integer.parseInt(txtUserId));
resultSet = preparedStmt.executeQuery();
                    </code></pre>
                </div>
                <div class="button-group">
                    <form action="sqltry4.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="SQL Technique 4">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            <div class="create-quiz-button">
                <?php
                if ($isAdmin) {
                    echo '<a href="create_quiz.php">Create Quiz</a>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
