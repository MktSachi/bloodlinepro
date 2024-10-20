<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    
    <title>Blood Request</title>
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
            margin-top: 20px;
            width: 100%;
            height: 600px;
            display: none;
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
        @media (max-width: 768px) {
            .operation-links {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'HpSidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="w3-main" style="margin-left:240px;margin-top:0px;">
        <div class="container">
            <h3><i class="fa fa-tint" style="margin-right: 5px;"></i><strong>Blood Request</strong></h3>

            <!-- Operation links with icons -->
            <div class="operation-links">
                <a href="Request.php" target="contentFrame" class="btn btn-primary" id="requestBtn" onclick="showButton('request', 'Request.php')">
                    <i class="fa fa-plus" style="margin-right: 5px;"></i>Request Blood
                </a>

                <a href="ViewRequests.php" target="contentFrame" class="btn btn-info" id="viewRequestBtn" onclick="showButton('viewRequest', 'ViewRequests.php')">
                    <i class="fa fa-eye" style="margin-right: 5px;"></i>View Received Blood Request
                </a>

                <a href="BloodRequestReport.php" target="contentFrame" class="btn btn-primary" id="detailsBtn" onclick="showButton('details', 'BloodRequestReport.php')">
                    <i class="fa fa-tint" style="margin-right: 5px;"></i>Blood Request Details
                </a>

                <a href="ViewBloodRequest.php" target="contentFrame" class="btn btn-primary" id="ViewBtn" onclick="showButton('View', 'ViewBloodRequest.php')">
                    <i class="fa fa-tint" style="margin-right: 5px;"></i>View Request Blood Request
                </a>

                <a href="#" class="btn" id="backButton" onclick="resetButtons()">
                    <i class="fa fa-arrow-left" style="margin-right: 5px;"></i>Back
                </a>
            </div>

            <!-- Welcome message -->
            <div id="welcomeMessage" class="welcome-message">
                <h4>Welcome to Blood Request Management</h4>
                <p>Please select an option above to proceed</p>
            </div>

            <!-- Iframe to load content -->
            <iframe name="contentFrame" id="contentFrame"></iframe>
        </div>
    </div>

    <div class="footer">
        @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
    </div>

    <script>
        const buttonIds = ['requestBtn', 'viewRequestBtn', 'detailsBtn', 'ViewBtn'];

        function showButton(buttonType, url) {
            const contentFrame = document.getElementById('contentFrame');
            const welcomeMessage = document.getElementById('welcomeMessage');
            const backButton = document.getElementById('backButton');

            // Hide welcome message and show iframe
            welcomeMessage.style.display = 'none';
            contentFrame.style.display = 'block';
            contentFrame.src = url;

            // Hide all buttons except the clicked one
            buttonIds.forEach(btnId => {
                const button = document.getElementById(btnId);
                button.style.display = btnId === buttonType + 'Btn' ? 'inline-flex' : 'none';
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

            // Show all main buttons
            buttonIds.forEach(btnId => {
                document.getElementById(btnId).style.display = 'inline-flex';
            });

            // Hide back button
            backButton.style.display = 'none';

            return false;
        }
    </script>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>