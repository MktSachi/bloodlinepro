<?php
require '../DonorRegistration/Database.php';
require '../DonorRegistration/Donor.php';

session_start();

if (isset($_SESSION['hospitalID'])) {
    $hospitalID = $_SESSION['hospitalID'];
} else {
    echo "Hospital ID is not set in the session.";
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);

$donorDetails = null;
$donorNotFound = false;
$submissionSuccess = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['donorNIC'])) {
        $donorNIC = $_POST['donorNIC'];
        $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
        if (!$donorDetails) {
            $donorNotFound = true;
        }
    }

    if (isset($_POST['donorNIC'], $_POST['donatedBloodCount'])) {
        $donorNIC = $_POST['donorNIC'];
        $donatedBloodCount = $_POST['donatedBloodCount'];

        $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);

        if (!$donorDetails) {
            $donorNotFound = true;
        } else {
            $bloodType = $donorDetails['bloodType'];

            $donationDate = date('Y-m-d');
            $bloodExpiryDate = date('Y-m-d', strtotime($donationDate . ' + 40 days'));

            // Insert donation details into donations table
            $insertDonationQuery = "INSERT INTO donations (donorNIC, hospitalID, donatedBloodCount, donationDate, bloodExpiryDate) VALUES (?, ?, ?, ?, ?)";
            $insertDonationStmt = $conn->prepare($insertDonationQuery);
            $insertDonationStmt->bind_param('siiss', $donorNIC, $hospitalID, $donatedBloodCount, $donationDate, $bloodExpiryDate);

            if ($insertDonationStmt->execute()) {
                $insertDonationStmt->close();

                // Update donation count in donors table
                $updateDonorQuery = "UPDATE donors SET donation_count = donation_count + 1 WHERE donorNIC = ?";
                $updateDonorStmt = $conn->prepare($updateDonorQuery);
                $updateDonorStmt->bind_param('s', $donorNIC);
                $updateDonorStmt->execute();
                $updateDonorStmt->close();

                // Update hospital blood inventory
                $selectInventoryQuery = "SELECT * FROM hospital_blood_inventory WHERE hospitalID = ? AND bloodType = ?";
                $selectInventoryStmt = $conn->prepare($selectInventoryQuery);
                $selectInventoryStmt->bind_param('is', $hospitalID, $bloodType);
                $selectInventoryStmt->execute();
                $result = $selectInventoryStmt->get_result();

                if ($result->num_rows > 0) {
                    // Update existing entry
                    $updateInventoryQuery = "UPDATE hospital_blood_inventory SET quantity = quantity + ? WHERE hospitalID = ? AND bloodType = ?";
                    $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
                    $updateInventoryStmt->bind_param('iis', $donatedBloodCount, $hospitalID, $bloodType);
                } else {
                    // Insert new entry
                    $updateInventoryQuery = "INSERT INTO hospital_blood_inventory (hospitalID, bloodType, quantity) VALUES (?, ?, ?)";
                    $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
                    $updateInventoryStmt->bind_param('isi', $hospitalID, $bloodType, $donatedBloodCount);
                }

                $updateInventoryStmt->execute();
                $updateInventoryStmt->close();

                $submissionSuccess = true;

                // Retrieve updated donor details
                $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
            } else {
                $error = 'Error submitting the donation details: ' . $conn->error;
            }
        }
    } else {
        $error = 'Please enter both NIC Number and Donated Blood Count.';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            padding-top: 20px;
        }

        .main-content {
            padding: 5px; /* Reduced from 20px to 10px */
            margin-left: 150px; /* Reduced from 250px to 200px */
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn {
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 20px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-success {
            background: linear-gradient(135deg, #5a090a 0%, #060606 100%);
            border-color: var(--success-color);
        }

        .alert {
            border-radius: 5px;
        }

        .highlight {
            background-color: rgba(0, 123, 255, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .highlight label {
            font-weight: bold;
            color: var(--secondary-color);
        }

        .highlight p {
            margin-bottom: 0;
            color: var(--dark-color);
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .card {
                margin-bottom: 15px;
            }

            h1 {
                font-size: 24px;
                margin-bottom: 15px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
</head>

<body>
    <?php include 'HpSidebar.php'; ?>
    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <h1 class="mb-4 text-center">Donation Camp Management</h1>

                    <div class="card mb-3">
                        <div class="card-header">
                            Donor Search
                        </div>
                        <div class="card-body">
                            <form id="donor-form" method="post" class="mb-3">
                                <div class="form-group">
                                    <input type="text" class="form-control mb-2" id="donorNIC" name="donorNIC" placeholder="Enter NIC Number" required>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Search
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
                            <div class="card-header">
                                Donor Details
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="highlight">
                                            <label>Full Name:</label>
                                            <p><?= htmlspecialchars($donorDetails['first_name'] . ' ' . $donorDetails['last_name']) ?></p>
                                        </div>
                                        <div class="highlight">
                                            <label>NIC Number:</label>
                                            <p><?= htmlspecialchars($donorDetails['donorNIC']) ?></p>
                                        </div>
                                        <div class="highlight">
                                            <label>Blood Type:</label>
                                            <p><?= htmlspecialchars($donorDetails['bloodType']) ?></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="highlight">
                                            <label>Address:</label>
                                            <p><?= htmlspecialchars($donorDetails['address']) ?></p>
                                        </div>
                                        <div class="highlight">
                                            <label>Email:</label>
                                            <p><?= htmlspecialchars($donorDetails['email']) ?></p>
                                        </div>
                                        <div class="highlight">
                                            <label>Phone Number:</label>
                                            <p><?= htmlspecialchars($donorDetails['phoneNumber']) ?></p>
                                        </div>
                                    </div>
                                </div>

                                <form id="donation-form" method="post">
                                    <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorDetails['donorNIC']) ?>">
                                    <div class="form-group">
                                        <label for="donatedBloodCount">Donated Blood Count:</label>
                                        <input type="number" class="form-control" id="donatedBloodCount" name="donatedBloodCount" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 mt-3">
                                        <i class="fas fa-check-circle"></i> Submit Donation Details
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
