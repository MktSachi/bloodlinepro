<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/Profile.css">
    <style>
        .w3-main {
            margin-left: 220px;
            padding: 20px;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        #profileForm {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        #successMessage {
            display: none;
            max-width: 600px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .w3-main {
                margin-left: 0;
                padding: 10px;
            }

            #profileForm {
                padding: 15px;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="w3-main">
        <div class="row">
            <div class="col-12">
                <div class="form-title">Admin Profile Details</div>
                <form id="profileForm" class="p-3">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" value="Admin" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" value="bloodlinepro@gmail.com" readonly>
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
                    <button type="submit" class="btn btn-custom">Save Changes</button>
                </form>
                <div id="successMessage" class="alert alert-success mt-3" role="alert">
                    Changes have been successfully saved!
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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

            fetch('Profile.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        document.getElementById('successMessage').style.display = 'block';
                    } else {
                        alert('Error: ' + data);
                    }
                });
        });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        // Validate current password and update new password in the database.
        // Here, for simplicity, just echo 'success' for demonstration.
        $storedPassword = 'admin123'; // This is for demonstration; replace with database logic.

        if ($currentPassword === $storedPassword) {
            // Assume password is updated successfully.
            echo 'success';
        } else {
            // If the current password is incorrect.
            echo 'Incorrect current password';
        }
    }
    ?>
</body>

</html>
