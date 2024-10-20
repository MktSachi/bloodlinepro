<?php
require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';

session_start();

// Assume we have the hospital ID stored in the session
$hospitalID = $_SESSION['hospitalID'];

$db = new Database();
$conn = $db->getConnection();
$bloodRequest = new BloodRequest($conn);

$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d', strtotime('-30 days'));
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');

$sentRequests = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sentRequests = $bloodRequest->retrieveSentBloodRequestsForDateRange($hospitalID, $startDate, $endDate);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sent Blood Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
            padding-top: 20px;
        }
        </style>
</head>
<body>
    <div class="container mt-5">
        <h2>View Sent Blood Requests</h2>
        <form method="POST" class="mb-4">
            <div class="form-row">
                <div class="col">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" class="form-control" value="<?php echo $startDate; ?>">
                </div>
                <div class="col">
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="endDate" class="form-control" value="<?php echo $endDate; ?>">
                </div>
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">View Requests</button>
                </div>
            </div>
        </form>

        <?php if (!empty($sentRequests)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Receiving Hospital</th>
                        <th>Blood Type</th>
                        <th>Quantity</th>
                        <th>Request Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sentRequests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['receivingHospitalName']); ?></td>
                            <td><?php echo htmlspecialchars($request['bloodType']); ?></td>
                            <td><?php echo htmlspecialchars($request['bloodQuantity']); ?></td>
                            <td><?php echo htmlspecialchars($request['requestDate']); ?></td>
                            <td><?php echo htmlspecialchars($request['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No sent blood requests found for the selected date range.</p>
        <?php endif; ?>
    </div>
</body>
</html>