<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>Donor Account</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .w3-main {
            background-color: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
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
        }

        .operation-links {
            margin: 20px 0;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 4px;
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

        iframe {
            border: 1px solid #e0e0e0;
            background: #ffffff;
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
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'HpSidebar.php'; ?>

    <div class="w3-main" style="margin-left:230px;margin-top:0px;">
        <div class="container">
            <h3><strong>Blood Request</strong></h3>
            
            <div class="operation-links">
                <a href="Request.php" target="contentFrame" class="btn btn-primary" onclick="showButton('request')" id="requestBtn">
                    <i class="fa fa-plus" style="margin-right: 5px;"></i>Request Blood
                </a>
                
                <a href="ViewRequests.php" target="contentFrame" class="btn btn-info" onclick="showButton('view')" id="viewBtn">
                    <i class="fa fa-eye" style="margin-right: 5px;"></i>View Blood Request
                </a>

                <a href="#" class="btn" id="backButton" onclick="resetButtons()">
                    <i class="fa fa-arrow-left" style="margin-right: 5px;"></i>Back
                </a>
            </div>

            <div id="welcomeMessage" class="welcome-message">
                <h4>Welcome to Blood Request Management</h4>
                <p>Please select an option above to proceed</p>
            </div>

            <iframe name="contentFrame" id="contentFrame" style="width: 100%; height: 600px; border: none;"></iframe>
        </div>
    </div>

    <div class="footer">
        @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
    </div>

    <script>
        // Show welcome message initially
        document.getElementById('welcomeMessage').style.display = 'block';
        document.getElementById('contentFrame').style.display = 'none';

        function showButton(buttonType) {
            const requestBtn = document.getElementById('requestBtn');
            const viewBtn = document.getElementById('viewBtn');
            const backButton = document.getElementById('backButton');
            const contentFrame = document.getElementById('contentFrame');
            const welcomeMessage = document.getElementById('welcomeMessage');

            // Hide welcome message and show iframe
            welcomeMessage.style.display = 'none';
            contentFrame.style.display = 'block';

            if (buttonType === 'request') {
                viewBtn.style.display = 'none';
                backButton.style.display = 'inline-block';
            } else if (buttonType === 'view') {
                requestBtn.style.display = 'none';
                backButton.style.display = 'inline-block';
            }
        }

        function resetButtons() {
            const requestBtn = document.getElementById('requestBtn');
            const viewBtn = document.getElementById('viewBtn');
            const backButton = document.getElementById('backButton');
            const contentFrame = document.getElementById('contentFrame');
            const welcomeMessage = document.getElementById('welcomeMessage');

            // Reset iframe src and hide it
            contentFrame.src = 'about:blank';
            contentFrame.style.display = 'none';

            // Show welcome message
            welcomeMessage.style.display = 'block';

            // Show all buttons except back button
            requestBtn.style.display = 'inline-block';
            viewBtn.style.display = 'inline-block';
            backButton.style.display = 'none';

            return false;
        }
    </script>
</body>
</html>