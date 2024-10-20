<?php
include_once '../Classes/Admin.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin = new Admin();
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    $result = $admin->updateAdminPassword($currentPassword, $newPassword);

    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile Details - BloodLinePro</title>
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
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <h3 class="text-center mb-4">Admin Profile Details</h3>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-user-edit blue-dot"></i>Update Password</h5>
                            <form id="profileForm">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="Admin" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" value="bloodlinepro@gmail.com"
                                        readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="currentPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirmNewPassword" required>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-save"><i class="fas fa-save me-2"></i>Save
                                        Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="successMessage" class="alert alert-success mt-3" role="alert" style="display:none;">
        Changes have been successfully saved!
    </div>

    <div id="errorMessage" class="alert alert-danger mt-3" role="alert" style="display:none;">
        An error occurred. Please try again.
    </div>
    <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('profileForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var currentPassword = document.getElementById('currentPassword').value;
            var newPassword = document.getElementById('newPassword').value;
            var confirmNewPassword = document.getElementById('confirmNewPassword').value;

            if (newPassword !== confirmNewPassword) {
                alert('New password and confirm new password do not match!');
                return;
            }

            var formData = new FormData();
            formData.append('currentPassword', currentPassword);
            formData.append('newPassword', newPassword);

            fetch('AdminProfile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('successMessage').textContent = data.success;
                        document.getElementById('successMessage').style.display = 'block';
                        document.getElementById('errorMessage').style.display = 'none';
                        // Clear the form
                        document.getElementById('profileForm').reset();
                    } else if (data.error) {
                        document.getElementById('errorMessage').textContent = data.error;
                        document.getElementById('errorMessage').style.display = 'block';
                        document.getElementById('successMessage').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('errorMessage').textContent = 'An unexpected error occurred. Please try again.';
                    document.getElementById('errorMessage').style.display = 'block';
                    document.getElementById('successMessage').style.display = 'none';
                });
        });
    </script>
</body>

</html>