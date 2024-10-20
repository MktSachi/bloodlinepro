<?php
session_start();
require '../Classes/Database.php';
require '../Classes/BloodRequest.php';

if (!isset($_SESSION['hospitalID'])) {
    die("Hospital ID not set.");
}

$hospitalID = $_SESSION['hospitalID'];

$db = new Database();
$conn = $db->getConnection();

$bloodRequestObj = new BloodRequest($conn);

$allRequests = [];
$isReportGenerated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $allRequests = $bloodRequestObj->getBloodRequestsByStatusAndDate($hospitalID, $startDate, $endDate, ['Pending', 'Approved', 'Rejected','']);

    $_SESSION['allRequests'] = $allRequests;
    $isReportGenerated = true;
}

$db->close();

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
        }
        .container {
            max-width: 800px;
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
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .alert {
            border-radius: 10px;
        }
        .table {
            background-color: white;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-chart-line me-2"></i>Generate Blood Request Reports</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif (isset($submissionSuccess)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Report generated successfully!
                    </div>
                <?php endif; ?>

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

        <?php if (isset($isReportGenerated) && $isReportGenerated && !empty($_SESSION['allRequests'])): ?>
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0"><i class="fas fa-hospital me-2"></i>Blood Requests</h2>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent form resubmission on page reload
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>