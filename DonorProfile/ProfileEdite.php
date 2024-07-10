<?php include 'DonorProfile.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Donor Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-picture {
            max-width: 250px;
            max-height: 200px;
            border-radius: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .highlight {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .change-profile-link {
            margin-top: 10px;
        }
        body {
            background: linear-gradient(to bottom, #8e1b1b 0%, #230606 100%);          
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            margin: 0 auto; /* This centers the container horizontally */
            text-align: center; /* This centers the content inside the container */
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            display: inline-block; 
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            color: #333;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.8);
            border-color: #333;
        }
        .btn-primary {
            background-color: #031529;
            border-color:  #031529;
        }
        .btn-primary:hover {
            background-color: #0295C4;
            border-color: #0295C4;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container col-md-8">
        <div class="col-md-7 offset-md-2 text-center">
            <?php if (!empty($profilePicture)): ?>
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Profile Picture" class="profile-picture">
            <?php else: ?>
                <p>No profile picture available</p>
            <?php endif; ?>
        </div>
        <h4 class="mb-4">Update Donor Details</h4>
        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_msg ?>
            </div>
        <?php endif; ?>
        <form id="update-donor-form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
                <?php if (isset($errors['username'])): ?>
                    <div class="invalid-feedback"><?= $errors['username'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($email) ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" class="form-control <?= isset($errors['phoneNumber']) ? 'is-invalid' : '' ?>" id="phoneNumber" name="phoneNumber" value="<?= isset($_POST['phoneNumber']) ? htmlspecialchars($_POST['phoneNumber']) : htmlspecialchars($phoneNumber) ?>" required>
                <?php if (isset($errors['phoneNumber'])): ?>
                    <div class="invalid-feedback"><?= $errors['phoneNumber'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" id="address" name="address" value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : htmlspecialchars($address) ?>" required>
                <?php if (isset($errors['address'])): ?>
                    <div class="invalid-feedback"><?= $errors['address'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="address2">Address Line 2 (Optional):</label>
                <input type="text" class="form-control" id="address2" name="address2" value="<?= isset($_POST['address2']) ? htmlspecialchars($_POST['address2']) : htmlspecialchars($address2) ?>">
            </div>
            <div class="form-group">
                <label for="profile_picture">Change Profile Picture:</label>
                <input type="file" class="form-control-file <?= isset($errors['profile_picture']) ? 'is-invalid' : '' ?>" id="profile_picture" name="profile_picture" accept="image/*">
                <?php if (isset($errors['profile_picture'])): ?>
                    <div class="invalid-feedback"><?= $errors['profile_picture'] ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary" name="updateDonor">Update</button>
        </form>
    </div>
</div>

</body>
</html>
