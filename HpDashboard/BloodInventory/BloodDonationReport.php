<?php
session_start();
require '../../DonorRegistration/Database.php';
require 'Inventory.php';

$db = new Database();
$conn = $db->getConnection();

$inventory = new Inventory($conn);

$username = $_SESSION['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startDate'], $_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    if (!empty($username)) {
        $donations = $inventory->getDonationReport($username, $startDate, $endDate);
        $_SESSION['donations'] = $donations;
    }
}

$db->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Donations - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            margin: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="row">
        <div class="col-md-12">
        
            <div class="card-body" style="max-width: 400px; margin: 0 auto;">
            <h5 class="card-title">Generate Reports</h5>
            <form id="reportForm" method="POST">
            <div class="mb-3">
            <label for="startDate" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="startDate" name="startDate" required>
        
        <div class="mb-3">
            <label for="endDate" class="form-label">End Date</label>
            <input type="date" class="form-control" id="endDate" name="endDate" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
</div>

            </div>
            <?php if (!empty($donations)): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Hospital Donations</h5>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Donor Name</th>
                                <th>Donor NIC</th>
                                <th>Donated Blood Count</th>
                                <th>Donation Date</th>
                                <th>Blood Expiry Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($donations as $donation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($donation['first_name'] . ' ' . $donation['last_name']) ?></td>
                                    <td><?= htmlspecialchars($donation['donorNIC']) ?></td>
                                    <td><?= htmlspecialchars($donation['donatedBloodCount']) ?></td>
                                    <td><?= htmlspecialchars($donation['donationDate']) ?></td>
                                    <td><?= htmlspecialchars($donation['bloodExpiryDate']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="Download.php?download=html" class="btn btn-success">Download Report</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Optional: Prevent form resubmission on page reload
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

</body>
</html>