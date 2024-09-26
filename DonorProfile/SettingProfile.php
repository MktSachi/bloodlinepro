<?php include 'DonorProfile.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Profile - BloodLinePro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Profile.css">
</head>
<body>
    <?php include 'sidebar.php' ?>

    <div class="main-content">
        <div class="container">
            <div class="profile-header">
                <h2 class="text-center mb-4">Donor Profile</h2>
                <p class="text-center text-muted"></p>
            </div>

            <div class="profile-section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullName"  class="fw-bold">Full Name</label>
                            <div class="profile-text"><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="bloodType" class="fw-bold">Blood Type</label>
                            <div class="profile-text"><?php echo htmlspecialchars($bloodType); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="donorNIC" class="fw-bold">Donor NIC</label>
                            <div class="profile-text"><?php echo htmlspecialchars($donorNIC); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="fw-bold">Gender</label>
                            <div class="profile-text"><?php echo htmlspecialchars($gender); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phoneNumber" class="fw-bold">Phone Number</label>
                            <div class="profile-text"><?php echo htmlspecialchars($phoneNumber); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="fw-bold">Email</label>
                            <div class="profile-text"><?php echo htmlspecialchars($email); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="fw-bold">Address</label>
                            <div class="profile-text"><?php echo htmlspecialchars($address); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="address2" class="fw-bold">Address 2</label>
                            <div class="profile-text"><?php echo htmlspecialchars($address2); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="ForgotPassword.php" class="btn btn-save me-3">
                    <i class="fas fa-key me-2"></i>Change Password
                </a>
                <a href="ProfileEdite.php" class="btn btn-save">
                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>
    <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>