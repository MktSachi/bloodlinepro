<?php
session_start();
require '../../DonorRegistration/Database.php';
require 'Inventory.php';

$db = new Database();
$conn = $db->getConnection();

$inventory = new Inventory($conn);

$username = $_SESSION['username'] ?? '';
if (!empty($username)) {
    $result = $inventory->getBloodInventory($username);
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
    <title>Hospital Blood Inventory - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .dashboard-container {
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .card-title {
            color: #333;
            font-weight: 600;
        }
        .table {
            color: #333;
        }
        .table thead th {
            border-top: none;
            background-color: #f1f3f5;
        }
        .chart-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i class="fas fa-tint me-2"></i>Hospital Blood Inventory</h5>
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
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i class="fas fa-chart-bar me-2"></i>Blood Inventory Chart</h5>
                        <div class="chart-container">
                            <canvas id="bloodInventoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="theme-toggle">
        <button class="btn btn-primary" onclick="toggleDarkMode()">
            <i class="fas fa-moon"></i> Toggle Dark Mode
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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

        const lightModeURL = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
        const darkModeURL = 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/darkly/bootstrap.min.css';

        function toggleDarkMode() {
            const themeLink = document.getElementById('theme-link');
            if (themeLink.getAttribute('href') === lightModeURL) {
                themeLink.setAttribute('href', darkModeURL);
                document.body.style.backgroundColor = '#222';
            } else {
                themeLink.setAttribute('href', lightModeURL);
                document.body.style.backgroundColor = '#f8f9fa';
            }
        }
    </script>
</body>
</html>