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
        :root {
            --primary-color: #dc3545;
            --secondary-color: #007bff;
        }
        body {
            background-color: white;
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: var(--primary-color);
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            padding-top: 20px;
            transition: all 0.3s;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            background-color: var(--primary-color);
            color: #fff;
            font-weight: bold;
            padding: 15px;
            border-bottom: none;
        }
        .highlight label {
            color: var(--secondary-color);
            font-weight: bold;
        }
        .highlight {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .donor-info {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .donor-info h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        .donor-avatar {
            width: 100px;
            height: 100px;
            background-color: var(--secondary-color);
            color: #fff;
            font-size: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            margin: 0 auto 20px;
        }
        .donation-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .animated {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <?php include 'HpSidebar.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <h1 class="mb-4 text-center"><i class="fas fa-heartbeat"></i> Donation Camp Management</h1>
            
            <div class="card animated">
                <div class="card-header">
                    <i class="fas fa-search"></i> Donor Search
                </div>
                <div class="card-body">
                    <form id="donor-form" method="post" class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="donorNIC" name="donorNIC" placeholder="Enter NIC Number" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>

                    <?php if ($donorNotFound): ?>
                        <div class="alert alert-danger animated">
                            <i class="fas fa-exclamation-circle"></i> Donor not found.
                        </div>
                    <?php endif; ?>

                    <?php if ($submissionSuccess): ?>
                        <div class="alert alert-success animated">
                            <i class="fas fa-check-circle"></i> Donation details submitted successfully.
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger animated">
                            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($donorDetails): ?>
                <div class="row animated">
                    <div class="col-md-6">
                        <div class="donor-info">
                            <div class="donor-avatar">
                                <?= strtoupper(substr($donorDetails['first_name'], 0, 1) . substr($donorDetails['last_name'], 0, 1)) ?>
                            </div>
                            <h3 class="text-center"><?= htmlspecialchars($donorDetails['first_name'] . ' ' . $donorDetails['last_name']) ?></h3>
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
                                        <div class="col-md-6">
                        <div class="donation-form">
                            <h3 class="text-center mb-4">Record New Donation</h3>
                            <form id="donation-form" method="post" onsubmit="return validateForm()">
                                <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorDetails['donorNIC']) ?>">
                                <div class="form-group mb-3">
                                    <label for="donatedBloodCount"><i class="fas fa-syringe"></i> Donated Blood Count:</label>
                                    <select class="form-control" id="donatedBloodCount" name="donatedBloodCount" required>
                                        <option value="400">400ml</option>
                                        <option value="500">500ml</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check-circle"></i> Submit Donation Details
                                </button>
                            </form>
                        </div>
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