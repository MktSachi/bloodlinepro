<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Profile Details</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/Profile.css">
    </head>
    <body>
        <div class="container-fluid">
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
            // Dummy validation
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];

            // In a real scenario, validate the current password and update the new password in the database
            // Assuming validation is successful for demonstration
            echo 'success';
        }
        ?>
    </body>
</html>
