<?php
require '../DonorRegistration/Database.php';
require '../DonorRegistration/Donor.php';

session_start();

// Check if hospital ID is set in the session
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
    if ($_POST['searchType'] === 'NIC') {
        if (isset($_POST['donorNIC'])) {
            $donorNIC = $_POST['donorNIC'];
            $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
            if (!$donorDetails) {
                $donorNotFound = true;
            }
        }
    } elseif ($_POST['searchType'] === 'fullName') {
        if (isset($_POST['donorFullName'])) {
            $donorFullName = $_POST['donorFullName'];
            // Split full name into first name and last name
            $names = explode(' ', $donorFullName, 2);
            if (count($names) == 2) {
                list($firstName, $lastName) = $names;
                $donorDetails = $donor->getDonorDetailsByFullName($firstName, $lastName);
                if (!$donorDetails) {
                    $donorNotFound = true;
                }
            } else {
                $donorNotFound = true;
            }
        } else {
            $donorNotFound = true;
        }
    }

    if ($donorDetails) {
        if (isset($_POST['donatedBloodCount']) && !empty($_POST['donatedBloodCount'])) {
            $donatedBloodCount = $_POST['donatedBloodCount'];

            $donationDate = date('Y-m-d');
            $bloodExpiryDate = date('Y-m-d', strtotime($donationDate . ' + 40 days'));

            // Insert donation details into donations table
            $insertDonationQuery = "INSERT INTO donations (donorNIC, hospitalID, donatedBloodCount, donationDate, bloodExpiryDate) VALUES (?, ?, ?, ?, ?)";
            $insertDonationStmt = $conn->prepare($insertDonationQuery);
            $insertDonationStmt->bind_param('siiss', $donorDetails['donorNIC'], $hospitalID, $donatedBloodCount, $donationDate, $bloodExpiryDate);

            if ($insertDonationStmt->execute()) {
                $insertDonationStmt->close();

                // Update donation count in donors table
                $updateDonorQuery = "UPDATE donors SET donation_count = donation_count + 1 WHERE donorNIC = ?";
                $updateDonorStmt = $conn->prepare($updateDonorQuery);
                $updateDonorStmt->bind_param('s', $donorDetails['donorNIC']);
                $updateDonorStmt->execute();
                $updateDonorStmt->close();

                // Update hospital blood inventory
                $selectInventoryQuery = "SELECT * FROM hospital_blood_inventory WHERE hospitalID = ? AND bloodType = ?";
                $selectInventoryStmt = $conn->prepare($selectInventoryQuery);
                $selectInventoryStmt->bind_param('is', $hospitalID, $donorDetails['bloodType']);
                $selectInventoryStmt->execute();
                $result = $selectInventoryStmt->get_result();

                if ($result->num_rows > 0) {
                    // Update existing entry
                    $updateInventoryQuery = "UPDATE hospital_blood_inventory SET quantity = quantity + ? WHERE hospitalID = ? AND bloodType = ?";
                    $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
                    $updateInventoryStmt->bind_param('iis', $donatedBloodCount, $hospitalID, $donorDetails['bloodType']);
                } else {
                    // Insert new entry
                    $updateInventoryQuery = "INSERT INTO hospital_blood_inventory (hospitalID, bloodType, quantity) VALUES (?, ?, ?)";
                    $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
                    $updateInventoryStmt->bind_param('isi', $hospitalID, $donorDetails['bloodType'], $donatedBloodCount);
                }

                $updateInventoryStmt->execute();
                $updateInventoryStmt->close();

                $submissionSuccess = true;

                // Retrieve updated donor details after donation
                $donorDetails = $donor->getDonorDetailsByNIC($donorDetails['donorNIC']);
            } else {
                $error = 'Error submitting the donation details: ' . $conn->error;
            }
        } else {
            $error = 'Please enter the Donated Blood Count.';
        }
    } else {
        $error = 'Donor not found.';
    }
}

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/DonorHandle.css">
    <style>
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .alert {
            margin-top: 20px;
        }
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'HpSidebar.php'; ?>
    <div class="w3-main" style="margin-left:200px;margin-top:43px;">
        <div class="container">
            <h1>Donation Camp Details</h1>
            <form id="donor-form" method="post">
                <div class="form-group">
                    <label for="searchType">Search by:</label>
                    <select class="form-control" id="searchType" name="searchType">
                        <option value="NIC">NIC Number</option>
                        <option value="fullName">Full Name</option>
                    </select>
                </div>
                <div class="form-group" id="searchNIC">
                    <label for="donorNIC">NIC Number:</label>
                    <input type="text" class="form-control" id="donorNIC" name="donorNIC">
                </div>
                <div class="form-group" id="searchFullName" style="display: none;">
                    <label for="donorFullName">Full Name:</label>
                    <input type="text" class="form-control" id="donorFullName" name="donorFullName">
                </div>
                <button type="submit" class="btn btn-primary">Show</button>
            </form>

            <?php if ($donorNotFound): ?>
                <div class="alert alert-danger">Donor not found.</div>
            <?php endif; ?>

            <?php if ($submissionSuccess): ?>
                <div class="alert alert-success">Donation details submitted successfully.</div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($donorDetails): ?>
                <div class="card">
                    <div class="card-header">
                        Donor Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group highlight">
                                    <label for="donor-name">Full Name:</label>
                                    <p id="donor-name"><?= htmlspecialchars($donorDetails['first_name'] . ' ' . $donorDetails['last_name']) ?></p>
                                </div>
                                <div class="form-group highlight">
                                    <label for="donor-blood-type">Blood Type:</label>
                                    <p id="donor-blood-type"><?= htmlspecialchars($donorDetails['bloodType']) ?></p>
                                </div>
                                <div class="form-group highlight">
                                    <label for="donor-email">Email:</label>
                                    <p id="donor-email"><?= htmlspecialchars($donorDetails['email']) ?></p>
                                </div>
                                <div class="form-group highlight">
                                    <label for="NIC">Donor NIC</label>
                                    <p id="donor-email"><?= htmlspecialchars($donorDetails['donorNIC']) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group highlight">
                                    <label for="donor-phone">Phone Number:</label>
                                    <p id="donor-phone"><?= htmlspecialchars($donorDetails['phoneNumber']) ?></p>
                                </div>
                                <div class="form-group highlight">
                                    <label for="donor-username">Username:</label>
                                    <p id="donor-username"><?= htmlspecialchars($donorDetails['username']) ?></p>
                                </div>
                                <div class="form-group highlight">
                                    <label for="donor-address">Address:</label>
                                    <p id="donor-address"><?= htmlspecialchars($donorDetails['address'] . ' ' . $donorDetails['address2']) ?></p>
                                </div>
                                <div class="form-group highlight">
                                    <label for="donor-donation-count">Donation Count:</label>
                                    <p id="donor-donation-count"><?= htmlspecialchars($donorDetails['donation_count']) ?></p>
                                </div>
                            </div>
                        </div>

                        <form id="donation-form" method="post">
                            <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                            <div class="form-group">
                                <label for="donatedBloodCount">Amount of Blood Donated (ml):</label>
                                <input type="number" class="form-control" id="donatedBloodCount" name="donatedBloodCount" required>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('searchType').addEventListener('change', function() {
            var selectedOption = this.value;
            if (selectedOption === 'NIC') {
                document.getElementById('searchNIC').style.display = 'block';
                document.getElementById('searchFullName').style.display = 'none';
            } else if (selectedOption === 'fullName') {
                document.getElementById('searchNIC').style.display = 'none';
                document.getElementById('searchFullName').style.display = 'block';
            }
        });
    </script>
</body>
</html>
