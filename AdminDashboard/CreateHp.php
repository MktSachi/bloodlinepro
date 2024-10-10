<?php
require '../Classes/Admin.php';

$admin = new Admin();
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $admin->createHealthcareProfessional($_POST);
    if (isset($result['errors'])) {
        $errors = $result['errors'];
    } elseif (isset($result['success'])) {
        $success_message = $result['success'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Professional Account Creation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/CreateHp.css">
    <style>
        .loader {
            position: fixed;
            z-index: 9999;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: none;
        }

        .loader img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        /* Professional Form Styles */
body {
    background-color: #f8f9fa;
    font-family: 'Arial', sans-serif;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    padding: 40px;
}

.page-title {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 30px;
    text-align: center;
    font-weight: bold;
}

.form-group {
    margin-bottom: 25px;
}

label {
    font-weight: 600;
    color: #34495e;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 10px 15px;
    font-size: 16px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
}

select.form-control {
    height: auto;
    padding: 10px 15px;
}

.btn-primary {
    background-color: #4a90e2;
    border-color: #4a90e2;
    padding: 12px 20px;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: background-color 0.15s ease-in-out;
}

.btn-primary:hover {
    background-color: #3a7bc8;
    border-color: #3a7bc8;
}

.alert {
    border-radius: 4px;
    margin-bottom: 30px;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.loader {
    background: rgba(255, 255, 255, 0.8);
}

.loader img {
    width: 80px;
    height: 80px;
}
    </style>
</head>
<body>
    <div class="loader" id="loader">
        <img src="Animation - 1728392021858.gif" alt="Loading...">
    </div>
    <div class="container">
        <div class="page-title">Healthcare Professional Account</div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
                <a href="your_login_page.php" class="btn btn-primary">Go to login page</a>
            </div>
        <?php else: ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="hp_creation_form">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="username">User Name</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select class="form-control" id="position" name="position" required>
                                <option value="">Select Position</option>
                                <option value="ho">House Officer</option>
                                <option value="mho">Medical House Officer</option>
                                <option value="sho">Senior House Officer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="registration_number">Registration Number</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nic_number">NIC Number</label>
                            <input type="text" class="form-control" id="nic_number" name="nic_number" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
        
                <div class="form-group">
                    <label for="hospital">Hospital</label>
                    <select class="form-control" id="hospital" name="hospital" required>
                        <option value="">Select Hospital</option>
                        <?php
                        $hospitals = $admin->getHospitals();
                        foreach ($hospitals as $id => $name) {
                            echo "<option value=\"$id\">$name</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form[name="hp_creation_form"]');
            var loader = document.getElementById('loader');

            form.addEventListener('submit', function() {
                loader.style.display = 'block';
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
