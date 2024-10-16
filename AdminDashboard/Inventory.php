<?php
session_start();
require_once '../Classes/Database.php';
require_once '../HpDashboard/BloodInventory/Inventory.php';

$db = new Database();
$conn = $db->getConnection();

$inventory = new Inventory($conn);

$hospitals = $inventory->getHospitals();

$selectedHospitalID = $_POST['hospital'] ?? null;
$bloodInventory = [];
$totalUnits = 0;

if ($selectedHospitalID) {
    $result = $inventory->getHospitalBloodInventory($selectedHospitalID);
    $bloodInventory = $result['inventory'];
    $totalUnits = $result['totalUnits'];
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        /* Loader styles */
        #content-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Content Loader -->
    <div id="content-loader">
        <div class="spinner"></div>
    </div>
    <?php include 'sidebar.php'; ?>

<!-- PAGE CONTENT -->
<div class="w3-main" style="margin-left:230px;margin-top:0px;">
    <div class="dashboard-container container">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-hospital me-2"></i>Select Hospital</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" id="hospital-form">
                            <div class="mb-3">
                                <select name="hospital" class="form-select" id="hospital-select">
                                    <option value="">--Select a hospital--</option>
                                    <?php foreach ($hospitals as $hospital): ?>
                                        <option value="<?= htmlspecialchars($hospital['hospitalID']) ?>" <?= $selectedHospitalID == $hospital['hospitalID'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($hospital['hospitalName']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">View Inventory</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($selectedHospitalID): ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tint me-2"></i>Hospital Blood Inventory</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Blood Type</th>
                                        <th>Quantity (ml)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bloodInventory as $bloodType => $quantity): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($bloodType) ?></td>
                                            <td><?= htmlspecialchars($quantity) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th>Total Units</th>
                                        <td><strong><?= htmlspecialchars($totalUnits) ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Blood Inventory Chart</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="bloodInventoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show loader when form is submitted
        document.getElementById('hospital-form').addEventListener('submit', function() {
            document.getElementById('content-loader').style.display = 'flex';
        });

        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            document.getElementById('content-loader').style.display = 'none';
        });

        <?php if ($selectedHospitalID): ?>
        const bloodTypes = <?= json_encode(array_keys($bloodInventory)) ?>;
        const quantities = <?= json_encode(array_values($bloodInventory)) ?>;

        // Chart.js Bar Chart
        var ctx = document.getElementById('bloodInventoryChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: bloodTypes,
                datasets: [{
                    label: 'Blood Quantity (ml)',
                    data: quantities,
                    backgroundColor: 'rgba(0, 123, 255, 0.6)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>