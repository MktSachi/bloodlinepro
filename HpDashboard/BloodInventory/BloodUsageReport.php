<?php
session_start();
require '../../Classes/Database.php';
require 'Inventory.php';

$db = new Database();
$conn = $db->getConnection();

$inventory = new Inventory($conn);

$username = $_SESSION['username'] ?? '';
$hospitalID = 1; // Example hospital ID, replace with the actual ID for the logged-in user

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startDate'], $_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    if (!empty($username) && !empty($hospitalID)) {
        $bloodUsage = $inventory->getBloodUsageReport($hospitalID, $startDate, $endDate);
        $_SESSION['bloodUsage'] = $bloodUsage;
    }
}

// Optional: Display a success message or redirect if needed
// header('Location: success_page.php');
// exit;

$db->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Usage - BloodLinePro</title>
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
                    <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Generate Blood Usage Reports</h4>
                </div>
                <div class="card-body">
                    <form id="reportForm" method="POST">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Generate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($bloodUsage)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-hospital me-2"></i>Blood Usage</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Sender Hospital</th>
                                    <th>Receiver Hospital</th>
                                    <th>Blood Type</th>
                                    <th>Blood Quantity</th>
                                    <th>Transfer Date</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($bloodUsage as $usage): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($usage['senderHospitalName']) ?></td>
                                        <td><?= htmlspecialchars($usage['receiverHospitalName']) ?></td>
                                        <td><?= htmlspecialchars($usage['bloodType']) ?></td>
                                        <td><?= htmlspecialchars($usage['bloodQuantity']) ?></td>
                                        <td><?= htmlspecialchars($usage['transferDate']) ?></td>
                                        <td><?= htmlspecialchars($usage['description']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="Download.php?download=html" class="btn btn-success mt-3">
                            <i class="fas fa-download me-2"></i>Download Report
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    No data available for the selected date range.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Prevent form resubmission on page reload
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</body>
</html>
