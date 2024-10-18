<?php
session_start();
require '../Classes/Database.php';
require '../Classes/BloodRequest.php'; // Ensure BloodRequest class is included

if (!isset($_SESSION['hospitalID'])) {
    die("Hospital ID not set.");
}

$hospitalID = $_SESSION['hospitalID'];

$db = new Database();
$conn = $db->getConnection();

// Create a BloodRequest object
$bloodRequestObj = new BloodRequest($conn);

// Initialize variables to store requests
$allRequests = [];

// Initialize a flag to check if the report is generated
$isReportGenerated = false;

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the start and end dates from the form
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Get both Pending and Approved blood requests within the specified time period
    $allRequests = $bloodRequestObj->getBloodRequestsByStatusAndDate($hospitalID, $startDate, $endDate, ['Pending', 'Approved']);

    // Store the requests in the session to be displayed and for PDF download
    $_SESSION['allRequests'] = $allRequests;

    // Set the flag to true
    $isReportGenerated = true;
}

// Close the database connection
$db->close();

// Clear the session variable if not on the report generation
if (!$isReportGenerated) {
    unset($_SESSION['allRequests']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Blood Requests - BloodLinePro</title>
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
                    <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Generate Blood Request Reports</h4>
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
                                <button type="submit" name="generate" class="btn btn-primary w-100">
                                    <i class="fas fa-file-alt me-2"></i>Generate Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($isReportGenerated && !empty($_SESSION['allRequests'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-hospital me-2"></i>Blood Requests</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Hospital Name</th>
                                    <th>Blood Type</th>
                                    <th>Blood Quantity</th>
                                    <th>Requested Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($_SESSION['allRequests'] as $request): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($request['hospitalName']) ?></td>
                                        <td><?= htmlspecialchars($request['bloodType']) ?></td>
                                        <td><?= htmlspecialchars($request['bloodQuantity']) ?></td>
                                        <td><?= htmlspecialchars($request['requestDate']) ?></td>
                                        <td><?= htmlspecialchars($request['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="Download.php?download=pdf" class="btn btn-success mt-3">
                            <i class="fas fa-download me-2"></i>Download PDF Report
                        </a>
                    </div>
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
