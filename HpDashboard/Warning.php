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
                  WHERE hospitalID = ? AND quantity < 3500";
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

$_SESSION['lowStockCount'] = count($lowStockBloodTypes);
$_SESSION['lowStockNotifications'] = $lowStockBloodTypes;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .w3-main {
            margin-left: 230px;
            margin-top: 0px;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        }
        .card-body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 1.5rem;
        }
        .card-title {
            color: #dc3545;
            font-weight: bold;
            font-size: 1.8rem;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }
        .header h1 {
            font-weight: 700;
            font-size: 2.2rem;
        }
        .chart-container {
            position: relative;
            margin: auto;
            height: 200px;
            width: 200px;
        }
        .blood-status {
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 1rem;
        }
        .alert-custom {
            background-color: #ffc107;
            color: #212529;
            border: none;
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .w3-main {
                margin-left: 0;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <?php include './HpSidebar.php'; ?>

    <div class="w3-main">
        <div class="header text-center">
            <h1><i class="fas fa-tint me-3"></i>Blood Inventory Status</h1>
        </div>

        <div class="container">
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div id="warningToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="toast-header bg-warning text-dark">
                        <strong class="me-auto"><i class="fas fa-exclamation-triangle me-2"></i>Warning</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Some blood types are critically low. Please check the inventory status.
                    </div>
                </div>
            </div>

            <?php if (empty($lowStockBloodTypes)): ?>
                <div class="alert alert-success text-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>All blood types are currently at adequate levels.
                </div>
            <?php else: ?>
                <div class="alert alert-custom text-center mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>Some blood types are critically low. Immediate attention required.
                </div>
                <div class="row">
                    <?php foreach ($lowStockBloodTypes as $bloodGroup): ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h2 class="card-title"><?= $bloodGroup['bloodType'] ?></h2>
                                    <div class="chart-container">
                                        <canvas id="chart<?= str_replace('+', '', $bloodGroup['bloodType']) ?>"></canvas>
                                    </div>
                                    <p class="blood-status text-danger">
                                        Available: <?= $bloodGroup['quantity'] ?> units
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show warning toast if there are low stock blood types
            <?php if (!empty($lowStockBloodTypes)): ?>
                var warningToast = new bootstrap.Toast(document.getElementById('warningToast'));
                warningToast.show();
            <?php endif; ?>

            // Create pie charts
            <?php foreach ($lowStockBloodTypes as $bloodGroup): ?>
                createPieChart('chart<?= str_replace('+', '', $bloodGroup['bloodType']) ?>', <?= $bloodGroup['quantity'] ?>);
            <?php endforeach; ?>
        });

        function createPieChart(id, value) {
            const ctx = document.getElementById(id).getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [value, 10 - value],
                        backgroundColor: ['#dc3545', '#e9ecef'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed + ' units';
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>