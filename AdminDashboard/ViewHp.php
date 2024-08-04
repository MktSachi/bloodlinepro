<?php
require '../Classes/Database.php';
require '../Classes/Validator.php';

function getHospitals()
{
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
$hpData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    if (isset($_POST['registration_number']) && !isset($_POST['first_name'])) {
        // Step 1: Fetch data based on registration number
        $registration_number = $validator->sanitizeInput($_POST['registration_number']);

        $stmt = $conn->prepare("SELECT * FROM healthcare_professionals WHERE hpRegNo = ?");
        $stmt->bind_param("s", $registration_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $hpData = $result->fetch_assoc();
        } else {
            $errors[] = "No data found for the given registration number.";
        }

        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Step 2: Update form data
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

                $stmtHp = $conn->prepare("UPDATE healthcare_professionals SET firstname = ?, lastname = ?, email = ?, position = ?, phonenumber = ?, hpnic = ?, hospitalid = ? WHERE hpRegNo = ?");
                $stmtHp->bind_param("ssssssis", $first_name, $last_name, $email, $position, $phone_number, $nic_number, $hospital_id, $registration_number);
                $stmtHp->execute();

                $conn->commit();

                $stmtHp->close();
                $success_message = "Healthcare professional account updated successfully!";
            } catch (Exception $e) {
                $conn->rollback();
                $errors[] = "Account update failed: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['delete'])) {
        // Delete functionality
        $registration_number = $validator->sanitizeInput($_POST['registration_number']);

        try {
            $stmtHp = $conn->prepare("DELETE FROM healthcare_professionals WHERE hpRegNo = ?");
            $stmtHp->bind_param("s", $registration_number);
            $stmtHp->execute();

            if ($stmtHp->affected_rows > 0) {
                $success_message = "Healthcare professional account deleted successfully!";
            } else {
                $errors[] = "No data found for the given registration number.";
            }

            $stmtHp->close();
        } catch (Exception $e) {
            $errors[] = "Account deletion failed: " . $e->getMessage();
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
    <title>Healthcare Professional Account Management</title>
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
        #loader {
            display: none;
            /* Add your loader styles here */
        }
    </style>
</head>

<body>
    <div class="loader" id="loader">
        <img src="Animation - 1720851760552.gif" alt="Loading...">
    </div>
    <div class="container">
        <div class="page-title">Healthcare Professional Account Management</div>

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
            </div>
        <?php elseif (empty($hpData)): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="hp_creation_form">
                <div class="form-group">
                    <label for="registration_number">Registration Number</label>
                    <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <?php else: ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="hp_creation_form">
                <input type="hidden" name="registration_number" value="<?php echo htmlspecialchars($hpData['hpRegNo']); ?>">

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($hpData['firstname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($hpData['lastname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <select class="form-control" id="position" name="position" required>
                        <option value="">Select Position</option>
                        <option value="ho" <?php echo $hpData['position'] == 'ho' ? 'selected' : ''; ?>>House Officer</option>
                        <option value="mho" <?php echo $hpData['position'] == 'mho' ? 'selected' : ''; ?>>Medical House Officer</option>
                        <option value="sho" <?php echo $hpData['position'] == 'sho' ? 'selected' : ''; ?>>Senior House Officer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($hpData['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nic_number">NIC Number</label>
                    <input type="text" class="form-control" id="nic_number" name="nic_number" value="<?php echo htmlspecialchars($hpData['hpnic']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="hospital">Hospital</label>
                    <select class="form-control" id="hospital" name="hospital" required>
                        <option value="">Select Hospital</option>
                        <?php
                        $hospitals = getHospitals();
                        foreach ($hospitals as $hospitalID => $hospitalName) {
                            echo '<option value="' . htmlspecialchars($hospitalID) . '"' . ($hpData['hospitalid'] == $hospitalID ? ' selected' : '') . '>' . htmlspecialchars($hospitalName) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone_number">Contact Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($hpData['phonenumber']); ?>" required>
                </div>

                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('form[name="hp_creation_form"]');
            var loader = document.getElementById('loader');

            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent the form from submitting immediately
                setTimeout(function () {
                    loader.style.display = 'block';
                    form.submit(); // Submit the form after showing the loader
                }, 1000); // Delay of 1000 milliseconds (1 second)
            });
        });
    </script>
</body>

</html>
