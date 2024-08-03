<?php
require '../DonorRegistration/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Total blood units
$totalunits = 0;
$queryTotalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory";
$resultTotalBlood = $conn->query($queryTotalBlood);
if ($resultTotalBlood->num_rows > 0) {
    $row = $resultTotalBlood->fetch_assoc();
    $totalunits = $row['total'];
}
$resultTotalBlood->free();

// Donor statistics
$queryDonorStats = "SELECT 
    COUNT(*) as total_donors,
    AVG(donation_count) as avg_donations,
    SUM(CASE WHEN bloodType IN ('A+', 'A-') THEN 1 ELSE 0 END) as type_a_count,
    SUM(CASE WHEN bloodType IN ('B+', 'B-') THEN 1 ELSE 0 END) as type_b_count,
    SUM(CASE WHEN bloodType IN ('AB+', 'AB-') THEN 1 ELSE 0 END) as type_ab_count,
    SUM(CASE WHEN bloodType IN ('O+', 'O-') THEN 1 ELSE 0 END) as type_o_count
FROM donors";
$resultDonorStats = $conn->query($queryDonorStats);
$donorStats = $resultDonorStats->fetch_assoc();

// Last donation date
$queryLastDonation = "SELECT MAX(donationDate) as last_donation FROM donations";
$resultLastDonation = $conn->query($queryLastDonation);
$lastDonation = $resultLastDonation->fetch_assoc();

// Hospital blood inventory
$hospitals = [];
$queryHospitals = "SELECT hospitalName, SUM(quantity) AS totalBlood FROM hospital_blood_inventory hbi JOIN hospitals h ON hbi.hospitalID = h.hospitalID GROUP BY hospitalName";
$resultHospitals = $conn->query($queryHospitals);
if ($resultHospitals->num_rows > 0) {
    while ($row = $resultHospitals->fetch_assoc()) {
        $hospitals[$row['hospitalName']] = $row['totalBlood'];
    }
}
$resultHospitals->free();

// Blood type distribution
$bloodTypeData = [];
$queryBloodType = "SELECT bloodType, SUM(quantity) AS total FROM hospital_blood_inventory GROUP BY bloodType";
$resultBloodType = $conn->query($queryBloodType);
if ($resultBloodType->num_rows > 0) {
    while ($row = $resultBloodType->fetch_assoc()) {
        $bloodTypeData[$row['bloodType']] = $row['total'];
    }
}
$resultBloodType->free();

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodLinePro Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #8B0000;
            --secondary-color: #D32F2F;
            --text-color: #333;
            --bg-color: #f4f4f4;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .main-content {
            padding: 20px;
        }
        .top-header {
            background-color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="top-header d-flex justify-content-between align-items-center mb-4">
                    <h1>Dashboard</h1>
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-3">
                            <a href="#" class="btn btn-link text-dark dropdown-toggle" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="notificationsDropdown">
                                <li><a class="dropdown-item" href="#">New donation request</a></li>
                                <li><a class="dropdown-item" href="#">Appointment reminder</a></li>
                                <li><a class="dropdown-item" href="#">Low blood stock alert</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="btn btn-link text-dark dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>Admin User
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Donors</h5>
                                <h2 class="card-text"><?php echo number_format($donorStats['total_donors']); ?></h2>
                                <p class="card-text text-success"><i class="fas fa-arrow-up me-2"></i>5% increase</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Blood Units Available</h5>
                                <h2 class="card-text"><?php echo number_format($totalunits); ?></h2>
                                <p class="card-text text-danger"><i class="fas fa-arrow-down me-2"></i>2% decrease</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Avg Donations/Donor</h5>
                                <h2 class="card-text"><?php echo number_format($donorStats['avg_donations'], 1); ?></h2>
                                <p class="card-text text-success"><i class="fas fa-arrow-up me-2"></i>3% increase</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Last Donation Date</h5>
                                <h2 class="card-text"><?php echo date('M d, Y', strtotime($lastDonation['last_donation'])); ?></h2>
                                <p class="card-text text-success"><i class="fas fa-check-circle me-2"></i>Recent</p>
                            </div>
                        </div>
                    </div>
                </div>

                
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>