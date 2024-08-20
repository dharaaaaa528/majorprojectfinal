<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot with Expand/Collapse Option</title>
    <style>
        .chatbot-container {
            width: 300px;
            position: fixed;
            bottom: 0;
            right: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #f9f9f9;
            overflow: hidden;
            z-index: 1000;
        }

        .chatbot-header {
            background-color: #56C2DD;
            padding: 10px;
            text-align: center;
            color: white;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-header button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .chatbot-content {
            display: none; /* Initially collapsed */
        }

        .chatbot-messages {
            padding: 10px;
            height: 300px;
            overflow-y: scroll;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .chatbot-input {
            width: 80%;
            padding: 10px;
            border: none;
            border-radius: 0;
        }

        .chatbot-send {
            width: 20%;
            padding: 10px;
            background-color: #56C2DD;
            border: none;
            color: white;
            cursor: pointer;
        }

        .chatbot-send:hover {
            background-color: #3b9db1;
        }

        /* Chatbot message text color */
        .chatbot-message {
            color: #2C3E50; /* Set chatbot text color here */
        }

        /* User message text color */
        .user-message {
            color: #1A5276; /* Set user text color here */
        }

        .chatbot-options {
            display: flex;
            flex-direction: column;
            padding: 10px;
            color:black;
        }

        .chatbot-option {
            margin-bottom: 5px;
        }

        /* Custom radio button styles */
        .chatbot-option input[type="radio"] {
            accent-color: #56C2DD; /* Custom radio button color */
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <!-- Chatbot UI -->
    <div id="chatbot" class="chatbot-container">
        <div id="chatbot-header" class="chatbot-header">
            <span>Chatbot</span>
            <button id="chatbot-toggle">Expand</button>
        </div>
        <div id="chatbot-content" class="chatbot-content">
            <div id="chatbot-messages" class="chatbot-messages"></div>
            <div class="chatbot-options">
                <label class="chatbot-option">
                    <input type="radio" name="chatbot-question" value="How do I reset my password?"> How do I reset my password?
                </label>
                <label class="chatbot-option">
                    <input type="radio" name="chatbot-question" value="How do I view my progress?"> How do I view my progress?
                </label>
                <label class="chatbot-option">
                    <input type="radio" name="chatbot-question" value="Why is the assessments locked?"> Why is the assessments locked?
                </label>
                <label class="chatbot-option">
                    <input type="radio" name="chatbot-question" value="How to request a name change for certifications?"> How to request a name change for certifications? 
                </label>
            </div>
            <input id="chatbot-input" type="text" class="chatbot-input" placeholder="Type a message...">
            <button id="chatbot-send" class="chatbot-send">Send</button>
        </div>
    </div>

    <script>
        // Toggle chatbot content visibility
        document.getElementById('chatbot-toggle').addEventListener('click', function () {
            var content = document.getElementById('chatbot-content');
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                this.textContent = 'Close';
            } else {
                content.style.display = 'none';
                this.textContent = 'Expand';
            }
        });

        // Handle radio button clicks
        document.querySelectorAll('input[name="chatbot-question"]').forEach(function (element) {
            element.addEventListener('change', function () {
                var message = this.value;

                if (message === '') return;

                appendMessage('You', message, 'user-message');

                // Simple chatbot response
                var response = getChatbotResponse(message);
                setTimeout(function () {
                    appendMessage('Chatbot', response, 'chatbot-message');
                }, 500);
            });
        });

        // Handle text input and send button click
        document.getElementById('chatbot-send').addEventListener('click', function () {
            var inputField = document.getElementById('chatbot-input');
            var message = inputField.value.trim();

            if (message === '') return;

            appendMessage('You', message, 'user-message');
            inputField.value = '';

            var response = getChatbotResponse(message);
            setTimeout(function () {
                appendMessage('Chatbot', response, 'chatbot-message');
            }, 500);
        });

        // Allow pressing Enter to send a message
        document.getElementById('chatbot-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                document.getElementById('chatbot-send').click();
            }
        });

        function appendMessage(sender, message, className) {
            var messagesContainer = document.getElementById('chatbot-messages');
            var messageElement = document.createElement('div');
            messageElement.classList.add('message');
            messageElement.classList.add(className); // Apply the color class
            messageElement.innerHTML = '<strong>' + sender + ':</strong> ' + message;
            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function getChatbotResponse(message) {
    // Convert message to lowercase for comparison
    message = message.toLowerCase();
    switch (message) {
        case 'hello':
            return 'Hello! How can I assist you today?';
        case 'how do i reset my password?':
            return 'To reset your password, go to the login page and click on the "Forgot Password" link. Follow the instructions provided to reset your password.';
        case 'help':
            return 'Sure! I am here to help you. What do you need assistance with?';
        case 'bye':
            return 'Goodbye! Have a great day!';
        case 'how do i view my progress?':
        	return 'Go to your username from the drop down click on Profile and from the side navigation click on progress.';
        case 'why is the assessments locked?':
        	return 'The relavent assessments would be unlocked after all the quizzes for SQL/XSS are completed.They would be locked again if you fail the test three times';
        case 'how to request a name change for certifications?':
            return 'You can send us a request from the certifications detail under profile.';
        default:
            return 'I am not sure how to respond to that. Can you ask something else?';
    	}
	}
        
    </script>
</body>
</html>
