<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bloodlinepro/Classes/Database.php');
require_once '../Donation.php';


session_start();


$hospitalID = isset($_SESSION['hospitalID']) ? $_SESSION['hospitalID'] : null;

if (!$hospitalID) {
    die("Hospital ID not found in session. Please login again.");
}

$db = new Database();
$donationManager = new Donation($db);

$expiredDonations = [];
$selectedDate = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
$showResults = true; 

// Process form 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkExpired'])) {
    $deleteResult = $donationManager->deleteExpiredDonations($hospitalID, $selectedDate);
    if ($deleteResult['success']) {
        $deletedCount = $deleteResult['deletedCount'];
        $successMessage = "Successfully deleted $deletedCount blood stocks that expired over three months ago.";
    } else {
        $errorMessage = "Error: " . $deleteResult['error'];
    }
}


$result = $donationManager->getExpiredDonationsByHospitalAndDate($hospitalID, $selectedDate);
$expiredDonations = $result['success'] ? $result['data'] : [];

if (!$result['success']) {
    $errorMessage = $result['error'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Expired Blood Status</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .date-selection-card {
            max-width: 500px;
            margin: 50px auto;
        }
        .results-section {
            margin-top: 30px;
        }
        .status-badge {
            font-size: 0.85em;
            padding: 5px 10px;
        }
        .blood-type-badge {
            font-weight: bold;
            padding: 5px 15px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        
        <div class="card date-selection-card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Check Expired Blood Status</h4>
            </div>
            <div class="card-body">
                <form id="dateForm" method="POST" class="mb-0">
                    <div class="mb-3">
                        <label for="expiryDate" class="form-label">Select Date to Check Expired Blood:</label>
                        <input type="date" 
                               id="expiryDate" 
                               name="date" 
                               class="form-control form-control-lg" 
                               value="<?php echo $selectedDate; ?>" 
                               required
                               max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <button type="submit" name="checkExpired" class="btn btn-primary w-100">Check Expired Blood</button>
                </form>
            </div>
        </div>

        
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        
        <?php if ($showResults): ?>
        <div class="results-section">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Expired Blood Donations as of <?php echo date('d M Y', strtotime($selectedDate)); ?></h5>
                        <span class="badge bg-primary"><?php echo count($expiredDonations); ?> Results</span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($expiredDonations)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Donation ID</th>
                                        <th>Donor NIC</th>
                                        <th>Blood Type</th>
                                        <th>Amount</th>
                                        <th>Donation Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($expiredDonations as $donation): ?>
                                        <tr>
                                            <td><?php echo $donation['donation_id']; ?></td>
                                            <td><?php echo $donation['donorNIC']; ?></td>
                                            <td>
                                                <span class="badge bg-info text-dark blood-type-badge">
                                                    <?php echo $donation['bloodType']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $donation['donatedBloodCount']; ?> units</td>
                                            <td><?php echo date('Y-m-d', strtotime($donation['donationDate'])); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($donation['bloodExpiryDate'])); ?></td>
                                            <td>
                                                <?php 
                                                $daysExpired = floor((strtotime($selectedDate) - strtotime($donation['bloodExpiryDate'])) / (60 * 60 * 24));
                                                $statusClass = $daysExpired > 7 ? 'bg-danger' : 'bg-warning';
                                                ?>
                                                <span class="badge status-badge <?php echo $statusClass; ?>">
                                                    Expired
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top: 20px;"> 
    <a href="download_expired_report.php?download=pdf&date=<?php echo $selectedDate; ?>" class="btn btn-primary">Download PDF Report</a>
</div>

                    <?php else: ?>
                        
                        <div class="text-center py-5">
                            <h5 class="text-muted">No expired blood donations found for the selected date.</h5>
                            <p class="mb-0">Try selecting a different date or check back later.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set default max date to today
            var today = new Date().toISOString().split('T')[0];
            $('#expiryDate').attr('max', today);

            // Automatically submit form when date changes
            $('#expiryDate').change(function() {
                $('#dateForm').submit();
            });
        });
    </script>
</body>
</html>