<?php
require '../DonorRegistration/Database.php';
require 'CreateHpEmail.php';

function generateRandomPassword($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

// Function to fetch hospitals from the database
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Professional Account Creation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/CreateHp.css">
</head>
<body>
    <div class="container">
        <div class="page-title">Healthcare Professional Account</div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();

            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $roleID = 'hp';
            $active = 2;

            $registration_number = filter_var($_POST['registration_number'], FILTER_VALIDATE_INT);
            $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
            $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
            $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_STRING);
            $nic_number = filter_var($_POST['nic_number'], FILTER_SANITIZE_STRING);
            $hospital_id = filter_var($_POST['hospital'], FILTER_VALIDATE_INT);

            try {
                $conn->begin_transaction();

                $password = generateRandomPassword();
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmtUser = $conn->prepare("INSERT INTO users (username, password, roleID, createdate, modifieddate, active) VALUES (?, ?, ?, NOW(), NOW(), ?)");
                $stmtUser->bind_param("sssi", $username, $hashed_password, $roleID, $active);
                $stmtUser->execute();

                $userid = $conn->insert_id;

                $stmtHp = $conn->prepare("INSERT INTO healthcare_professionals (hpRegNo, userid, firstname, lastname, email, position, phonenumber, hpnic, hospitalid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmtHp->bind_param("iissssssi", $registration_number, $userid, $first_name, $last_name, $email, $position, $phone_number, $nic_number, $hospital_id);
                $stmtHp->execute();

                $conn->commit();

                echo '<div class="success-message">
                        Account created successfully!<br>Here are the details:<br><br>';
                echo "First Name: " . htmlspecialchars($first_name) . "<br>";
                echo "Last Name: " . htmlspecialchars($last_name) . "<br>";
                echo "User Name: " . htmlspecialchars($username) . "<br>";
                echo "Email: " . htmlspecialchars($email) . "<br>";
                echo "Position: " . htmlspecialchars($position) . "<br>";
                echo "Registration Number: " . htmlspecialchars($registration_number) . "<br>";
                echo "NIC Number: " . htmlspecialchars($nic_number) . "<br>";
                echo "Hospital: " . htmlspecialchars(getHospitals()[$hospital_id]) . "<br>"; // Fetch hospital name
                echo "Contact Number: " . htmlspecialchars($phone_number) . "<br><br>";

                $emailSender = new EmailSender();
                $emailSender->sendConfirmationEmail($email, $first_name, $username, $password);

                echo '<a href="your_login_page.php">Go to login page</a></div>';

                $stmtUser->close();
                $stmtHp->close();
            } catch (Exception $e) {
                $conn->rollback();
                echo '<div class="error-message">Account creation failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }

            $db->close();
        } else {
        ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                    <label for="position" class="form-label">Position</label>
                    <select class="form-control" id="position" name="position" required>
                        <option value="">Select Position</option>
                        <option value="ho">House Officer</option>
                        <option value="mho">Medical House Officer</option>
                        <option value="sho">Senior House Officer</option>
                    </select>
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
                    <label for="phone_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                </div>
                <button type="submit" class="btn btn-custom" name="submit">Create Account</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
