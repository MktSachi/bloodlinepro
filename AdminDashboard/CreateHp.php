<?php
require '../DonorRegistration/Database.php';
require 'CreateHpEmail.php';
require '../DonorRegistration/Validator.php';

function generateRandomPassword($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

function getHospitals() {
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT hospitalID, hospitalName FROM hospitals";
    $result = $conn->query($sql);

    $hospitals = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hospitals[$row['hospitalID']] = $row['hospitalName'];
        }
    }

    $db->close();
    return $hospitals;
}

$validator = new Validator();
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    $username = $validator->sanitizeInput($_POST['username']);
    $roleID = 'hp';
    $active = 2;

    $registration_number = $validator->sanitizeInput($_POST['registration_number']);
    $first_name = $validator->sanitizeInput($_POST['first_name']);
    $last_name = $validator->sanitizeInput($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $position = $validator->sanitizeInput($_POST['position']);
    $phone_number = $validator->sanitizeInput($_POST['phone_number']);
    $nic_number = $validator->sanitizeInput($_POST['nic_number']);
    $hospital_id = filter_var($_POST['hospital'], FILTER_VALIDATE_INT);

    // Validate registration number and position match
    $position_prefix = [
        'ho' => 'HO',
        'mho' => 'MHO',
        'sho' => 'SHO'
    ];
    
    if (!preg_match('/^(' . $position_prefix[$position] . ')\d{5}$/', $registration_number)) {
        $errors[] = "Invalid registration number";
    }

    // Validate NIC number
    if (!$validator->validateNIC($nic_number)) {
        $errors[] = "Invalid NIC number.";
    }

    // Validate phone number
    if (!preg_match('/^\d{10}$/', $phone_number)) {
        $errors[] = "Invalid phone number.";
    }

    // Validate email
    if (!$email) {
        $errors[] = "Invalid email address.";
    }

    if (empty($errors)) {
        try {
            $conn->begin_transaction();

            $password = generateRandomPassword();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmtUser = $conn->prepare("INSERT INTO users (username, password, roleID, createdate, modifieddate, active) VALUES (?, ?, ?, NOW(), NOW(), ?)");
            $stmtUser->bind_param("sssi", $username, $hashed_password, $roleID, $active);
            $stmtUser->execute();

            $userid = $conn->insert_id;

            $stmtHp = $conn->prepare("INSERT INTO healthcare_professionals (hpRegNo, userid, firstname, lastname, email, position, phonenumber, hpnic, hospitalid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtHp->bind_param("ssssssssi", $registration_number, $userid, $first_name, $last_name, $email, $position, $phone_number, $nic_number, $hospital_id);
            $stmtHp->execute();

            $conn->commit();

            $success_message = "Account created successfully!<br>Here are the details:<br><br>";
            $success_message .= "First Name: " . htmlspecialchars($first_name) . "<br>";
            $success_message .= "Last Name: " . htmlspecialchars($last_name) . "<br>";
            $success_message .= "User Name: " . htmlspecialchars($username) . "<br>";
            $success_message .= "Email: " . htmlspecialchars($email) . "<br>";
            $success_message .= "Position: " . htmlspecialchars($position) . "<br>";
            $success_message .= "Registration Number: " . htmlspecialchars($registration_number) . "<br>";
            $success_message .= "NIC Number: " . htmlspecialchars($nic_number) . "<br>";
            $success_message .= "Hospital: " . htmlspecialchars(getHospitals()[$hospital_id]) . "<br>";
            $success_message .= "Contact Number: " . htmlspecialchars($phone_number) . "<br><br>";

            $emailSender = new EmailSender();
            $emailSender->sendConfirmationEmail($email, $first_name, $username, $password);

            $stmtUser->close();
            $stmtHp->close();
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Account creation failed: " . $e->getMessage();
        }
    }

    $db->close();
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
    </style>
</head>
<body>
    <div class="loader" id="loader">
        <img src="Animation - 1720851760552.gif" alt="Loading...">
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
                <div class="form-group">
                    <label for="position">Position</label>
                    <select class="form-control" id="position" name="position" required>
                        <option value="">Select Position</option>
                        <option value="ho">House Officer</option>
                        <option value="mho">Medical House Officer</option>
                        <option value="sho">Senior House Officer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="registration_number">Registration Number</label>
                    <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="nic_number">NIC Number</label>
                    <input type="text" class="form-control" id="nic_number" name="nic_number" required>
                </div>
                <div class="form-group">
                    <label for="hospital">Hospital</label>
                    <select class="form-control" id="hospital" name="hospital" required>
                        <option value="">Select Hospital</option>
                        <?php
                        $hospitals = getHospitals();
                        foreach ($hospitals as $hospitalID => $hospitalName) {
                            echo '<option value="' . htmlspecialchars($hospitalID) . '">' . htmlspecialchars($hospitalName) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone_number">Contact Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
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
</body>
</html>
