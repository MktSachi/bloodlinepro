<?php
session_start();
require '../DonorRegistration/Database.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiverHospitalID'], $_POST['bloodType'], $_POST['bloodQuantity'], $_POST['description'])) {
    $senderHospitalID = $hpHospitalID;
    $receiverHospitalID = $_POST['receiverHospitalID'];
    $bloodType = $_POST['bloodType'];
    $bloodQuantity = $_POST['bloodQuantity'];
    $description = $_POST['description'];

    // Validate input
    if ($senderHospitalID == $receiverHospitalID) {
        $error = 'Sender and receiver hospitals cannot be the same.';
    } else {
        // Begin transaction
        $conn->begin_transaction();
        try {
            // Update sender hospital blood inventory
            $updateSenderInventoryQuery = "UPDATE hospital_blood_inventory SET quantity = quantity - ? WHERE hospitalID = ? AND bloodType = ?";
            $updateSenderInventoryStmt = $conn->prepare($updateSenderInventoryQuery);
            $updateSenderInventoryStmt->bind_param('iis', $bloodQuantity, $senderHospitalID, $bloodType);
            $updateSenderInventoryStmt->execute();

            if ($updateSenderInventoryStmt->affected_rows === 0) {
                throw new Exception('Sender hospital does not have the specified blood type in inventory or insufficient quantity.');
            }
            $updateSenderInventoryStmt->close();

            // Update receiver hospital blood inventory
            $selectReceiverInventoryQuery = "SELECT * FROM hospital_blood_inventory WHERE hospitalID = ? AND bloodType = ?";
            $selectReceiverInventoryStmt = $conn->prepare($selectReceiverInventoryQuery);
            $selectReceiverInventoryStmt->bind_param('is', $receiverHospitalID, $bloodType);
            $selectReceiverInventoryStmt->execute();
            $resultReceiver = $selectReceiverInventoryStmt->get_result();

            if ($resultReceiver->num_rows > 0) {
                $updateReceiverInventoryQuery = "UPDATE hospital_blood_inventory SET quantity = quantity + ? WHERE hospitalID = ? AND bloodType = ?";
                $updateReceiverInventoryStmt = $conn->prepare($updateReceiverInventoryQuery);
                $updateReceiverInventoryStmt->bind_param('iis', $bloodQuantity, $receiverHospitalID, $bloodType);
            } else {
                $updateReceiverInventoryQuery = "INSERT INTO hospital_blood_inventory (hospitalID, bloodType, quantity) VALUES (?, ?, ?)";
                $updateReceiverInventoryStmt = $conn->prepare($updateReceiverInventoryQuery);
                $updateReceiverInventoryStmt->bind_param('isi', $receiverHospitalID, $bloodType, $bloodQuantity);
            }

            $updateReceiverInventoryStmt->execute();
            $updateReceiverInventoryStmt->close();
            $selectReceiverInventoryStmt->close();

            // Insert record into blood_usage table
            $insertBloodUsageQuery = "INSERT INTO blood_usage (senderHospitalID, receiverHospitalID, bloodType, bloodQuantity, description) VALUES (?, ?, ?, ?, ?)";
            $insertBloodUsageStmt = $conn->prepare($insertBloodUsageQuery);
            $insertBloodUsageStmt->bind_param('iisis', $senderHospitalID, $receiverHospitalID, $bloodType, $bloodQuantity, $description);
            $insertBloodUsageStmt->execute();
            $insertBloodUsageStmt->close();

            $submissionSuccess = true;
            // Commit transaction
            $conn->commit();
        } catch (Exception $e) {
            $error = 'An error occurred during the blood transfer process: ' . $e->getMessage();
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
    <title>Blood Transfer Between Hospitals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .alert {
            margin-top: 20px;
        }
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include './HpSidebar.php'; ?>
    <div class="w3-main" style="margin-left:200px;margin-top:43px;">
        <div class="container">
            <h1>Blood Transfer Between Hospitals</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($submissionSuccess): ?>
                <div class="alert alert-success mt-3">Blood transfer completed successfully!</div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="senderHospital">Sender Hospital:</label>
                    <input type="text" class="form-control" id="senderHospital" value="HP Hospital" disabled>
                </div>
                <div class="form-group mt-3">
                    <label for="receiverHospitalID">Receiver Hospital:</label>
                    <select class="form-control" id="receiverHospitalID" name="receiverHospitalID" required>
                        <option value="">Select a hospital</option>
                        <?php foreach ($hospitals as $hospital): ?>
                            <option value="<?= htmlspecialchars($hospital['hospitalID']) ?>"><?= htmlspecialchars($hospital['hospitalName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="bloodType">Blood Type:</label>
                    <select class="form-control" id="bloodType" name="bloodType" required>
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
                <div class="form-group mt-3">
                    <label for="bloodQuantity">Blood Quantity (in ml):</label>
                    <input type="number" class="form-control" id="bloodQuantity" name="bloodQuantity" required>
                </div>
                <div class="form-group mt-3">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Transfer Blood</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
