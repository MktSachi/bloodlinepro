<?php

session_start();


require '../../Classes/Database.php';
require 'Inventory.php';


$db = new Database();
$conn = $db->getConnection();


$inventory = new Inventory($conn);
$username = $_SESSION['username'] ?? '';


if (!empty($username)) {
    $inventoryReport = $inventory->getBloodInventory($username);
    $_SESSION['inventoryReport'] = $inventoryReport;
}


if (method_exists($db, 'close')) {
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Inventory Report - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .table {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
<div class="container dashboard-container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Hospital Blood Inventory Report</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($inventoryReport['inventory'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Blood Type</th>
                                    <th>Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($inventoryReport['inventory'] as $bloodType => $quantity): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($bloodType) ?></td>
                                        <td><?= htmlspecialchars($quantity) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <strong>Total Units:</strong> <?= htmlspecialchars($inventoryReport['totalUnits']) ?>
                            </div>
                        </div>
                        <a href="DownloadInventory.php?download=html" class="btn btn-success mt-3">
                            <i class="fas fa-download me-2"></i>Download Report
                        </a>
                    <?php else: ?>
                        <p>No inventory data available for your hospital.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
