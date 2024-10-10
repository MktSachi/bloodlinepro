<?php
require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';

session_start();

// Initialize database connection and blood request object
$db = new Database();  // Connection is made in the constructor
$conn = $db->getConnection();  // Get the connection using getConnection()
$bloodRequest = new BloodRequest($conn);

// Assuming username is stored in session
$username = $_SESSION['username'];  // Retrieve the logged-in username

// Fetch blood requests specific to HP's hospital
$requests = $bloodRequest->getBloodRequests($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Blood Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h2 {
            color: #dc3545;
            margin-bottom: 30px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">
            <i class="fas fa-tint me-2"></i>Blood Requests
        </h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Requesting Hospital</th>
                        <th>Blood Type</th>
                        <th>Requested Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($requests) > 0): ?>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo $request['requestID']; ?></td>
                                <td><?php echo $request['requestingHospital']; ?></td>
                                <td><?php echo $request['bloodType']; ?></td>
                                <td><?php echo $request['requestedQuantity']; ?></td>
                                <td><?php echo $request['status']; ?></td>
                                <td>
                                    <button class="btn btn-success btn-action me-1" onclick="confirmRequest('<?php echo $request['requestID']; ?>', '<?php echo urlencode($request['requestingHospital']); ?>', '<?php echo urlencode($request['bloodType']); ?>', '<?php echo $request['requestedQuantity']; ?>')">Accept</button>
                                    <button class="btn btn-danger btn-action" onclick="confirmReject('<?php echo $request['requestID']; ?>')">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No blood requests found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmRequest(requestID, requestingHospital, bloodType, requestedQuantity) {
            if (confirm("Are you sure you want to proceed?")) {
                window.location.href = "HandleBloodUsage/TransferBlood.php?requestID=" + requestID + 
                "&receiverHospital=" + encodeURIComponent(requestingHospital) + 
                "&bloodType=" + encodeURIComponent(bloodType) + 
                "&requestedQuantity=" + requestedQuantity;
            }
        }

        function confirmReject(requestID) {
            if (confirm("Are you sure you want to reject this request?")) {
                window.location.href = "RejectBloodRequest.php?requestID=" + requestID;
            }
        }
    </script>
</body>
</html>
