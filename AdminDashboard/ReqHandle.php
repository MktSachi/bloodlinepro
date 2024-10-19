<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>Blood Requests</title>
    <link rel="stylesheet" href="../HpDashboard/Css/DonorHandle.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .w3-main {
            background-color: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-left: 230px;
        }
        .container {
            padding: 25px;
        }
        h3 {
            color: #333;
            font-size: 24px;
            padding-bottom: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
        }
        h3 i {
            color: #2c3e50;
            margin-right: 10px;
        }
        .operation-links {
            margin: 20px 0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
        .btn-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        .btn-primary:hover {
            background-color: #34495e;
            border-color: #34495e;
        }
        .btn-info {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-info:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        #content-loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .main-content {
            display: none;
        }
        iframe {
            border: 1px solid #e0e0e0;
            background: #ffffff;
            margin-top: 20px;
            width: 100%;
            height: 600px;
            display: none;
        }
        .footer {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
        }
        #backButton {
            background-color: #95a5a6;
            border-color: #95a5a6;
            color: white;
            display: none;
        }
        #backButton:hover {
            background-color: #7f8c8d;
            border-color: #7f8c8d;
        }
        .welcome-message {
            text-align: center;
            padding: 50px;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="w3-main">
        <!-- Content Loader -->
        <div id="content-loader">
            <div class="spinner"></div>
        </div>

        <div class="main-content">
            <div class="container">
                <h3><i class="fa fa-bars"></i><strong>Blood Requests</strong></h3>

                <div class="operation-links">
                    <a href="ViewRequest.php" target="contentFrame" class="btn btn-primary" onclick="showContent('ViewRequest.php', this)">
                        <i class="fa fa-eye" style="margin-right: 5px;"></i>View Requests
                    </a>
                    
                    <a href="FindReq.php" target="contentFrame" class="btn btn-info" onclick="showContent('FindReq.php', this)">
                        <i class="fa fa-eye" style="margin-right: 5px;"></i>Search Request
                    </a>
                    
                    <a href="#" class="btn" id="backButton" onclick="resetButtons()">
                        <i class="fa fa-arrow-left" style="margin-right: 5px;"></i>Back
                    </a>
                </div>

                <div id="welcomeMessage" class="welcome-message">
                    <h4>Welcome to Blood Requests Management</h4>
                    <p>Please select an option above to proceed</p>
                </div>

                <!-- Iframe to load content -->
                <iframe name="contentFrame" id="contentFrame"></iframe>
            </div>
        </div>
    </div>

    <div class="footer">
        @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('content-loader').style.display = 'none';
                document.querySelector('.main-content').style.display = 'block';
                document.getElementById('welcomeMessage').style.display = 'block'; // Show welcome message after loading
            }, 1500); // 1500 milliseconds = 1.5 seconds
        });

        function showContent(url, button) {
            const contentFrame = document.getElementById('contentFrame');
            const welcomeMessage = document.getElementById('welcomeMessage');
            const backButton = document.getElementById('backButton');

            // Hide welcome message and show iframe
            welcomeMessage.style.display = 'none';
            contentFrame.style.display = 'block';
            contentFrame.src = url;

            // Hide all other buttons except the clicked one
            const buttons = document.querySelectorAll('.operation-links .btn');
            buttons.forEach(btn => {
                if (btn !== button && btn !== backButton) {
                    btn.style.display = 'none'; // Hide other buttons
                }
            });

            // Show back button
            backButton.style.display = 'inline-flex';
        }

        function resetButtons() {
            const contentFrame = document.getElementById('contentFrame');
            const welcomeMessage = document.getElementById('welcomeMessage');
            const backButton = document.getElementById('backButton');

            // Reset iframe and show welcome message
            contentFrame.src = 'about:blank';
            contentFrame.style.display = 'none';
            welcomeMessage.style.display = 'block';

            // Show all main buttons again
            const buttons = document.querySelectorAll('.operation-links .btn');
            buttons.forEach(btn => {
                btn.style.display = 'inline-flex'; // Show all buttons
            });

            // Hide back button
            backButton.style.display = 'none';
        }
    </script>
</body>
</html>
