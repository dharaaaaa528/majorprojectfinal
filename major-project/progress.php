<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f2f2f2;
            padding: 20px;
        }
        .progress-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .progress-title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .progress-bar {
            width: 100%;
            background-color: #ddd;
            height: 30px;
            border-radius: 4px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .progress-bar-inner {
            height: 100%;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
        }
        .progress-label {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <h2 class="progress-title">Progress</h2>
        
        <!-- SQL Techniques Progress -->
        <div class="progress-label">SQL Techniques</div>
        <div class="progress-bar sql-techniques">
            <div class="progress-bar-inner" style="width: 0%;">0%</div>
        </div>
        
        <!-- SQL Quizzes Progress -->
        <div class="progress-label">SQL Quizzes</div>
        <div class="progress-bar sql-quizzes">
            <div class="progress-bar-inner" style="width: 0%;">0%</div>
        </div>
        
        <!-- SQL Tests Progress -->
        <div class="progress-label">SQL Tests</div>
        <div class="progress-bar sql-tests">
            <div class="progress-bar-inner" style="width: 0%;">0%</div>
        </div>
        
        <!-- Script Techniques Progress -->
        <div class="progress-label">Script Techniques</div>
        <div class="progress-bar script-techniques">
            <div class="progress-bar-inner" style="width: 0%;">0%</div>
        </div>
        
        <!-- Script Quizzes Progress -->
        <div class="progress-label">Script Quizzes</div>
        <div class="progress-bar script-quizzes">
            <div class="progress-bar-inner" style="width: 0%;">0%</div>
        </div>
        
        <!-- Script Tests Progress -->
        <div class="progress-label">Script Tests</div>
        <div class="progress-bar script-tests">
            <div class="progress-bar-inner" style="width: 0%;">0%</div>
        </div>
    </div>

    <script>
        // Simulated completion percentages (can be replaced with actual data)
        const progress = {
            'sqlTechniques': 75,
            'sqlQuizzes': 50,
            'sqlTests': 25,
            'scriptTechniques': 60,
            'scriptQuizzes': 40,
            'scriptTests': 20
        };

        // Function to update progress bars
        function updateProgressBars() {
            // SQL Techniques
            document.querySelector('.sql-techniques .progress-bar-inner').style.width = `${progress.sqlTechniques}%`;
            document.querySelector('.sql-techniques .progress-bar-inner').textContent = `${progress.sqlTechniques}%`;

            // SQL Quizzes
            document.querySelector('.sql-quizzes .progress-bar-inner').style.width = `${progress.sqlQuizzes}%`;
            document.querySelector('.sql-quizzes .progress-bar-inner').textContent = `${progress.sqlQuizzes}%`;

            // SQL Tests
            document.querySelector('.sql-tests .progress-bar-inner').style.width = `${progress.sqlTests}%`;
            document.querySelector('.sql-tests .progress-bar-inner').textContent = `${progress.sqlTests}%`;

            // Script Techniques
            document.querySelector('.script-techniques .progress-bar-inner').style.width = `${progress.scriptTechniques}%`;
            document.querySelector('.script-techniques .progress-bar-inner').textContent = `${progress.scriptTechniques}%`;

            // Script Quizzes
            document.querySelector('.script-quizzes .progress-bar-inner').style.width = `${progress.scriptQuizzes}%`;
            document.querySelector('.script-quizzes .progress-bar-inner').textContent = `${progress.scriptQuizzes}%`;

            // Script Tests
            document.querySelector('.script-tests .progress-bar-inner').style.width = `${progress.scriptTests}%`;
            document.querySelector('.script-tests .progress-bar-inner').textContent = `${progress.scriptTests}%`;
        }

        // Call updateProgressBars() when the page loads
        window.onload = function() {
            updateProgressBars();
        };
    </script>
</body>
</html>

