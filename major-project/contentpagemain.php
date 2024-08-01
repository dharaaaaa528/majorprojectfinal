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
            width: 150px !important;
            background-color: black;
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 1070vh; /* Ensure the sidebar spans the full height of the viewport */
            border-right: 2px solid white;
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
            width: 100px;
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
    </style>
</head>
<body>
    <?php include 'topnavmain.php'; ?>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="contentpagemain.php">SQL techniques</a></li>
                <li><a href="contentpage2main.php">XSScript techniques</a></li>
            </ul>
        </div>       
        <div class="content">
            <div class="technique" id="technique1">
                <h2>Technique 1: SQL Injection Based on 1=1 is Always True</h2>
                <p>
                    This technique leverages the fact that the condition <code>1=1</code> is always true, allowing attackers to manipulate SQL queries to return all rows from a table.
                </p>
                <div class="example">
<pre><code>
txtUserId = getRequestString("UserId");
txtSQL = "SELECT * FROM Users WHERE UserId = " + txtUserId;

If 'txtUserId' is set to '105 OR 1=1', the query becomes:

SELECT * FROM users WHERE '1'='1';
                    </code></pre>
                    </div>
                    <h3>Example</h3>
                <p> 
Imagine you have a simple login form on a website where a user enters their username and password. The application takes these inputs and creates an SQL query to check if the credentials are correct.</p>
            
                
                <div class="example">
                    <pre><code>
SELECT * FROM users WHERE username = 'user' AND password = 'pass';
 </code></pre>
  </div>
 <p> In this query:<br>

'user' is the username entered by the user.<br>
'pass' is the password entered by the user. </p>
<p> Normal Execution
<br><br>
When a legitimate user logs in, the inputs are safe, and the query checks for a match in the database. If the credentials are correct, it returns the user’s data.

Injecting <code>1=1</code>
An attacker might enter something malicious instead of a normal username and password. <br><br> For example:

<code>Username: user' OR 1=1 --
Password: (left blank) </code>
<br><br>
This causes the query to look like this: </p>
<div class="example">
<pre><code>
SELECT * FROM users WHERE username = 'user' OR 1=1 --' AND password = '';

</code></pre>
</div>
<p>Breaking Down the Injection <br>
<code>user' </code>: Closes the username value.
<code>OR 1=1 </code>: This part always evaluates to true because <code>1=1 </code> is always true.
--: This is a comment indicator in SQL, which means everything after it is ignored. So, the rest of the query (including the password check) is ignored.
<br><br>
Result of the Injection
<br><br>
The modified query effectively becomes: </p>
<div class="example">
<pre><code>
SELECT * FROM users WHERE username = 'user' OR 1=1;
 </code></pre>
                </div>
<p>Since 1=1 is always true, the query will return all rows from the users table, potentially allowing the attacker to bypass authentication and log in without knowing the actual password.</p>
<h3>Why is This Dangerous?</h3>
<p>

Bypass Authentication: Attackers can gain unauthorized access to the system.<br>
Data Exposure: Attackers can retrieve all data from the users table.<br>
Potential Data Manipulation: If the attacker can execute queries, they might also insert, update, or delete data. </p>

                
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
                    <form action="sqltry1main.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="SQL Technique 1">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
            <div class="technique" id="technique2">
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
                <h3>Example Scenario</h3>
                <p>Imagine you have a simple login form on a website where a user enters their username and password. The application takes these inputs and creates an SQL query to check if the credentials are correct.<br><br> Here’s a simple version of what that query might look like:</p>
                <div class="example">
                    <pre><code>
SELECT * FROM users WHERE username = 'user' AND password = 'pass';
                     </code></pre>
                </div>
                <p>In this query:
<br>
<code>'user'</code> is the username entered by the user.<br>
<code>'pass'</code> is the password entered by the user.</p>
<p>Normal Execution <br>
When a legitimate user logs in, the inputs are safe, and the query checks for a match in the database. If the credentials are correct, it returns the user’s data.
<br><br>
Injecting ""="" <br>
An attacker might enter something malicious instead of a normal username and password. <br>For example:
<br>
Username: <code>user"</code> OR <code>""="</code><br>
Password: (left blank)</p>
<p>This causes the query to look like this:</p>
<div class="example">
                    <pre><code>
SELECT * FROM users WHERE username = 'user" OR ""="' AND password = '';

                     </code></pre>
                </div>
                <p>Breaking Down the Injection<br>
1.<code>user" </code>: Closes the username value.<br>
2.<code>OR ""=" </code>: This part always evaluates to true because ""="" is always true.<br>
3.<code>AND password = '';</code>: This part is ignored because of the <code>OR ""=""</code>.</p>
<p>Result of the Injection<br>
The modified query effectively becomes:</p>
<div class="example">
                    <pre><code>
SELECT * FROM users WHERE username = 'user" OR ""="';
 </code></pre>
                </div>
            <p>Since <code>""="" </code>is always true, the query will return all rows from the <code>users</code> table, potentially allowing the attacker to bypass authentication and log in without knowing the actual password.</p>
<h3>Why is This Dangerous?</h3>
<p>1.Bypass Authentication: Attackers can gain unauthorized access to the system.<br>
2.Data Exposure: Attackers can retrieve all data from the users table.<br>
3.Potential Data Manipulation: If the attacker can execute queries, they might also insert, update, or delete data.</p>
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
                    <form action="sqltry2main.php" method="get" style="margin: 0;">
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
                <h3>What are Batched SQL Statements?</h3>
                <p>Batched SQL statements are multiple SQL commands combined into a single string and sent to the database for execution. This can be efficient, but it also opens up a risk for SQL injection if not handled properly.</p>
                <h3>How Does SQL Injection with Batched Statements Work?</h3>
                <p>
                When an application concatenates user input directly into a batch of SQL statements, an attacker can inject additional SQL commands. This can lead to executing unintended SQL statements.
                </p>
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
                <h3>Example Scenario</h3>
                <p>Let's say you have an application that updates user information and logs the update in the database. The application might construct a batched SQL statement like this:</p>
                <div class="example">
                    <pre><code>
