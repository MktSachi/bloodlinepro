<?php
require '../Classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch blood type distribution
$bloodTypeData = [];
$queryBloodType = "SELECT bloodType, SUM(quantity) AS total FROM hospital_blood_inventory GROUP BY bloodType";
$resultBloodType = $conn->query($queryBloodType);
if ($resultBloodType->num_rows > 0) {
    while ($row = $resultBloodType->fetch_assoc()) {
        $bloodTypeData[$row['bloodType']] = $row['total'];
    }
}
$resultBloodType->free();

// Calculate low stock blood types (quantities < 100)
$lowStockBloodTypes = [];
foreach ($bloodTypeData as $bloodType => $quantity) {
    if ($quantity < 100) {
        $lowStockBloodTypes[] = [
            'bloodType' => $bloodType,
            'quantity' => $quantity
        ];
    }
}

$lowStockCount = count($lowStockBloodTypes);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory Warning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        #content-loader {
            position: absolute;
            top: 0;
            left: 230px;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
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
<?php include './sidebar.php'; ?>
    <div class="w3-main">

        <div class="header text-center">
            <h1><i class="fas fa-tint me-3"></i>Blood Inventory Warning</h1>
        </div>
        <div id="content-loader">
            <div class="spinner"></div>
        </div>
        <?php if ($lowStockCount > 0): ?>
            <div class="alert alert-danger text-center mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>Warning: <?= $lowStockCount ?> blood type<?= $lowStockCount > 1 ? 's are' : ' is' ?> critically low (below 100 units).
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
                                <p class="blood-status text-danger mt-3">
                                    Available: <?= $bloodGroup['quantity'] ?> units
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>All blood types are currently at adequate levels (100+ units).
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                        data: [value, 100 - value],
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
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('content-loader').style.display = 'none';
                document.querySelector('.main-content').style.display = 'block';
            }, 1500); // 1500 milliseconds = 1.5 seconds
        });
    </script>
</body>
</html>