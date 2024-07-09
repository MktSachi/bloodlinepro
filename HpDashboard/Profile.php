<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Professional Account Creation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .form-container {      
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
            margin-right: 10px;
            white-space: nowrap;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-control {
            flex: 1;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include 'HpSidebar.php'; ?>

    <div class="w3-main" style="margin-left:230px;margin-top:px;">
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="registration_number" class="form-label">Registration Number</label>
                    <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                </div>
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="username" class="form-label">User Name</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="nic_number" class="form-label">NIC Number</label>
                    <input type="text" class="form-control" id="nic_number" name="nic_number" required>
                </div>
                <div class="form-group">
                    <label for="hospital" class="form-label">Hospital</label>
                    <input type="text" class="form-control" id="hospital" name="hospital" required>
                </div>
                <div class="form-group">
                    <label for="position" class="form-label">Position</label>
                    <input type="text" class="form-control" id="position" name="position" required>
                </div>
                <div class="form-group">
                    <label for="phone_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
