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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #dc3545;
            --secondary-color: #007bff;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            background-color: var(--primary-color);
            color: #fff;
            font-weight: bold;
            padding: 15px;
            border-bottom: none;
        }
        .highlight label {
            color: var(--secondary-color);
            font-weight: bold;
        }
        .highlight {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .hp-info {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .hp-info h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        .hp-avatar {
            width: 100px;
            height: 100px;
            background-color: var(--secondary-color);
            color: #fff;
            font-size: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            margin: 0 auto 20px;
        }
        .hp-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .animated {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #content-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div id="content-loader">
        <div class="spinner"></div>
    </div>

    <div class="container mt-4">
        <h1 class="mb-4 text-center"><i class="fas fa-user-md"></i> Healthcare Professional Account Management</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger animated">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
    <div class="alert alert-success animated">
        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
    </div>
<?php endif; ?>

        <div class="card animated">
            <div class="card-header">
                <i class="fas fa-search"></i> Healthcare Professional Search
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="hp">
                    <div class="input-group">
                        <input type="text" class="form-control" id="registration_number" name="registration_number" placeholder="Enter Registration Number" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($hpData)): ?>
            <div class="row animated">
                <div class="col-md-6">
                    <div class="hp-info">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="hp_update_form">
                        <div class="hp-avatar">
                            <?= strtoupper(substr($hpData['firstname'], 0, 1) . substr($hpData['lastname'], 0, 1)) ?>
                        </div>
                        <h3 class="text-center"><?= htmlspecialchars($hpData['firstname'] . ' ' . $hpData['lastname']) ?></h3>
                        <div class="highlight">
                            <label><i class="fas fa-id-card"></i> Registration Number:</label>
                            <p><?= htmlspecialchars($hpData['hpRegNo']) ?></p>
                        </div>
                        <div class="highlight">
                            <label><i class="fas fa-user-tag"></i> Position:</label>
                            <p><?= htmlspecialchars($hpData['position']) ?></p>
                        </div>
                        <div class="highlight">
                            <label><i class="fas fa-envelope"></i> Email:</label>
                            <p><?= htmlspecialchars($hpData['email']) ?></p>
                        </div>
                        <div class="highlight">
                            <label><i class="fas fa-id-badge"></i> NIC Number:</label>
                            <p><?= htmlspecialchars($hpData['hpnic']) ?></p>
                        </div>
                        <div class="highlight">
                            <label><i class="fas fa-hospital"></i> Hospital:</label>
                            <p>
                                <?php
                                $hospitals = getHospitals();
                                echo htmlspecialchars($hospitals[$hpData['hospitalid']]);
                                ?>
                            </p>
                        </div>
                    
                        <div class="highlight">
                            <label><i class="fas fa-phone"></i> Contact Number:</label>
                            <p><?= htmlspecialchars($hpData['phonenumber']) ?></p>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="hp-form">
                        <h3 class="text-center mb-4">Update Healthcare Professional</h3>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="hp_update_form">
                            <input type="hidden" name="registration_number" value="<?php echo htmlspecialchars($hpData['hpRegNo']); ?>">

                            <div class="mb-3">
                                <label for="first_name" class="form-label"><i class="fas fa-user"></i> First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($hpData['firstname']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label"><i class="fas fa-user"></i> Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($hpData['lastname']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="position" class="form-label"><i class="fas fa-user-tag"></i> Position</label>
                                <select class="form-control" id="position" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="ho" <?php echo $hpData['position'] == 'ho' ? 'selected' : ''; ?>>House Officer</option>
                                    <option value="mho" <?php echo $hpData['position'] == 'mho' ? 'selected' : ''; ?>>Medical House Officer</option>
                                    <option value="sho" <?php echo $hpData['position'] == 'sho' ? 'selected' : ''; ?>>Senior House Officer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($hpData['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nic_number" class="form-label"><i class="fas fa-id-badge"></i> NIC Number</label>
                                <input type="text" class="form-control" id="nic_number" name="nic_number" value="<?php echo htmlspecialchars($hpData['hpnic']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="hospital" class="form-label"><i class="fas fa-hospital"></i> Hospital</label>
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
                            <div class="mb-3">
                                <label for="phone_number" class="form-label"><i class="fas fa-phone"></i> Contact Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($hpData['phonenumber']); ?>" required>
                            </div>

                            <button type="submit" name="update" class="btn btn-primary w-100 mb-2">
    <i class="fas fa-save"></i> Update
</button>
<button type="submit" name="delete" class="btn btn-danger w-100">
    <i class="fas fa-trash-alt"></i> Delete
</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent form resubmission on page reload
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Show loader when form is submitted and hide after 1.5 seconds
        document.getElementById('hp').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting immediately
            document.getElementById('content-loader').style.display = 'flex';
            
            setTimeout(function() {
                document.getElementById('content-loader').style.display = 'none';
                e.target.submit(); // Submit the form after the loader disappears
            }, 1500); // 1500 milliseconds = 1.5 seconds
        });
    </script>
</body>

</html>