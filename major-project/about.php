<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <link rel="stylesheet" href="#"> <!-- Link to your CSS file for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            height: 100%;
            color: white;
            background-color: rgba(0, 0, 0, 0.9); /* Black with 50% opacity */
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }
        html, body {
            height: 100%;
        }
        .content {
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Center vertically */
            align-items: center; /* Center horizontally */
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background for content */
            border-radius: 10px; /* Rounded corners for the content area */
            padding: 20px; /* Padding inside the content area */
        }
        .about-container {
            text-align: center;
            margin-bottom: 40px; /* Added margin for separation */
        }
        .about-container h1 {
            margin-bottom: 20px;
        }
        .team-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .team-member {
            text-align: center;
            margin-bottom: 20px; /* Added margin between team members */
        }
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .team-member p {
            margin-top: 10px;
            font-size: 16px;
        }
        .additional-content {
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php include 'topnav.php'; ?> <!-- Include the top navigation bar -->

    <div class="background"></div>
    <div class="overlay"></div>

    <div class="content">
        <div class="about-container">
            <h1>About Us</h1>
            <p>Welcome to our website! We are a team of passionate individuals dedicated to providing the best services and experiences for our users. Our mission is to empower individuals with the knowledge and skills to protect themselves and their organizations from cyber threats, with a focus on SQL Injection and XSS Injection vulnerabilities.</p>
        </div>
        
        <h2>Our Team</h2>
        <div class="team-container">
            <div class="team-member">
                <img src="dhara.jpg" alt="Team Member 1">
                <p>DHARA HARESH GANDHI</p>
            </div>
            <div class="team-member">
                <img src="darren.jpg" alt="Team Member 2">
                <p>LOKE WEI FUNG DARREN</p>
            </div>
            <div class="team-member">
                <img src="dharshini.jpg" alt="Team Member 3">
                <p>DHARSHINI SARAVANA KUMAR</p>
            </div>
            <div class="team-member">
                <img src="ilyas.jpg" alt="Team Member 4">
                <p>MUHAMMAD ILYAS</p>
            </div>
            <!-- Add more team members as needed -->
        </div>

        <!-- Additional Content -->
        <div class="additional-content">
            <h2>Our Mission</h2>
            <p>At our organization, we strive to create a safer digital world by educating and equipping individuals with the knowledge and tools to defend against cyber threats. Our goal is to provide comprehensive education on the prevention, detection, and mitigation of SQL Injection and XSS Injection attacks.</p>

            <h2>Our Values</h2>
            <ul>
                <li><strong>Integrity:</strong> We adhere to the highest ethical standards, ensuring honesty and fairness in all our actions.</li>
                <li><strong>Excellence:</strong> We pursue excellence in every aspect of our work, continuously improving and delivering high-quality results.</li>
                <li><strong>Innovation:</strong> We embrace creativity and seek innovative solutions to meet the evolving needs of our users.</li>
                <li><strong>Collaboration:</strong> We believe in the power of teamwork and collaboration to achieve our common goals.</li>
                <li><strong>Customer Focus:</strong> We are committed to understanding and exceeding the expectations of our users.</li>
            </ul>

            <h2>Our Services</h2>
            <p>We offer a range of educational services designed to help users understand and combat SQL Injection and XSS Injection attacks:</p>
            <ul>
                <li><strong>Comprehensive Tutorials:</strong> Step-by-step guides on identifying, preventing, and mitigating SQL Injection and XSS Injection vulnerabilities.</li>
                <li><strong>Interactive Workshops:</strong> Hands-on sessions that allow users to practice and reinforce their skills in a controlled environment.</li>
                <li><strong>Expert Webinars:</strong> Live sessions with cybersecurity professionals who share their insights and answer questions about the latest threats and defenses.</li>
                <li><strong>Resource Library:</strong> A collection of articles, videos, and tools to help users stay informed and prepared against cyber threats.</li>
            </ul>

            <h2>Contact Us</h2>
            <p>If you have any questions or would like to learn more about our services, please don't hesitate to contact us at [contact information].</p>

            <h2>Testimonials</h2>
            <p>Here's what our users have to say about us:</p>
            <blockquote>
                "This organization has transformed the way we approach our work. Their dedication and expertise are unmatched." - User A
            </blockquote>
            <blockquote>
                "We have seen significant improvements in our processes thanks to their innovative solutions." - User B
            </blockquote>
        </div>
    </div>
</body>
</html>




