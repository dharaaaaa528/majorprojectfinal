<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <style>
        :root {
            --container-background-light: #ffffff;
            --container-background-dark: #2c2c2c;
            --text-color-light: #333333;
            --text-color-dark: #ffffff;
            --button-background-light: #007bff;
            --button-background-dark: #0056b3;
            --button-text-light: #ffffff;
            --button-text-dark: #ffffff;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: var(--container-background-light);
            padding: 30px;
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            transition: background-color 0.3s, color 0.3s;
        }

        .dark-mode .container {
            background-color: var(--container-background-dark);
            color: var(--text-color-dark);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: inherit; /* Inherit color from container */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: inherit; /* Inherit color from container */
        }

        input[type="text"],
        input[type="number"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--button-background-light);
            color: var(--button-text-light);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: var(--button-background-light);
        }

        .dark-mode input[type="submit"] {
            background-color: var(--button-background-dark);
            color: var(--button-text-dark);
        }

        .dark-mode input[type="submit"]:hover {
            background-color: var(--button-background-dark);
        }

        .switch-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .switch {
            display: block;
            --width-of-switch: 3.5em;
            --height-of-switch: 2em;
            --size-of-icon: 1.4em;
            --slider-offset: 0.3em;
            position: relative;
            width: var(--width-of-switch);
            height: var(--height-of-switch);
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f4f4f5;
            transition: .4s;
            border-radius: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: var(--size-of-icon);
            width: var(--size-of-icon);
            border-radius: 20px;
            left: var(--slider-offset);
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(40deg, #ff0080, #ff8c00 70%);
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #303136;
        }

        input:checked + .slider:before {
            left: calc(100% - (var(--size-of-icon) + var(--slider-offset)));
            background: #303136;
            box-shadow: inset -3px -2px 5px -2px #8983f7, inset -10px -4px 0 0 #a3dafb;
        }

        .back-button-link {
            text-decoration: none; /* Remove underline from link */
        }

        .back-button {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            text-align: center;
            width: 200px;
            border: none;
            border-radius: 2em;
            height: 56px;
            font-family: sans-serif;
            color: #000000;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s;
            z-index: 1;
        }

        .back-button:hover {
            background-color: #f4f4f5;
        }

        .icon {
            background-color: #4caf50;
            border-radius: 1em;
            height: 40px;
            width: 25%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 5%;
            top: 50%;
            transform: translateY(-50%);
            transition: width 0.5s;
            z-index: 2;
        }

        .back-button:hover .icon {
            width: 90%;
        }

        .icon svg {
            fill: #000000;
        }

        .text {
            margin-left: 2em;
            z-index: 1;
            position: relative;
        }

        .question-container {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .remove-question {
            color: red;
            cursor: pointer;
            font-size: 14px;
        }

        .remove-question:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Quiz Questions</h2>
        <form id="quiz-form" action="store_quiz.php" method="post">
            <div class="form-group">
                <label for="quiz_id">Quiz ID:</label>
                <input type="number" id="quiz_id" name="quiz_id" required>
            </div>

            <div id="questions-container">
                <!-- Initial Question Block -->
                <div class="question-container">
                    <div class="form-group">
                        <label for="question_1">Question:</label>
                        <input type="text" id="question_1" name="questions[0][question]" required>
                    </div>

                    <div class="form-group">
                        <label for="option1">Option 1:</label>
                        <input type="text" id="option1" name="questions[0][option1]" required>
                    </div>

                    <div class="form-group">
                        <label for="option2">Option 2:</label>
                        <input type="text" id="option2" name="questions[0][option2]" required>
                    </div>

                    <div class="form-group">
                        <label for="option3">Option 3:</label>
                        <input type="text" id="option3" name="questions[0][option3]" required>
                    </div>

                    <div class="form-group">
                        <label for="option4">Option 4:</label>
                        <input type="text" id="option4" name="questions[0][option4]" required>
                    </div>

                    <div class="form-group">
                        <label for="correct_option_1">Correct Option (1-4):</label>
                        <input type="number" id="correct_option_1" name="questions[0][correct_option]" min="1" max="4" required>
                    </div>

                    <span class="remove-question" onclick="removeQuestion(this)">Remove Question</span>
                </div>
            </div>

            <button type="button" onclick="addQuestion()">Add Another Question</button>
            <input type="submit" value="Create Quiz Questions">
        </form>
        <div class="switch-container">
            <a href="contentpage.php" class="back-button-link">
                <button class="back-button">
                    <div class="icon">
                        <svg width="25px" height="25px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"></path>
                            <path fill="#000000" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"></path>
                        </svg>
                    </div>
                    <p class="text">Go Back</p>
                </button>
            </a>
            <label class="switch">
                <input type="checkbox" id="mode-toggle">
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <script>
        let questionIndex = 1; // Start indexing from 1

        function addQuestion() {
            questionIndex++;
            const container = document.getElementById('questions-container');
            const questionBlock = document.createElement('div');
            questionBlock.classList.add('question-container');

            questionBlock.innerHTML = `
                <div class="form-group">
                    <label for="question_${questionIndex}">Question:</label>
                    <input type="text" id="question_${questionIndex}" name="questions[${questionIndex - 1}][question]" required>
                </div>

                <div class="form-group">
                    <label for="option1_${questionIndex}">Option 1:</label>
                    <input type="text" id="option1_${questionIndex}" name="questions[${questionIndex - 1}][option1]" required>
                </div>

                <div class="form-group">
                    <label for="option2_${questionIndex}">Option 2:</label>
                    <input type="text" id="option2_${questionIndex}" name="questions[${questionIndex - 1}][option2]" required>
                </div>

                <div class="form-group">
                    <label for="option3_${questionIndex}">Option 3:</label>
                    <input type="text" id="option3_${questionIndex}" name="questions[${questionIndex - 1}][option3]" required>
                </div>

                <div class="form-group">
                    <label for="option4_${questionIndex}">Option 4:</label>
                    <input type="text" id="option4_${questionIndex}" name="questions[${questionIndex - 1}][option4]" required>
                </div>

                <div class="form-group">
                    <label for="correct_option_${questionIndex}">Correct Option (1-4):</label>
                    <input type="number" id="correct_option_${questionIndex}" name="questions[${questionIndex - 1}][correct_option]" min="1" max="4" required>
                </div>

                <span class="remove-question" onclick="removeQuestion(this)">Remove Question</span>
            `;

            container.appendChild(questionBlock);
        }

        function removeQuestion(element) {
            element.parentElement.remove();
        }

        const toggle = document.getElementById('mode-toggle');
        const container = document.querySelector('.container');

        toggle.addEventListener('change', () => {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</body>
</html>
