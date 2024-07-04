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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['donorNIC'])) {
        $donorNIC = $_POST['donorNIC'];
        $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
        if (!$donorDetails) {
            $donorNotFound = true;
        } else {
            // Handle form submission
            if (isset($_POST['donatedBloodCount'])) {
                $donatedBloodCount = $_POST['donatedBloodCount'];

                // Calculate the blood expiry date (40 days after current date)
                $donationDate = date('Y-m-d'); // Current date
                $bloodExpiryDate = date('Y-m-d', strtotime($donationDate . ' + 40 days'));

                // Insert data into donations table
                $query = "INSERT INTO donations (donorNIC, donatedBloodCount, donationDate, bloodExpiryDate) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('siss', $donorNIC, $donatedBloodCount, $donationDate, $bloodExpiryDate);

                if ($stmt->execute()) {
                    // Update the donation count in donors table
                    $updateQuery = "UPDATE donors SET donation_count = donation_count + 1 WHERE donorNIC = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param('s', $donorNIC);
                    $updateStmt->execute();
                    $updateStmt->close();
                    
                    $submissionSuccess = true;
                    
                    // Retrieve updated donor details
                    $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
                } else {
                    $error = 'Error submitting the donation details.';
                }
                $stmt->close();
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
                                <label for="donor-gender">Gender:</label>
                                <p id="donor-gender"><?= htmlspecialchars($donorDetails['gender']) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <?php if (!empty($donorDetails['profile_picture'])): ?>
                                <img src="<?= htmlspecialchars($donorDetails['profile_picture']) ?>" alt="Profile Picture" class="profile-picture">
                            <?php else: ?>
                                <p>No profile picture available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group highlight">
                        <label for="donor-donation-count">Total Donations:</label>
                        <p id="donor-donation-count"><?= htmlspecialchars($donorDetails['donation_count']) ?></p>
                    </div>
                    <form method="post" class="mt-3">
                        <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                        <div class="form-group">
                            <label for="donatedBloodCount">Donated Blood Count:</label>
                            <input type="number" class="form-control" id="donatedBloodCount" name="donatedBloodCount" required>
                        </div>
                        <button type="submit" class="btn btn-success">Submit Donation</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <?php include 'Footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>