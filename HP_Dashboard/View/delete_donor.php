<?php
require '../../DonorRegistration/Database.php';
require '../../DonorRegistration/Donor.php';

$donorDeleted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorNIC = $_POST['donorNIC'];

    $db = new Database();
    $conn = $db->getConnection();
    $donor = new Donor($db);

    $result = $donor->deleteDonorByNIC($donorNIC);

    if ($result) {
        $donorDeleted = true;
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletion Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        .success-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .success-card {
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        .success-card .icon {
            font-size: 2rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .success-card .message {
            font-size: 1.25rem;
            font-weight: 700;
            color: #343a40;
            margin-bottom: 0.5rem;
        }
        .success-card .sub-message {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        .success-card .btn-primary {
            background-color: #031529;
            border-color: #031529;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <?php if ($donorDeleted): ?>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="message">Success!</div>
                <div class="sub-message">Donor deleted successfully.</div>
            <?php else: ?>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="message">Error!</div>
                <div class="sub-message">Failed to delete donor.</div>
            <?php endif; ?>
            <a href="/donor_management.php" class="btn btn-primary btn-block">Back to Donor Management</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
