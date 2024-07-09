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
            color: white;
            padding: 0;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .background {
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background overlay */
            z-index: -1;
        }

        .about-container {
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin: 20px;
            position: relative;
            z-index: 1;
        }

        .about-container h1 {
            margin-bottom: 20px;
        }

        .team-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .team-member {
            text-align: center;
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
    </style>
</head>
<body>
    <?php include 'topnav.php'; ?> <!-- Include the top navigation bar -->

    <div class="background"></div>
    <div class="overlay"></div>

    <div class="about-container">
        <h1>About Us</h1>
        <p>Welcome to our website! We are a team of passionate individuals dedicated to providing the best services and experiences for our users. Our mission is to [your mission statement here].</p>
        
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
                <img src="ilyas.jpg" alt="Team Member 3">
                <p>MUHAMMAD ILYAS S-O SEGU ABUBACKER</p>
            </div>
            <!-- Add more team members as needed -->
        </div>
    </div>
</body>
</html>

