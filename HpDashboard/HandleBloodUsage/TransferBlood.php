<?php
session_start();
require '../../Classes/Database.php';
require '../BloodInventory/Inventory.php';

$db = new Database();
$conn = $db->getConnection();
$inventory = new Inventory($conn);

// Fetch hospitals with both ID and Name
$hospitals = $inventory->getHospitals();

$error = '';
$submissionSuccess = false;

// Retrieve hospitalID from session (logged-in health professional)
if (isset($_SESSION['hospitalID'])) {
    $hpHospitalID = $_SESSION['hospitalID'];
} else {
    $error = 'Hospital ID not set for the logged-in health professional.';
}

// Retrieve data from query string
$requestID = isset($_GET['requestID']) ? $_GET['requestID'] : '';
$receiverHospitalName = isset($_GET['receiverHospital']) ? urldecode($_GET['receiverHospital']) : '';
$bloodType = isset($_GET['bloodType']) ? urldecode($_GET['bloodType']) : '';  // Properly decode blood type
$requestedQuantity = isset($_GET['requestedQuantity']) ? $_GET['requestedQuantity'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiverHospitalID'], $_POST['bloodType'], $_POST['bloodQuantity'], $_POST['description'])) {
    // Start transaction
    $conn->begin_transaction();

    try {
        $senderHospitalID = $hpHospitalID;
        $receiverHospitalID = $_POST['receiverHospitalID'];
        $bloodType = $_POST['bloodType'];
        $bloodQuantity = $_POST['bloodQuantity'];
        $description = $_POST['description'];

        // Transfer blood logic
        $resultMessage = $inventory->transferBlood($senderHospitalID, $receiverHospitalID, $bloodType, $bloodQuantity, $description);

        if (strpos($resultMessage, 'successful') !== false) {
            // Blood transfer successful, now delete the blood request
            if (!empty($requestID)) {
                $query = "DELETE FROM blood_requests WHERE requestID = ?";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param('i', $requestID);
                    if ($stmt->execute()) {
                        $stmt->close();
                        $deleteResult = true;
                    } else {
                        $deleteResult = false;
                    }
                } else {
                    die("Failed to prepare statement: " . $conn->error);
                }

                if (!$deleteResult) {
                    throw new Exception("Blood transfer succeeded, but failed to delete the blood request.");
                }
            }

            $submissionSuccess = true;
            $conn->commit(); // Commit the transaction
        } else {
            throw new Exception($resultMessage); // Handle any error during blood transfer
        }
    } catch (Exception $e) {
        $conn->rollback(); // Rollback the transaction on error
        $error = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Transfer Between Hospitals - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
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
            background-color: #dc3545;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            background-color: #dc3545;
            border: none;
        }
        .btn-primary:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Blood Transfer Between Hospitals</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif ($submissionSuccess): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Blood transfer completed successfully and request deleted!
                    </div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="requestID" value="<?= htmlspecialchars($requestID) ?>">

                    <div class="mb-3">
                        <label for="receiverHospitalID" class="form-label">Receiver Hospital:</label>
                        <select class="form-select" id="receiverHospitalID" name="receiverHospitalID" required>
                            <option value="" disabled>Select a Hospital</option>
                            <?php foreach ($hospitals as $hospital): ?>
                                <option value="<?= htmlspecialchars($hospital['hospitalID']) ?>" <?= ($hospital['hospitalName'] == $receiverHospitalName) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($hospital['hospitalName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bloodType" class="form-label">Blood Type:</label>
                        <select class="form-select" id="bloodType" name="bloodType" required>
                            <option value="<?= htmlspecialchars($bloodType) ?>" selected><?= htmlspecialchars($bloodType) ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bloodQuantity" class="form-label">Blood Quantity (in ml):</label>
                        <input type="number" class="form-control" id="bloodQuantity" name="bloodQuantity" value="<?= htmlspecialchars($requestedQuantity) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Transfer Blood
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
