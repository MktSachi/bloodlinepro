<?php
session_start();
require '../DonorRegistration/Database.php';

if (!isset($_SESSION['hospitalID'])) {
    die("Hospital ID not set.");
}

$hospitalID = $_SESSION['hospitalID'];

$db = new Database();
$conn = $db->getConnection();

$lowStockBloodTypes = [];

$queryLowStock = "SELECT bloodType, quantity FROM hospital_blood_inventory 
                  WHERE hospitalID = ? AND quantity < 11";
$stmt = $conn->prepare($queryLowStock);
$stmt->bind_param("i", $hospitalID);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error fetching data: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $lowStockBloodTypes[] = [
        'bloodType' => htmlspecialchars($row['bloodType']),
        'quantity' => htmlspecialchars($row['quantity'])
    ];
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .inventory-container {
            margin: 40px auto;
            max-width: 1000px;
            padding: 30px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        .inventory-container h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e74c3c;
        }
        
        .blood-group {
            margin-bottom: 15px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }
        
        .blood-group:hover {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .blood-group h2 {
            font-size: 18px;
            color: #2c3e50;
            margin: 0;
            font-weight: 500;
        }
        
        .blood-group p {
            font-size: 16px;
            color: #e74c3c;
            margin: 5px 0 0;
            font-weight: 500;
        }
        
        .quantity-bar {
            height: 6px;
            background-color: #ecf0f1;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 8px;
            width: 100%;
        }
        
        .quantity-fill {
            height: 100%;
            background-color: #e74c3c;
            transition: width 0.5s ease;
        }
        
        .no-alerts {
            text-align: center;
            color: #27ae60;
            font-size: 18px;
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f6e9;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <?php include './HpSidebar.php'; ?>
    
    <div class="w3-main" style="margin-left:270px;margin-top:43px;">
        <div class="inventory-container">
            <h1>Blood Inventory Status</h1>
            
            <?php if (empty($lowStockBloodTypes)): ?>
                <p class="no-alerts">All blood types are currently at adequate levels.</p>
            <?php else: ?>
                <?php foreach ($lowStockBloodTypes as $bloodGroup): ?>
                    <div class="blood-group">
                        <div style="width: 100%;">
                            <h2>Blood Type: <?= $bloodGroup['bloodType'] ?></h2>
                            <p>Available: <?= $bloodGroup['quantity'] ?> units</p>
                            <div class="quantity-bar">
                                <div class="quantity-fill" style="width: <?= ($bloodGroup['quantity'] / 10) * 100 ?>%;"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('.blood-group').forEach((group, index) => {
                setTimeout(() => {
                    group.style.opacity = '1';
                    group.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>