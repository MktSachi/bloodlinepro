<?php include 'HpProfileValidation.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
        }
        .main-content {
            margin-left: 200px;
            padding: 30px;
        }
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }
        .card-body {
            padding: 25px;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #34495e;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .blue-dot {
            color: #3498db;
            font-size: 20px;
            margin-right: 10px;
        }
        .btn-save {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-save:hover {
            background-color: #2980b9;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php include 'HpSidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <h3 class="text-center mb-4">Edit Profile</h3>
            <div class="row">
                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-user-edit blue-dot"></i>Personal Information</h5>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    <?php if (isset($errors['email'])) echo '<span class="text-danger">'.$errors['email'].'</span>'; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="phoneNumber" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($phoneNumber); ?>" required>
                                    <?php if (isset($errors['phoneNumber'])) echo '<span class="text-danger">'.$errors['phoneNumber'].'</span>'; ?>
                                </div>
                               
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                                    <?php if (isset($errors['username'])) echo '<span class="text-danger">'.$errors['username'].'</span>'; ?>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" name="updateHP" class="btn btn-save me-2"><i class="fas fa-save me-2"></i>Save Changes</button>
                                    <a href="ForgotPassword.php" class="btn btn-save"><i class="fas fa-key me-2"></i>Change Password</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>