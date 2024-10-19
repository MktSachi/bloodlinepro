<?php
// Include your database connection file
include '../Classes/Database.php';

session_start();
$db = new Database();
$conn = $db->getConnection();

// Assuming the logged-in HP's hospital ID is stored in the session
$hospitalID = $_SESSION['hospitalID'];

// Fetch donor account count
$donorCountQuery = "SELECT COUNT(*) as count FROM donors";
$donorCountResult = $conn->query($donorCountQuery);
$donorCount = $donorCountResult->fetch_assoc()['count'];

// Fetch blood request count
$bloodRequestCountQuery = "SELECT COUNT(*) as count FROM blood_requests";
$bloodRequestCountResult = $conn->query($bloodRequestCountQuery);
$bloodRequestCount = $bloodRequestCountResult->fetch_assoc()['count'];

// Fetch total blood units in inventory for the HP's hospital
$totalHospitalUnits = 0;
$queryHospitalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory WHERE hospitalID = ?";
$stmt = $conn->prepare($queryHospitalBlood);
$stmt->bind_param("i", $hospitalID);
$stmt->execute();
$resultHospitalBlood = $stmt->get_result();

if ($resultHospitalBlood->num_rows > 0) {
    $row = $resultHospitalBlood->fetch_assoc();
    $totalHospitalUnits = $row['total'];
}
$stmt->close();

// Fetch blood usage count
$bloodUsageQuery = "SELECT SUM(bloodQuantity) as total_usage FROM blood_usage";
$bloodUsageResult = $conn->query($bloodUsageQuery);
$bloodUsageCount = 0;
if ($bloodUsageResult->num_rows > 0) {
    $row = $bloodUsageResult->fetch_assoc();
    $bloodUsageCount = $row['total_usage'];
}
$bloodUsageResult->free();

$totalunits = 0;
$queryTotalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory";
$resultTotalBlood = $conn->query($queryTotalBlood);
if ($resultTotalBlood->num_rows > 0) {
    $row = $resultTotalBlood->fetch_assoc();
    $totalunits = $row['total'];
}
$resultTotalBlood->free();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Health-Care Professional Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f2f5;
    }
    .dashboard-container {
      padding: 50px;
    }
    h3 {
      font-weight: 700;
      font-size: 28px; 
      color: #2c3e50;
      margin-bottom: 30px;
    }
    .card {
      border-radius: 10px;
      border: none;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      transition: all 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
    }
    .card-body {
      padding: 25px;
    }
    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 10px;
      color: #34495e;
    }
    .card-text {
      font-size: 2rem;
      font-weight: 700;
      color: #2c3e50;
    }
    .icon {
      font-size: 2.5rem;
      opacity: 0.7;
    }
    .w3-main {
      margin-left: 200px;
    }
    @media (max-width: 768px) {
      .w3-main {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <?php include 'HpSidebar.php'; ?>

  <div class="w3-main">
    <div class="dashboard-container">
      <h3 class="text-center mb-4">Health-Care Professional Dashboard</h3>
      
      <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Donor Account</h5>
                <p class="card-text"><?php echo $donorCount; ?></p>
              </div>
              <i class="fas fa-users icon text-primary"></i>
            </div>
            <a href="DonorAccountHandle.php" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Blood Request</h5>
                <p class="card-text"><?php echo $bloodRequestCount; ?></p>
              </div>
              <i class="fas fa-tint icon text-danger"></i>
            </div>
            <a href="RequestHandle.php" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Hospital Inventory</h5>
                <p class="card-text"><?php echo $totalHospitalUnits; ?></p> <!-- HP Hospital Blood Inventory -->
              </div>
              <i class="fas fa-database icon text-info"></i>
            </div>
            <a href="HospitalInevntoryHandle.php" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Warning</h5>
                <p class="card-text">2</p> <!-- Replace with dynamic warning count if available -->
              </div>
              <i class="fas fa-exclamation-triangle icon text-warning"></i>
            </div>
            <a href="Warning.php" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Blood Usage</h5>
                <p class="card-text"><?php echo $bloodUsageCount; ?></p> <!-- Blood Usage Count -->
              </div>
              <i class="fas fa-chart-line icon text-primary"></i>
            </div>
            <a href="BloodUsage.php" class="stretched-link"></a>
          </div>
        </div>

        

        

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Donation Camp</h5>
                <p class="card-text">2</p> <!-- Replace with dynamic donation camp count if available -->
              </div>
              <i class="fas fa-campground icon text-secondary"></i>
            </div>
            <a href="DonationCamp.php" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Blood Availability</h5>
                <p class="card-text"><?php echo $totalunits; ?></p> <!-- Blood Availability -->
              </div>
              <i class="fas fa-heartbeat icon text-success"></i>
            </div>
            <a href="WholeBloodInv.php" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Notify</h5>
                <p class="card-text">10</p> <!-- Replace with dynamic notification count if available -->
              </div>
              <i class="fas fa-bell icon text-info"></i>
            </div>
            <a href="NotificationHandle.php" class="stretched-link"></a>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
