<?php
session_start();
require '../../DonorRegistration/Database.php';


$db = new Database();
$conn = $db->getConnection();


$donations = [];


$username = $_SESSION['username'] ?? '';
if (!empty($username)) {
    $queryHpHospital = "SELECT h.hospitalID FROM healthcare_professionals hp 
                        JOIN users u ON hp.userid = u.userid
                        JOIN hospitals h ON hp.hospitalID = h.hospitalID
                        WHERE u.username = ?";
    $stmtHpHospital = $conn->prepare($queryHpHospital);
    $stmtHpHospital->bind_param('s', $username);
    $stmtHpHospital->execute();
    $resultHpHospital = $stmtHpHospital->get_result();

    if ($resultHpHospital->num_rows > 0) {
        $hpHospital = $resultHpHospital->fetch_assoc();
        $hospitalID = $hpHospital['hospitalID'];
    }

    $stmtHpHospital->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startDate'], $_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    
    $queryDonations = "SELECT d.id, d.donorNIC, d.donatedBloodCount, d.donationDate, d.bloodExpiryDate, 
                               dn.first_name, dn.last_name
                        FROM donations d
                        JOIN donors dn ON d.donorNIC = dn.donorNIC
                        WHERE d.hospitalID = ? AND d.donationDate BETWEEN ? AND ?";
    $stmtDonations = $conn->prepare($queryDonations);
    $stmtDonations->bind_param('iss', $hospitalID, $startDate, $endDate);
    $stmtDonations->execute();
    $resultDonations = $stmtDonations->get_result();

    
    while ($row = $resultDonations->fetch_assoc()) {
        $donations[] = $row;
    }

    $stmtDonations->close();

    
    $_SESSION['donations'] = $donations;
}

$db->close();


function generateHTMLDownload($data) {
    $filename = 'hospital_donations_report_' . date('Y-m-d') . '.html';
    $htmlContent = '<html><head><title>Hospital Donations Report</title>';
    $htmlContent .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    $htmlContent .= '</head><body><div class="container"><h1>Hospital Donations Report</h1>';
    $htmlContent .= '<table class="table table-bordered"><thead><tr>';
    $htmlContent .= '<th>Donor Name</th><th>Donor NIC</th><th>Donated Blood Count</th><th>Donation Date</th><th>Blood Expiry Date</th>';
    $htmlContent .= '</tr></thead><tbody>';

    foreach ($data as $row) {
        $htmlContent .= '<tr>';
        $htmlContent .= '<td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['donorNIC']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['donatedBloodCount']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['donationDate']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['bloodExpiryDate']) . '</td>';
        $htmlContent .= '</tr>';
    }

    $htmlContent .= '</tbody></table></div></body></html>';

    
    file_put_contents($filename, $htmlContent);


    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);
    unlink($filename);
    exit;
}
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