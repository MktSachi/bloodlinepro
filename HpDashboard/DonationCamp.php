<?php
require '../DonorRegistration/Database.php';
require '../DonorRegistration/Donor.php';

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);

$donorDetails = null;
$donorNotFound = false;
$submissionSuccess = false;
$error = '';

$hospitals = []; // Initialize hospitals array


$query = "SELECT hospitalID, hospitalName FROM hospitals";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hospitals[] = $row;
    }
}
$result->free();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['donorNIC'])) {
        $donorNIC = $_POST['donorNIC'];
        $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
        if (!$donorDetails) {
            $donorNotFound = true;
        } else {
            if (isset($_POST['donatedBloodCount'], $_POST['hospitalID'])) {
                $donatedBloodCount = $_POST['donatedBloodCount'];
                $hospitalID = $_POST['hospitalID'];
                $bloodType = $donorDetails['bloodType'];

                
                $donationDate = date('Y-m-d');
                $bloodExpiryDate = date('Y-m-d', strtotime($donationDate . ' + 40 days'));

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
                    // Check if the blood type entry exists
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
                    $error = 'Error submitting the donation details.';
                }
            } else {
                $error = 'Please enter donated blood count and select a hospital.';
            }
        }
    } else {
        $donorNotFound = true;
    }
}

$db->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>Donor Account</title>
    <link rel="stylesheet" href="css/DonorHandle.css">  
    <style>
        label {
            font-weight: bold;
        }
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
    <?php include './HpSidebar.php'; ?>
    <div class="w3-main" style="margin-left:200px;margin-top:43px;">

    <div class="container">
        <h1>Donation Camp Details</h1>
        <form id="donor-form" method="post">
            <div class="form-group">
                <label for="donorNIC">NIC Number:</label>
                <input type="text" class="form-control" id="donorNIC" name="donorNIC" required>
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
                            <label for="donatedBloodCount">Blood Count:</label>
                            <input type="number" class="form-control" id="donatedBloodCount" name="donatedBloodCount" required>
                        </div>
                        <div class="form-group">
                            <label for="hospitalID">Hospital:</label>
                            <select class="form-control" id="hospitalID" name="hospitalID" required>
                                <option value="">Select a hospital</option>
                                <?php foreach ($hospitals as $hospital): ?>
                                    <option value="<?= htmlspecialchars($hospital['hospitalID']) ?>"><?= htmlspecialchars($hospital['hospitalName']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Submit Donation</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
