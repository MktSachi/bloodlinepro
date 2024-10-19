<?php include 'DonorProfile.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Donor Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="Profile.css">
    <style>
    .profile-picture-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 30px;
    }

    .profile-picture {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-picture-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .profile-picture-container:hover .profile-picture-overlay {
        opacity: 1;
    }

    .profile-picture-overlay i {
        color: #fff;
        font-size: 24px;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <div class="profile-picture-container">
                    <?php if (!empty($profilePicture) && file_exists($profilePicture)): ?>
                        <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Profile Picture" class="profile-picture">
                    <?php else: ?>
                        <img src="default-profile.jpg" alt="Default Profile Picture" class="profile-picture">
                    <?php endif; ?>
                    <div class="profile-picture-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <h4 class="text-center">Update Your Profile</h4>
                
                <!-- Success Message -->
                <?php if (isset($_SESSION['success_msg'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($_SESSION['success_msg']) ?>
                    </div>
                    <?php unset($_SESSION['success_msg']); ?>
                <?php endif; ?>
                
                <!-- Error Message -->
                <?php if (isset($error_msg)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error_msg) ?>
                    </div>
                <?php endif; ?>
                
                <form id="update-donor-form" method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Username</label>
                        <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber"><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="text" class="form-control <?= isset($errors['phoneNumber']) ? 'is-invalid' : '' ?>" id="phoneNumber" name="phoneNumber" value="<?= htmlspecialchars($phoneNumber) ?>" required>
                        <?php if (isset($errors['phoneNumber'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['phoneNumber']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="address"><i class="fas fa-home"></i> Address</label>
                        <input type="text" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" id="address" name="address" value="<?= htmlspecialchars($address) ?>" required>
                        <?php if (isset($errors['address'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['address']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="address2"><i class="fas fa-map-marker-alt"></i> Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="address2" name="address2" value="<?= htmlspecialchars($address2) ?>">
                    </div>
                    <div class="form-group">
                        <label for="profile_picture"><i class="fas fa-image"></i> Change Profile Picture</label>
                        <input type="file" class="form-control-file <?= isset($errors['profile_picture']) ? 'is-invalid' : '' ?>" id="profile_picture" name="profile_picture" accept="image/*">
                        <?php if (isset($errors['profile_picture'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['profile_picture']) ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="updateDonor"><i class="fas fa-save"></i> Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
