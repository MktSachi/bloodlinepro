<?php
session_start();
require '../../DonorRegistration/Database.php';

$db = new Database();
$conn = $db->getConnection();

$hospitals = [];

// Fetch hospitals data from the database
$query = "SELECT hospitalID, hospitalName FROM hospitals";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hospitals[] = $row;
    }
}
$result->free();

$error = '';
$submissionSuccess = false;

// Assume the HP's hospital ID is stored in the session
if (isset($_SESSION['hospitalID'])) {
    $hpHospitalID = $_SESSION['hospitalID'];
} else {
    $error = 'Hospital ID not set for the logged-in health professional.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patientName'], $_POST['gender'], $_POST['bloodType'], $_POST['admissionDate'], $_POST['description'], $_POST['bloodQuantity'])) {
    $hospitalID = $hpHospitalID;
    $patientName = $_POST['patientName'];
    $gender = $_POST['gender'];
    $bloodType = $_POST['bloodType'];
    $admissionDate = $_POST['admissionDate'];
    $description = $_POST['description'];
    $bloodQuantity = (int) $_POST['bloodQuantity'];

    // Validate input
    if (empty($patientName) || empty($gender) || empty($bloodType) || empty($admissionDate) || empty($description) || $bloodQuantity <= 0) {
        $error = 'All fields are required and blood quantity must be positive.';
    } else {
        // Begin transaction
        $conn->begin_transaction();
        try {
            // Update hospital blood inventory
            $updateInventoryQuery = "UPDATE hospital_blood_inventory SET quantity = quantity - ? WHERE hospitalID = ? AND bloodType = ?";
            $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
            $updateInventoryStmt->bind_param('iis', $bloodQuantity, $hospitalID, $bloodType);
            $updateInventoryStmt->execute();

            if ($updateInventoryStmt->affected_rows === 0) {
                throw new Exception('Hospital does not have the specified blood type in inventory or insufficient quantity.');
            }
            $updateInventoryStmt->close();

            // Insert record into patients table
            $insertPatientQuery = "INSERT INTO patients (patientName, gender, bloodType, hospitalID, admissionDate, `condition`, bloodQuantity) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertPatientStmt = $conn->prepare($insertPatientQuery);
            $insertPatientStmt->bind_param('sssisss', $patientName, $gender, $bloodType, $hospitalID, $admissionDate, $description, $bloodQuantity);
            $insertPatientStmt->execute();
            $insertPatientStmt->close();

            $submissionSuccess = true;
            // Commit transaction
            $conn->commit();
        } catch (Exception $e) {
            $error = 'An error occurred during the blood usage process: ' . $e->getMessage();
            // Rollback transaction
            $conn->rollback();
        }
    }
}

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Blood Usage - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-procedures me-2"></i>Patient Blood Usage</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif ($submissionSuccess): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Blood usage recorded successfully!
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="patientName" class="form-label">Patient Name:</label>
                        <input type="text" class="form-control" id="patientName" name="patientName" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender:</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bloodType" class="form-label">Blood Type:</label>
                        <select class="form-select" id="bloodType" name="bloodType" required>
                            <option value="">Select a blood type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="admissionDate" class="form-label">Admission Date:</label>
                        <input type="date" class="form-control" id="admissionDate" name="admissionDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="bloodQuantity" class="form-label">Blood Quantity Used (Units):</label>
                        <input type="number" class="form-control" id="bloodQuantity" name="bloodQuantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Record Usage
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