UPDATE users SET email = 'newemail@example.com' WHERE id = 1; INSERT INTO log (action) VALUES ('Updated email');

                    </code></pre>
                </div>
                <p>Normal Execution <br>
When a legitimate user updates their email, the batched SQL statement runs multiple commands:
<br><br>
1.Update the user's email.<br>
2.Log the update action.</p>
<h3>Injecting Malicious SQL</h3>
<p>An attacker can exploit this if user input is not properly sanitized. Suppose the application takes user input for the email and constructs the SQL statement like this:</p>
<div class="example">
                    <pre><code>
$email = $_POST['email']; // User-provided email
$sql = "UPDATE users SET email = '$email' WHERE id = 1; INSERT INTO log (action) VALUES ('Updated email');";
$conn->query($sql);
 </code></pre>
                </div>
                <h3>Malicious Input</h3>
                <p>An attacker might provide an email input like this:</p>
                <div class="example">
                    <pre><code>
newemail@example.com'; DROP TABLE users; --
 </code></pre>
                </div>
               <p>This input causes the SQL statement to be:</p>
               <div class="example">
                    <pre><code>
UPDATE users SET email = 'newemail@example.com'; DROP TABLE users; --' WHERE id = 1; INSERT INTO log (action) VALUES ('Updated email');
 </code></pre>
                </div>
                <p>Breaking Down the Injection <br>
1.<code>UPDATE users SET email = 'newemail@example.com';</code>: This part updates the email as expected.<br>
2.<code>DROP TABLE users;:</code> This part drops the <code>users</code>code table, deleting all user data.<br>
3.<code>--' WHERE id = 1; INSERT INTO log (action) VALUES ('Updated email');</code>: This part is commented out and ignored because of the <code>--</code> (SQL comment syntax).<br><br>

Result of the Injection<br>
The injected <code>DROP TABLE users;</code> command is executed, leading to the deletion of the entire <code>users</code> table.</p>

<h3>Why is This Dangerous?</h3>
<p>1.Data Deletion: Attackers can delete critical tables.<br>
2.Data Manipulation: Attackers can insert, update, or delete data maliciously.<br>
3.Unauthorized Access: Attackers can execute commands that they shouldn't have permission to run.</p>
               
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
                    <form action="sqltry3main.php" method="get" style="margin: 0;">
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
                Blind SQL injection is a type of SQL injection attack where the attacker is unable to see the direct results of their payloads. Instead of retrieving data directly from the database, the attacker infers information based on the application's behavior and responses to crafted SQL queries. This method can be time-consuming but is very effective when the application does not return error messages or query results.
                </p>
                <h3>Types of Blind SQL Injection</h3>
                <p>
                1. Boolean-based Blind SQL Injection:</p>

<li>This technique relies on sending different payloads to the server and observing changes in the application's response to infer information.</li>
<li>The attacker sends queries that result in a true or false response and observes how the application behaves (e.g., different content or error messages).</li>
<p>2. Time-based Blind SQL Injection:</p>

<li>This technique uses time delays to infer information from the database.</li>
<li>The attacker sends queries that cause the database to wait for a specified time before responding, and the response time indicates whether the condition in the query was true or false.</li>
                
                <p>
                    Blind SQL Injection method is used when the attacker cannot see the result of the SQL query directly, but can infer information based on the behavior of the application.
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
                <h3>Example Scenario</h3>
                <p>Consider a vulnerable login form with the following SQL query:</p>
                <div class="example">
                    <pre><code>
SELECT * FROM users WHERE username = 'user' AND password = 'pass';

                    </code></pre>
                </div>
                
                <h3>Boolean-based Blind SQL Injection</h3>
                <p>An attacker wants to determine if the application is vulnerable and whether a user with the username "admin" exists.</p>
                <p>1. True Condition:</p>
                <li>Input: <code>admin' </code> AND <code>'1'='1</code></li>
                <li>Resulting Query:<code> SELECT * FROM users WHERE username = 'admin' AND '1'='1' AND password = 'pass';</code></li>
                <li>If <code>"admin"</code> exists, the query is true and the application behaves normally.</li>
                
                <p>2. False Condition:</p>
                <li>Input:<code> admin'</code> AND<code>'1'='2</code> </li>
                <li>Resulting Query:<code> SELECT * FROM users WHERE username = 'admin' AND '1'='2' AND password = 'pass';</code></li>
                <li>The query is false and the application behaves differently (e.g., an error message or a different page).</li>
                <p>By comparing responses, the attacker can infer whether the username "admin" exists.</p>
                <h3>Time-based Blind SQL Injection</h3>
                <p>An attacker can infer information based on the response time of the application.</p>
                <p>1.If the user <code>"admin"</code> exists:</p>
                <li>Input:<code> admin' AND IF((SELECT COUNT(*) FROM users WHERE username = 'admin') > 0, SLEEP(5), 0) -- </code></li>
                <li>Resulting Query:<code> SELECT * FROM users WHERE username = 'admin' AND IF((SELECT COUNT(*) FROM users WHERE username = 'admin') > 0, SLEEP(5), 0) AND password = 'pass';</code></li>
                <li>If "admin" exists, the query causes a 5-second delay.</li>
                <p>2.If the user "admin" does not exist:</p>
                <li>The application responds immediately, indicating the username does not exist.</li>
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
                    <form action="sqltry4main.php" method="get" style="margin: 0;">
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


