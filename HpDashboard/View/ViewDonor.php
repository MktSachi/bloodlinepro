<?php
require '../../Classes/Database.php';
require '../../Classes/Donor.php';

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
    <title>Donor Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
            padding: 15px;
        }
        .card-body {
            padding: 20px;
        }
        .profile-picture {
            max-width: 150px;
            max-height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 3px solid #007bff;
        }
        .highlight {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 5px solid #007bff;
        }
        .highlight label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="mb-4"><i class="fas fa-user mr-2"></i>Donor Management</h3>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-search mr-2"></i> Donor Search
            </div>
            <div class="card-body">
                <form id="donor-form" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" id="donorNIC" name="donorNIC" placeholder="Enter NIC Number" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($donorNotFound): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i> Donor not found.
            </div>
        <?php endif; ?>

        <?php if ($donorDetails): ?>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user mr-2"></i> Donor Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <?php if (!empty($donorDetails['profile_picture'])): ?>
                                <img src="<?= htmlspecialchars($donorDetails['profile_picture']) ?>" alt="Profile Picture" class="profile-picture img-fluid">
                            <?php else: ?>
                                <img src="picture.jpg" alt="Profile Picture" class="profile-picture img-fluid">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="highlight">
                                        <label>Full Name:</label>
                                        <p><?= htmlspecialchars($donorDetails['first_name'] . ' ' . $donorDetails['last_name']) ?></p>
                                    </div>
                                    <div class="highlight">
                                        <label>Blood Type:</label>
                                        <p><?= htmlspecialchars($donorDetails['bloodType']) ?></p>
                                    </div>
                                    <div class="highlight">
                                        <label>Email:</label>
                                        <p><?= htmlspecialchars($donorDetails['email']) ?></p>
                                    </div>
                                    <div class="highlight">
                                        <label>Gender:</label>
                                        <p><?= htmlspecialchars($donorDetails['gender']) ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="highlight">
                                        <label>Phone Number:</label>
                                        <p><?= htmlspecialchars($donorDetails['phoneNumber']) ?></p>
                                    </div>
                                    <div class="highlight">
                                        <label>Address:</label>
                                        <p><?= htmlspecialchars($donorDetails['address'] . ' ' . $donorDetails['address2']) ?></p>
                                    </div>
                                    <div class="highlight">
                                        <label>Username:</label>
                                        <p><?= htmlspecialchars($donorDetails['username']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <button id="update-donor" class="btn btn-secondary">
                                <i class="fas fa-edit mr-1"></i> Update
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <form id="delete-donor-form" method="post" action="delete_donor.php" class="d-inline">
                                <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                                <button id="update-donor" class="btn btn-secondary" data-toggle="modal" data-target="#updateDonorModal">
            <i class="fas fa-edit mr-1"></i> Update
        </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Form (Initially Hidden) -->
            <div id="update-form-container" class="mt-5" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-edit mr-2"></i> Update Donor Information
                    </div>
                    <div class="card-body">
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
                                <label>Gender</label>
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
            </div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('update-donor').addEventListener('click', function() {
            document.getElementById('update-form-container').style.display = 'block';
        });

        // Remove this code
document.getElementById('update-donor').addEventListener('click', function() {
    document.getElementById('update-form-container').style.display = 'block';
});
    </script>
    
</body>
</html>