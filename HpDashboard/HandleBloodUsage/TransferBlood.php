<?php
session_start();
require '../../Classes/Database.php';
require '../BloodInventory/Inventory.php';

$db = new Database();
$conn = $db->getConnection();
$inventory = new Inventory($conn);

$hospitals = $inventory->getHospitals();

$error = '';
$submissionSuccess = false;

if (isset($_SESSION['hospitalID'])) {
    $hpHospitalID = $_SESSION['hospitalID'];
    // Fetch available blood groups for the current hospital
    $availableBloodGroups = $inventory->getAvailableBloodGroups($hpHospitalID);
} else {
    $error = 'Hospital ID not set for the logged-in health professional.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiverHospitalID'], $_POST['bloodType'], $_POST['bloodQuantity'], $_POST['description'])) {
    $senderHospitalID = $hpHospitalID;
    $receiverHospitalID = $_POST['receiverHospitalID'];
    $bloodType = $_POST['bloodType'];
    $bloodQuantity = $_POST['bloodQuantity'];
    $description = $_POST['description'];

    $resultMessage = $inventory->transferBlood($senderHospitalID, $receiverHospitalID, $bloodType, $bloodQuantity, $description);
    if (strpos($resultMessage, 'successful') !== false) {
        $submissionSuccess = true;
    } else {
        $error = $resultMessage;
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
        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
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
                <h2 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Blood Transfer Between Hospitals</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif ($submissionSuccess): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Blood transfer completed successfully!
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="senderHospital" value="HP Hospital" style="display: none;">
                    </div>
                    <div class="mb-3">
                        <label for="receiverHospitalID" class="form-label">Receiver Hospital:</label>
                        <select class="form-select" id="receiverHospitalID" name="receiverHospitalID" required>
                            <option value="">Select a hospital</option>
                            <?php foreach ($hospitals as $hospital): ?>
                                <option value="<?= htmlspecialchars($hospital['hospitalID']) ?>"><?= htmlspecialchars($hospital['hospitalName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bloodType" class="form-label">Blood Type:</label>
                        <select class="form-select" id="bloodType" name="bloodType" required>
                            <option value="">Select a blood type</option>
                            <?php foreach ($availableBloodGroups as $bloodType): ?>
                                <option value="<?= htmlspecialchars($bloodType) ?>"><?= htmlspecialchars($bloodType) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bloodQuantity" class="form-label">Blood Quantity (in ml):</label>
                        <input type="number" class="form-control" id="bloodQuantity" name="bloodQuantity" required>
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