<?php include 'ResetPassWordBack.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        .reset-password-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .reset-password-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        .reset-password-card .icon {
            font-size: 2rem;
            color: #007bff; /* Bootstrap primary color */
            margin-bottom: 1rem;
        }
        .reset-password-card .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #343a40;
            margin-bottom: 1rem;
        }
        .reset-password-card .sub-title {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .reset-password-card .form-group {
            margin-bottom: 1.5rem;
        }
        .reset-password-card .btn-primary {
            background-color: #007bff; /* Bootstrap primary color */
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-card">
            <div class="text-center">
                <i class="fas fa-key icon"></i>
                <h2 class="title">Reset Password</h2>
                <p class="sub-title">Enter your new password below.</p>
            </div>
            <form action="ResetPassword.php" method="post">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
