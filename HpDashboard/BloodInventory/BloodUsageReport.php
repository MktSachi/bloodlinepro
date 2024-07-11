<?php
session_start();
require '../../DonorRegistration/Database.php';
$db = new Database();
$conn = $db->getConnection();




$usages = [];


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
    
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    
    $queryUsage = "SELECT bu.usageID, bu.bloodType, bu.bloodQuantity, bu.transferDate, 
                          h.hospitalName AS senderHospitalName, h.address AS senderHospitalAddress, h.phoneNumber AS senderHospitalPhone, h.email AS senderHospitalEmail, bu.description
                   FROM blood_usage bu
                   JOIN hospitals h ON bu.senderHospitalID = h.hospitalID
                   WHERE bu.senderHospitalID = ? AND bu.transferDate BETWEEN ? AND ?";
$stmtDonations = $conn->prepare($queryUsage);
$stmtDonations->bind_param('iss', $hospitalID, $startDate, $endDate);
$stmtDonations->execute();
$resultDonations = $stmtDonations->get_result();


while ($row = $resultDonations->fetch_assoc()) {
    $donations[] = $row;
}

$stmtDonations->close();
$_SESSION['usages'] = $usages;


}



$db->close();
function generateHTMLDownload($data) {
    $filename = 'blood_usage_report_' . date('Y-m-d') . '.html';
    $htmlContent = '<html><head><title>Blood Usage Report</title>';
    $htmlContent .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    $htmlContent .= '</head><body><div class="container"><h1>Blood Usage Report</h1>';
    $htmlContent .= '<table class="table table-bordered"><thead><tr>';
    $htmlContent .= '<th>Usage ID</th><th>Blood Type</th><th>Blood Quantity</th><th>Transfer Date</th><th>Hospital Name</th><th>Address</th><th>Phone Number</th><th>Email</th><th>Description</th>';
    $htmlContent .= '</tr></thead><tbody>';

    foreach ($data as $row) {
        $htmlContent .= '<tr>';
        $htmlContent .= '<td>' . htmlspecialchars($row['usageID']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['bloodType']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['bloodQuantity']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['transferDate']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['senderHospitalName']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['senderHospitalAddress']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['senderHospitalPhone']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['senderHospitalEmail']) . '</td>';
        $htmlContent .= '<td>' . htmlspecialchars($row['description']) . '</td>';
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
    <title>Blood Usage Report - BloodLinePro</title>
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
            <div class="card">
                <div class="card-body" style="max-width: 400px; margin: 0 auto;">
                    <h5 class="card-title">Generate Blood Usage Report</h5>
                    <form id="reportForm" method="POST">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </form>
                </div>
            </div>

            <?php if (!empty($usages)): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Blood Usage Report</h5>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Usage ID</th>
                                <th>Blood Type</th>
                                <th>Blood Quantity</th>
                                <th>Transfer Date</th>
                                <th>Sender Hospital</th>
                                <th>Receiver Hospital</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($usages as $usage): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usage['usageID']) ?></td>
                                    <td><?= htmlspecialchars($usage['bloodType']) ?></td>
                                    <td><?= htmlspecialchars($usage['bloodQuantity']) ?></td>
                                    <td><?= htmlspecialchars($usage['transferDate']) ?></td>
                                    <td><?= htmlspecialchars($usage['senderHospitalName']) ?></td>
                                    <td><?= htmlspecialchars($usage['receiverHospitalName']) ?></td>
                                    <td><?= htmlspecialchars($usage['description']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="BloodUsageReport.php?download=html" class="btn btn-success">Download Report</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</body>
</html>

