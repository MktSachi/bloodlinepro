<?php
require '../../donor_registration/Database.php';
require '../../donor_registration/Donor.php';

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

                // Insert data into Donation table
                $query = "INSERT INTO Donation (donorNIC, donatedBloodCount) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('si', $donorNIC, $donatedBloodCount);

                if ($stmt->execute()) {
                    $submissionSuccess = true;
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Donor Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
        .highlight {
            background-color: #f0f0f0; /* Light grey background */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <form id="donor-form" method="post">
            <div class="form-group">
                <label for="donorNIC">NIC Number:</label>
                <input type="text" class="form-control" id="donorNIC" name="donorNIC" required>
            </div>
            <button type="submit" class="btn btn-primary">Show</button>
        </form>

        <?php if ($donorNotFound): ?>
            <div class="alert alert-danger mt-3">Donor not register in our system.</div>
        <?php endif; ?>

        <?php if ($submissionSuccess): ?>
            <div class="alert alert-success mt-3">Donation details submitted successfully.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($donorDetails): ?>
            <div class="card mt-3">
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
                    <!-- Display Profile Picture -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <?php if (!empty($donorDetails['profilePicture'])): ?>
                                <img src="<?= htmlspecialchars($donorDetails['profilePicture']) ?>" alt="Profile Picture" class="profile-picture">
                            <?php else: ?>
                                <p>No profile picture available</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Form to submit donated blood count -->
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

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
