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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-fluid {
            margin-left: 180px;
            margin-top: 43px;
            padding-bottom: 60px;
        }
        .card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 12px 20px rgba(0,0,0,0.2);
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
        .progress {
            height: 20px;
            border-radius: 10px;
            background-color: #e9ecef;
            overflow: hidden;
        }
        .progress-bar {
            background-color: #dc3545;
            transition: width 1s ease-in-out;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
            margin-left: 180px;
        }
        .warning-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .blood-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <?php include './HpSidebar.php'; ?>
    
    <div class="container-fluid">
        <div class="container mt-5 p-4 bg-white shadow rounded">
            <h1 class="mb-4 text-center text-danger">
                <i class="fas fa-tint mr-2"></i>Blood Inventory Status
            </h1>
            
            <div id="warningToast" class="toast warning-toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                <div class="toast-header bg-warning text-dark">
                    <strong class="mr-auto"><i class="fas fa-exclamation-triangle mr-2"></i>Warning</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Some blood types are critically low. Please check the inventory status.
                </div>
            </div>

            <?php if (empty($lowStockBloodTypes)): ?>
                <div class="alert alert-success text-center" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>All blood types are currently at adequate levels.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($lowStockBloodTypes as $bloodGroup): ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-tint blood-icon text-danger"></i>
                                    <h2 class="card-title mb-3"><?= $bloodGroup['bloodType'] ?></h2>
                                    <p class="card-text text-danger font-weight-bold mb-3">
                                        Available: <?= $bloodGroup['quantity'] ?> units
                                    </p>
                                    <div class="progress mt-3">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: <?= ($bloodGroup['quantity'] / 10) * 100 ?>%;" 
                                             aria-valuenow="<?= $bloodGroup['quantity'] ?>" 
                                             aria-valuemin="0" aria-valuemax="10">
                                            <?= $bloodGroup['quantity'] ?>/10
                                        </div>
                                    </div>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show warning toast if there are low stock blood types
            <?php if (!empty($lowStockBloodTypes)): ?>
                $('#warningToast').toast('show');
            <?php endif; ?>

            // Animate progress bars
            $('.progress-bar').each(function() {
                var $bar = $(this);
                var progress = $bar.attr('aria-valuenow');
                $bar.css('width', 0).animate({
                    width: progress + '%'
                }, 1000);
            });
        });
    </script>
</body>
</html>