<?php
require '../../DonorRegistration/Database.php';
require '../../DonorRegistration/Donor.php';

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);

$donorDetails = null;
$donorNotFound = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['donorNIC'])) {
        $donorNIC = $_POST['donorNIC'];
        $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
        if (!$donorDetails) {
            $donorNotFound = true;
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
        .profile-picture {
            max-width: 200px; /* Adjust size as needed */
            max-height: 200px; /* Adjust size as needed */
            border-radius: 50%;
            margin-bottom: 10px;
        }
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
                       <!-- <h2>Profile Picture</h2>-->
                        <?php if (!empty($donorDetails['profilePicture'])): ?>
                            <img src="<?= htmlspecialchars($donorDetails['profilePicture']) ?>" alt="Profile Picture" class="profile-picture">
                        <?php else: ?>
                         <!--   <p>No profile picture available</p>-->
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Buttons for Update and Delete -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <button id="update-donor" class="btn btn-secondary">Update</button>
                    </div>
                    <div class="col-md-6">
                        <form id="delete-donor-form" method="post" action="delete_donor.php" class="d-inline">
                            <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                            <button type="submit" class="btn btn-danger float-right">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form for Updating Donor Details -->
            <div id="update-form-container" class="mt-5" style="display: none;">
                <form id="update-donor-form" method="post" action="update_donor.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">First name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?= htmlspecialchars($donorDetails['first_name']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?= htmlspecialchars($donorDetails['last_name']) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($donorDetails['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($donorDetails['email']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phoneNumber">Phone number</label>
                        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?= htmlspecialchars($donorDetails['phoneNumber']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($donorDetails['address']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="address2">Address 2</label>
                        <input type="text" class="form-control" id="address2" name="address2" value="<?= htmlspecialchars($donorDetails['address2']) ?>" required>
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
                  <!--  <div class="mb-3">
                        <label for="profilePicture">Profile Picture</label>
                        <input type="file" class="form-control-file" id="profilePicture" name="profilePicture">
                    </div>-->
                    <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                    <button type="submit" class="btn btn-primary">Update Donor</button>
                </form>
            </div>
        <?php endif; ?>
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
