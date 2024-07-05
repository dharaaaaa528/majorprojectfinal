<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inj3ctPractice</title>
    <link rel="stylesheet" href="contentpage.css">
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff; /* Ensure text is visible on dark background */
        }
        .container {
            display: flex;
            min-height: 100vh; /* Ensure the container spans the full height of the viewport */
        }
        .sidebar {
            width: 200px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100vh; /* Ensure the sidebar spans the full height of the viewport */
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
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            color: #000; /* Black text for better readability on light background */
            background-color: rgba(255, 255, 255, 0.9); /* Light background for content area */
            border-radius: 10px;
            margin: 20px;
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
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .example pre {
            margin: 0;
            color: #fff; /* Ensure code text is visible on dark background */
        }
    </style>
</head>
<body>
    <?php include 'topnavmain.php'; ?>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="#">Profile</a></li>
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

If 'uName' and 'uPass' are set to '=' or '='=', the query becomes:

SELECT * FROM Users WHERE Name = "" OR ""="" AND Pass = "" OR ""="";
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
            <div class="technique" id="technique3">
                <h2>Technique 3: SQL Injection Based on Batched SQL Statements</h2>
                <p>
                    This method exploits the ability to run multiple SQL statements in a single query, potentially performing destructive actions like dropping tables.
                </p>
                <h3>Example</h3>
                <div class="example">
                    <pre><code>
txtUserId = getRequestString("UserId");
txtSQL = "SELECT * FROM Users WHERE UserId = " + txtUserId;

If 'txtUserId' is set to '105; DROP TABLE Suppliers', the query becomes:

SELECT * FROM Users WHERE UserId = 105; DROP TABLE Suppliers;
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
                <h2>Technique 4: Comment-Based SQL Injection</h2>
                <p>
                    Comment-based SQL injection is a technique used by attackers to manipulate SQL queries by injecting comments into the code. This method exploits vulnerabilities in an application’s handling of SQL queries by including SQL comment syntax to manipulate the intended query structure. This can effectively alter the intended query execution, potentially giving attackers unauthorized access to the database.
                </p>
                <h3>How It Works</h3>
                <p>
                    <ul>
                        <li>SQL Comment Syntax: SQL comments can be added using -- (double dash) for single-line comments or /* ... */ for multi-line comments.</li>
                    </ul>
                </p>
                <h3>Example of a Vulnerable Query</h3>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin' AND password = 'password';
                    </code></pre>
                </div>
                <h3>Injection Technique</h3>
                <p>
                    An attacker might input the following into a login form:
                </p>
                <p>Username: admin --</p>
                <p>Password: (leave blank)</p>
                <h3>Resulting Query</h3>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin' -- AND password = '';
                    </code></pre>
                </div>
                <p>Here, -- comments out the rest of the query, turning it into:</p>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin';
                    </code></pre>
                </div>
                <p>Since '1'='1' is always true, this query bypasses authentication.</p>
                <h3>Detailed Examples</h3>
                <h4>Basic Authentication Bypass</h4>
                <p>Original input fields:</p>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin' AND password = 'password';
                    </code></pre>
                </div>
                <h4>Resulting query:</h4>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin' -- AND password = '';
                    </code></pre>
                </div>
                <h4>Multi-line Comments</h4>
                <p>Using multi-line comments to bypass security checks:</p>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin' AND password = 'password' /* login bypass */;
                    </code></pre>
                </div>
                <h4>Resulting query:</h4>
                <div class="example">
                    <pre><code>
SELECT * FROM Users WHERE username = 'admin' /* login bypass */;
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
            </div>
        </div>
    </div>
</body>
</html>


