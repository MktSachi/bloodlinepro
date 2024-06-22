<?php
require '../../donor_registration/Database.php';
require '../../donor_registration/Donor.php';

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);

$donorDetails = null;
$donorNotFound = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorNIC = $_POST['donorNIC'];
    $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
    if (!$donorDetails) {
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
    <title>Donor Registration</title>
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
            <div class="alert alert-danger mt-3">Donor not found.</div>
        <?php endif; ?>

        <?php if ($donorDetails): ?>
            <div id="donor-details" class="mt-3">
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
                            <p id="donor-address"><?= htmlspecialchars($donorDetails['address']) ?></p>
                        </div>
                        <div class="form-group highlight">
                            <label for="donor-gender">Gender:</label>
                            <p id="donor-gender"><?= htmlspecialchars($donorDetails['gender']) ?></p>
                        </div>
                    </div>
                </div>
                <button id="update-donor" class="btn btn-secondary">Update</button>
                <form id="delete-donor-form" method="post" action="delete_donor.php" class="d-inline">
                    <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        <?php endif; ?>

        <div id="update-form-container" class="mt-5" style="display: none;">
            <form id="update-donor-form" method="post" action="update_donor.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?= htmlspecialchars($donorDetails['first_name']) ?>" required>
                        <div class="invalid-feedback">Valid first name is required.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?= htmlspecialchars($donorDetails['last_name']) ?>" required>
                        <div class="invalid-feedback">Valid last name is required.</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($donorDetails['username']) ?>" required>
                    <div class="invalid-feedback">Your username is required.</div>
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($donorDetails['email']) ?>">
                    <div class="invalid-feedback">Please enter a valid email.</div>
                </div>
                <div class="mb-3">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" value="<?= htmlspecialchars($donorDetails['phoneNumber']) ?>" required>
                    <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                </div>
                <div class="mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($donorDetails['address']) ?>" required>
                    <div class="invalid-feedback">Please enter your address.</div>
                </div>
                <div class="mb-3">
                    <label for="gender">Gender</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" <?= ($donorDetails['gender'] === 'male') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="genderMale">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female" <?= ($donorDetails['gender'] === 'female') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="genderFemale">Female</label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="bloodType">Blood Type:</label>
                    <select id="bloodType" name="bloodType" class="form-control" required>
                        <option value="A+" <?= ($donorDetails['bloodType'] === 'A+') ? 'selected' : '' ?>>A+</option>
                        <option value="A-" <?= ($donorDetails['bloodType'] === 'A-') ? 'selected' : '' ?>>A-</option>
                        <option value="B+" <?= ($donorDetails['bloodType'] === 'B+') ? 'selected' : '' ?>>B+</option>
                        <option value="B-" <?= ($donorDetails['bloodType'] === 'B-') ? 'selected' : '' ?>>B-</option>
                        <option value="AB+" <?= ($donorDetails['bloodType'] === 'AB+') ? 'selected' : '' ?>>AB+</option>
                        <option value="AB-" <?= ($donorDetails['bloodType'] === 'AB-') ? 'selected' : '' ?>>AB-</option>
                        <option value="O+" <?= ($donorDetails['bloodType'] === 'O+') ? 'selected' : '' ?>>O+</option>
                        <option value="O-" <?= ($donorDetails['bloodType'] === 'O-') ? 'selected' : '' ?>>O-</option>
                    </select>
                </div>
                <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                <button type="submit" class="btn btn-primary">Update Donor</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('update-donor').addEventListener('click', function() {
            document.getElementById('update-form-container').style.display = 'block';
        });
    </script>
</body>
</html>
