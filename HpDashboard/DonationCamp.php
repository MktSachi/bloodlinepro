<?php
require_once '../Classes/Database.php';
require_once 'HealthCareProfessional.php';
require_once 'Donation.php';

session_start();

if (!isset($_SESSION['hospitalID'])) {
    die("Error: Hospital ID is not set in the session.");
}

$hospitalID = $_SESSION['hospitalID'];
$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);
$healthcareProfessional = new HealthCareProfessional($db);
$donation = new Donation($db);

$donorDetails = null;
$donorNotFound = false;
$submissionSuccess = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorNIC = $_POST['donorNIC'] ?? '';
    $donatedBloodCount = $_POST['donatedBloodCount'] ?? '';

    if (!empty($donorNIC)) {
        $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
        if (!$donorDetails) {
            $donorNotFound = true;
        } elseif (!empty($donatedBloodCount)) {
            $bloodType = $donorDetails['bloodType'];
            $result = $donation->processDonation($donorNIC, $hospitalID, $donatedBloodCount, $bloodType);
            
            if ($result === true) {
                $submissionSuccess = true;
                $donorDetails = $donor->getDonorDetailsByNIC($donorNIC); // Refresh donor details
            } else {
                $error = $result; // Error message from processDonation
            }
        }
    } elseif (!empty($donatedBloodCount)) {
        $error = 'Please enter the NIC Number.';
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Camp Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            padding-top: 20px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }
        .highlight label {
            color: #007bff;
            font-weight: bold;
        }
        .highlight {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .btn-dark-red {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        .btn-dark-red:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: #fff;
        }
        .card-header-dark-red {
            background-color: #8B0000;
            color: white;
        }
        .text-dark-red {
            color: #8B0000;
        }
        
    </style>
</head>
<body>
    <?php include 'HpSidebar.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <h1 class="mb-4"><i class="fas fa-heartbeat"></i> Donation Camp Management</h1>
            
            <div class="card">
                <div class="card-header card-header-dark-red">
                    <i class="fas fa-search"></i> Donor Search
                </div>
                <div class="card-body">
                    <form id="donor-form" method="post" class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="donorNIC" name="donorNIC" placeholder="Enter NIC Number" required>
                            <button type="submit" class="btn">
                                <span class="text-dark-red">
                                    <i class="fas fa-search"></i> Search
                                </span>
                            </button>
                        </div>
                    </form>

                    <?php if ($donorNotFound): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Donor not found.
                        </div>
                    <?php endif; ?>

                    <?php if ($submissionSuccess): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Donation details submitted successfully.
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($donorDetails): ?>
                <div class="card">
                    <div class="card-header card-header-dark-red">
                        <i class="fas fa-user"></i> Donor Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="highlight">
                                    <label><i class="fas fa-user"></i> Full Name:</label>
                                    <p><?= htmlspecialchars($donorDetails['first_name'] . ' ' . $donorDetails['last_name']) ?></p>
                                </div>
                                <div class="highlight">
                                    <label><i class="fas fa-id-card"></i> NIC Number:</label>
                                    <p><?= htmlspecialchars($donorDetails['donorNIC']) ?></p>
                                </div>
                                <div class="highlight">
                                    <label><i class="fas fa-tint"></i> Blood Type:</label>
                                    <p><?= htmlspecialchars($donorDetails['bloodType']) ?></p>
                                </div>
                                <div class="highlight">
                                    <label><i class="fas fa-envelope"></i> Email:</label>
                                    <p><?= htmlspecialchars($donorDetails['email']) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="highlight">
                                    <label><i class="fas fa-map-marker-alt"></i> Address:</label>
                                    <p><?= htmlspecialchars($donorDetails['address']) ?></p>
                                </div>
                                <div class="highlight">
                                    <label><i class="fas fa-phone"></i> Phone Number:</label>
                                    <p><?= htmlspecialchars($donorDetails['phoneNumber']) ?></p>
                                </div>
                                <div class="highlight">
                                    <label><i class="fas fa-donate"></i> Donation Count:</label>
                                    <p><?= htmlspecialchars($donorDetails['donation_count']) ?></p>
                                </div>
                            </div>
                        </div>

                        <form id="donation-form" method="post" onsubmit="return validateForm()">
                            <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorDetails['donorNIC']) ?>">
                            <div class="form-group mb-3">
                                <label for="donatedBloodCount"><i class="fas fa-syringe"></i> Donated Blood Count:</label>
                                <input type="number" class="form-control" id="donatedBloodCount" name="donatedBloodCount" min="1" step="1" required>
                            </div>
                            <button type="submit" class="btn btn-dark-red">
                                <i class="fas fa-check-circle"></i> Submit Donation Details
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm() {
            var donatedBloodCount = document.getElementById('donatedBloodCount').value;
            if (donatedBloodCount <= 0 || !Number.isInteger(Number(donatedBloodCount))) {
                alert('Please enter a valid positive whole number for the donated blood count.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>