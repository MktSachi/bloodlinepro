<?php
require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Create an instance of BloodRequest
$bloodRequest = new BloodRequest($conn);

$requests = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestDate = $_POST['requestDate'];
    $requests = $bloodRequest->getBloodRequestsByDate($requestDate);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Requests - BloodLinePro</title>
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
                    <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Blood Requests Report</h4>
                </div>
                <div class="card-body">
                    <form id="reportForm" method="POST">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="requestDate" class="form-label">Request Date</label>
                                <input type="date" class="form-control" id="requestDate" name="requestDate" required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Generate Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($requests)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-hospital me-2"></i>Blood Requests for <?= htmlspecialchars($requestDate) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Donating Hospital</th>
                                    <th>Requesting Hospital</th>
                                    <th>Blood Type</th>
                                    <th>Requested Quantity</th>
                                    <th>Request Time</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($request['requestID']) ?></td>
                                        <td><?= htmlspecialchars($request['donatingHospital']) ?></td>
                                        <td><?= htmlspecialchars($request['requestingHospital']) ?></td>
                                        <td><?= htmlspecialchars($request['bloodType']) ?></td>
                                        <td><?= htmlspecialchars($request['requestedQuantity']) ?></td>
                                        <td><?= htmlspecialchars(date('H:i:s', strtotime($request['requestDate']))) ?></td>
                                        <td><?= htmlspecialchars($request['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="Download.php?download=html&date=<?= urlencode($requestDate) ?>" class="btn btn-success mt-3">
                            <i class="fas fa-download me-2"></i>Download Report
                        </a>
                    </div>
                </div>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <div class="alert alert-warning" role="alert">
                    No blood requests found for the selected date: <?= htmlspecialchars($requestDate) ?>
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