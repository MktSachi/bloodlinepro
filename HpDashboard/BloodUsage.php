<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>Blood Usage</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .w3-main {
            background-color: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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
        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
            color: white;
        }
        .btn-dark:hover {
            background-color: #23272b;
            border-color: #1d2124;
        }
        .btn-back {
            background-color: #95a5a6;
            border-color: #95a5a6;
            color: white;
            display: none;
        }
        .btn-back:hover {
            background-color: #7f8c8d;
            border-color: #7f8c8d;
            color: white;
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
            border: none;
            width: 100%;
            height: 600px;
            margin-top: 20px;
            display: none; /* Initially hidden */
        }
        .welcome-message {
            text-align: center;
            padding: 50px;
            color: #666;
            display: block; /* Initially displayed */
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
    <?php include './HpSidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="w3-main" style="margin-left:230px;margin-top:0px;">
        <div class="container">
            <h3><strong>Blood Usage</strong></h3>
            
            <!-- Operation links with dark button for Patient -->
            <div class="operation-links">
                <a href="HandleBloodUsage/BloodUsagePatient.php" target="contentFrame" class="btn btn-dark" id="patientBtn" onclick="showButton('patient', 'HandleBloodUsage/BloodUsagePatient.php')">
                    <i class="fa fa-user-injured" style="margin-right: 5px;"></i> Patient
                </a>

                <a href="#" class="btn btn-back" id="backButton" onclick="resetButtons()">
                    <i class="fa fa-arrow-left" style="margin-right: 5px;"></i> Back
                </a>
            </div>

            <!-- Welcome message -->
            <div id="welcomeMessage" class="welcome-message">
                <h4>Welcome to Blood Usage Management</h4>
                <p>Please select an option above to proceed</p>
            </div>

            <!-- Iframe to load content -->
            <iframe name="contentFrame" id="contentFrame"></iframe>
        </div>
    </div>

    <div class="footer">
        @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
    </div>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const buttonIds = ['patientBtn'];

        function showButton(buttonType, url) {
            const contentFrame = document.getElementById('contentFrame');
            const welcomeMessage = document.getElementById('welcomeMessage');
            const backButton = document.getElementById('backButton');

            // Hide welcome message and show iframe
            welcomeMessage.style.display = 'none';
            contentFrame.style.display = 'block';
            contentFrame.src = url;

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

            // Hide back button
            backButton.style.display = 'none';
        }
    </script>
</body>
</html>
