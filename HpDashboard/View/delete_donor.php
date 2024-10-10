<?php
require '../../Classes/Database.php';
require '../../Classes/Donor.php';

$donorDeleted = false;
$donorDetails = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorNIC = $_POST['donorNIC'];
    
    $db = new Database();
    $conn = $db->getConnection();
    $donor = new Donor($db);

    if (isset($_POST['confirm_delete'])) {
        $result = $donor->deleteDonorByNIC($donorNIC);
        if ($result) {
            $donorDeleted = true;
        }
    } else {
        $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Donor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;

        }
        .container {
            max-width: 500px;
            margin: 50px auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .success-icon {
            color: #28a745;
        }
        .error-icon {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!$donorDeleted && $donorDetails): ?>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Deletion
                </div>
                <div class="card-body">
                    <h5 class="card-title">Are you sure you want to delete this donor?</h5>
                    <p class="card-text">
                        <strong>Name:</strong> <?= htmlspecialchars($donorDetails['first_name'] . ' ' . $donorDetails['last_name']) ?><br>
                        <strong>NIC:</strong> <?= htmlspecialchars($donorDetails['donorNIC']) ?><br>
                        <strong>Blood Type:</strong> <?= htmlspecialchars($donorDetails['bloodType']) ?>
                    </p>
                    <form method="post">
                        <input type="hidden" name="donorNIC" value="<?= htmlspecialchars($donorNIC) ?>">
                        <input type="hidden" name="confirm_delete" value="1">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Confirm Delete
                        </button>
                        <a href="ViewDonor.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card text-center">
                <div class="card-body">
                    <?php if ($donorDeleted): ?>
                        <div class="icon success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="card-title">Success!</h5>
                        <p class="card-text">Donor deleted successfully.</p>
                    <?php else: ?>
                        <div class="icon error-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h5 class="card-title">Error!</h5>
                        <p class="card-text">Failed to delete donor.</p>
                    <?php endif; ?>
                    <a href="/donor_management.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Donor Management
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>