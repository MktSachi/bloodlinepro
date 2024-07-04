<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory Alert</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
        }
        
        .alert-container {
            margin: 20px auto;
            text-align: left;
            display: inline-block;
            max-width: 1200px;
            width: calc(100% - 40px);
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.2s ease;
        }
        
        .alert-container:hover {
            transform: scale(1.02);
        }

        .alert-container h1 {
            font-size: 2em;
            color: #f44336;
            margin-bottom: 10px;
        }

        .blood-group {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f8f8;
            border-left: 6px solid #f44336;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
            text-align: left;
        }

        .blood-group:hover {
            transform: translateX(5px);
        }

        .blood-group h2 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 5px;
        }

        .blood-group p {
            color: #777;
            margin: 0;
        }
    </style>
</head>
<body>
<?php include './HpSidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:270px;margin-top:43px;">
    <div class="alert-container">
        <h1>Attention: Blood Inventory is Low!</h1>

        <div class="blood-group">
            <h2>Blood Group: A+</h2>
            <p>Available Quantity: 15 units</p>
        </div>

        <div class="blood-group">
            <h2>Blood Group: B-</h2>
            <p>Available Quantity: 8 units</p>
        </div>

        <div class="blood-group">
            <h2>Blood Group: O+</h2>
            <p>Available Quantity: 10 units</p>
        </div>

        <div class="blood-group">
            <h2>Blood Group: AB-</h2>
            <p>Available Quantity: 3 units</p>
        </div>
    </div>
</div>
</body>
</html>
