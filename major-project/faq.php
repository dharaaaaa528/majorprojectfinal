<?php
require_once 'header.php';
require_once 'config.php';
require_once 'topnav.php';
// Handle feedback form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $feedback = trim($_POST['feedback']);

    if (!empty($name) && !empty($email) && !empty($feedback)) {
        $sql = "INSERT INTO feedback (name, email, feedback) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $name, $email, $feedback);
            if ($stmt->execute()) {
                $successMessage = "Thank you for your feedback!";
            } else {
                $errorMessage = "There was an error submitting your feedback. Please try again.";
            }
            $stmt->close();
        }
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ & Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #000; /* Ensures all text within faq-container is black */
        }

        .faq-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .question {
            font-weight: bold;
            margin-top: 20px;
        }

        .answer {
            margin-bottom: 10px;
        }

        .feedback-container {
            margin-top: 40px;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            color: #000; /* Ensures all text within feedback-container is black */
        }

        .feedback-container h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .feedback-container input, .feedback-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .feedback-container button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .feedback-container button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 20px;
            color: green;
            font-weight: bold;
        }

        .error-message {
            margin-bottom: 20px;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>

        <div class="question">Q1: How do I reset my password?</div>
        <div class="answer">A1: To reset your password, go to the login page and click on the "Forgot Password" link. Follow the instructions provided to reset your password.</div>

        <div class="question">Q2: How do I change my account information?</div>
        <div class="answer">A2: You can change your account information by logging into your account and navigating to the "Account Settings" section.</div>

        <div class="question">Q3: What should I do if I encounter an error on the website?</div>
        <div class="answer">A3: If you encounter an error, please contact our support team through the "Contact Us" page or submit your feedback below.</div>

        <div class="question">Q4: How do I access the quizzes and tests?</div>
        <div class="answer">A4: After logging in, you can access quizzes and tests from the "Tests" section on the main dashboard.</div>

        <div class="question">Q5: Can I retake a test?</div>
        <div class="answer">A5: Yes, you can retake tests. However, some tests may have specific conditions for retaking, such as a waiting period or limited attempts.</div>

        <div class="question">Q6: How do I navigate through the website and begin learning?</div>
        <div class="answer">A6: To begin, log into your account and navigate to the "Content" section where you can start learning. After completing the learning material, proceed to the "Quizzes" section to assess your knowledge. Once you've passed the quizzes, move on to the "Tests" section. After successfully completing all required tests, you can obtain your certificate from the "Certificates" section.</div>

        <div class="question">Q7: What should I do if I forget my username?</div>
        <div class="answer">A7: If you forget your username, please contact our support team through the "Contact Us" page for assistance.</div>

        <div class="question">Q8: How do I update my email address?</div>
        <div class="answer">A8: You can update your email address in the "Account Settings" section after logging in.</div>

        <div class="question">Q9: How can I change my password?</div>
        <div class="answer">A9: To change your password, go to the "Account Settings" section after logging in and follow the instructions to update your password.</div>

        <div class="question">Q10: What is the passing score for the quizzes?</div>
        <div class="answer">A10: The passing score for each quiz is displayed on the quiz page before you begin. Ensure you achieve the required score to proceed to the next step.</div>

        <div class="question">Q11: How do I track my progress?</div>
        <div class="answer">A11: You can track your progress from the dashboard, which displays your progress bar and completed modules, quizzes, and tests.</div>

        <div class="question">Q12: What should I do if a quiz or test does not load?</div>
        <div class="answer">A12: If a quiz or test does not load, try refreshing the page. If the issue persists, please contact our support team for assistance.</div>

        <div class="question">Q13: Can I skip learning content and directly take the tests?</div>
        <div class="answer">A13: No, you must complete the learning content and quizzes before you can access the tests.</div>

        <div class="question">Q14: What if I fail a test?</div>
        <div class="answer">A14: If you fail a test, you may be allowed to retake it depending on the test's conditions. Check the test page for specific retake policies.</div>

        <div class="question">Q15: How do I download my certificate?</div>
        <div class="answer">A15: After passing all required tests, you can download your certificate from the "Certificates" section on your dashboard.</div>

        <div class="question">Q16: Can I share my certificate on social media?</div>
        <div class="answer">A16: Yes, once you download your certificate, you can share it on social media platforms.</div>

        <div class="question">Q17: Is there a time limit for completing the quizzes?</div>
        <div class="answer">A17: Some quizzes may have a time limit, which will be indicated before you start the quiz.</div>

        <div class="question">Q18: What if I encounter technical issues during a test?</div>
        <div class="answer">A18: If you encounter technical issues during a test, contact our support team immediately for assistance.</div>

        <div class="question">Q19: How do I provide feedback about the course?</div>
        <div class="answer">A19: You can provide feedback by filling out the feedback form at the bottom of this page.</div>

        <div class="question">Q20: Can I access the content on my mobile device?</div>
        <div class="answer">A20: Yes, our website is mobile-friendly, and you can access all content, quizzes, and tests on your mobile device.</div>

        <div class="question">Q21: What browsers are supported?</div>
        <div class="answer">A21: Our website supports all major browsers, including Chrome, Firefox, Safari, and Edge. Ensure your browser is up-to-date for the best experience.</div>

        <div class="question">Q22: How do I enable dark mode?</div>
        <div class="answer">A22: You can enable dark mode from the settings section on your dashboard. This will switch the website's theme to dark mode for better visibility.</div>

        <div class="question">Q23: What is the maximum number of attempts allowed for a test?</div>
        <div class="answer">A23: The maximum number of attempts for a test is typically set by the course administrator. Check the test page or course guidelines for specific details on the number of allowed attempts.</div>

        <div class="question">Q24: How do I contact support?</div>
        <div class="answer">A24: You can contact support through the "Contact Us" page on our website. Our team will get back to you as soon as possible.</div>

        <div class="question">Q25: Can I change my course after enrolling?</div>
        <div class="answer">A25: Course changes can be made depending on the policies set by the course administrator. Check the course enrollment page or contact support for more details.</div>

        <div class="question">Q26: What should I do if I encounter a broken link?</div>
        <div class="answer">A26: If you encounter a broken link, please report it through the "Contact Us" page so we can fix it as soon as possible.</div>

        <div class="question">Q27: How do I access my learning materials?</div>
        <div class="answer">A27: After logging in, you can access your learning materials from the "Content" section on your dashboard.</div>

        <div class="question">Q28: Are there any prerequisites for taking the tests?</div>
        <div class="answer">A28: Some tests may have prerequisites such as completing certain modules or quizzes. Check the test page for specific requirements.</div>

        <div class="question">Q29: How do I update my personal information?</div>
        <div class="answer">A29: You can update your personal information in the "Account Settings" section after logging in.</div>

        <div class="question">Q30: Can I get a refund if I am not satisfied with the course?</div>
        <div class="answer">A30: Refund policies vary by course. Please review the refund policy on the course enrollment page or contact support for more information.</div>

        <div class="feedback-container">
            <h3>Submit Your Feedback</h3>
            <?php
            if (isset($successMessage)) {
                echo "<div class='message'>$successMessage</div>";
            }
            if (isset($errorMessage)) {
                echo "<div class='error-message'>$errorMessage</div>";
            }
            ?>
            <form action="" method="post">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="feedback" rows="5" placeholder="Your Feedback" required></textarea>
                <button type="submit">Submit Feedback</button>
            </form>
        </div>
    </div>
</body>
</html>


