<?php
session_start();
require '../Classes/Database.php';

if (!isset($_SESSION['hospitalID'])) {
    die("Hospital ID not set.");
}

$hospitalID = $_SESSION['hospitalID'];

$db = new Database();
$conn = $db->getConnection();

$lowStockBloodTypes = [];

$queryLowStock = "SELECT bloodType, quantity FROM hospital_blood_inventory 
                  WHERE hospitalID = ? AND quantity < 10";
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

// Set session variable for notifications
$_SESSION['lowStockCount'] = count($lowStockBloodTypes);
$_SESSION['lowStockNotifications'] = $lowStockBloodTypes;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory Status</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include './HpSidebar.php'; ?>
    
    <div class="container-fluid" style="margin-left:150px;margin-top:43px;">
        <div class="container mt-5 p-4 bg-white shadow rounded">
            <h1 class="mb-4 text-center">Blood Inventory Status</h1>
            
            <?php if (empty($lowStockBloodTypes)): ?>
                <div class="alert alert-success text-center" role="alert">
                    All blood types are currently at adequate levels.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($lowStockBloodTypes as $bloodGroup): ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h2 class="card-title">Blood Type: <?= $bloodGroup['bloodType'] ?></h2>
                                    <p class="card-text text-danger">Available: <?= $bloodGroup['quantity'] ?> units</p>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= ($bloodGroup['quantity'] / 10) * 100 ?>%;" aria-valuenow="<?= $bloodGroup['quantity'] ?>" aria-valuemin="0" aria-valuemax="10"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>