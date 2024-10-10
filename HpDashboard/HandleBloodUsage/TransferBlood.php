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
$popupMessage = '';  // New variable for the message from Inventory

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
        $popupMessage = $resultMessage;  // Set the message from Inventory

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
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
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
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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

        .success-container, .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
            background-color: #f8f9fa;
        }

        .success-card, .error-card {
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        .success-card .icon, .error-card .icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .success-card .icon {
            color: #28a745;
        }

        .error-card .icon {
            color: #dc3545;
        }

        .success-card .message, .error-card .message {
            font-size: 1.25rem;
            font-weight: 700;
            color: #343a40;
            margin-bottom: 0.5rem;
        }

        .success-card .sub-message, .error-card .sub-message {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .success-card .btn-primary, .error-card .btn-primary {
            background-color: #031529;
            border-color: #031529;
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
                    <!-- Error Modal Trigger -->
                    <script>
                        window.onload = function () {
                            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                            errorModal.show();
                        };
                    </script>
                <?php elseif ($submissionSuccess): ?>
                    <!-- Success Modal Trigger -->
                    <script>
                        window.onload = function () {
                            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            successModal.show();
                        };
                    </script>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="requestID" value="<?= htmlspecialchars($requestID) ?>">

                    <div class="mb-3">
                        <label for="receiverHospitalID" class="form-label">Receiver Hospital:</label>
                        <select class="form-select" id="receiverHospitalID" name="receiverHospitalID" required>
                            <option value="" disabled>Select a Hospital</option>
                            <?php foreach ($hospitals as $hospital): ?>
                                <option value="<?= htmlspecialchars($hospital['hospitalID']) ?>"
                                    <?= ($hospital['hospitalName'] == $receiverHospitalName) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($hospital['hospitalName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bloodType" class="form-label">Blood Type:</label>
                        <select class="form-select" id="bloodType" name="bloodType" required>
                            <option value="<?= htmlspecialchars($bloodType) ?>" selected>
                                <?= htmlspecialchars($bloodType) ?>
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bloodQuantity" class="form-label">Blood Quantity (in ml):</label>
                        <input type="number" class="form-control" id="bloodQuantity" name="bloodQuantity"
                            value="<?= htmlspecialchars($requestedQuantity) ?>" required>
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

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
                <div class="success-card">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="message">Success!</div>
                    <div class="sub-message"><?= htmlspecialchars($popupMessage) ?></div> <!-- Using dynamic message here -->
                    <a href="../RequestHandle.php" class="btn btn-primary btn-block">Go to Request List</a>
                </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
                <div class="error-card">
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="message">Unsuccess!</div>
                    <div class="sub-message"><?= htmlspecialchars($popupMessage) ?></div> <!-- Using dynamic message here -->
                    <a href="../RequestHandle.php" class="btn btn-primary btn-block">Go to Request List</a>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
