<?php
session_start();
require_once('../Classes/Database.php');

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();


// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit;
}
$db->close();
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
    <link href="css/AdminDashboard.css" rel="stylesheet">
  <style>
    
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="w3-main">
    <div class="dashboard-container">
      <h3 class="text-center mb-4">Admin</h3>

      <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Health-Care Professional Account</h5>
                <p class="card-text">15</p>
              </div>
              <i class="fas fa-users icon text-primary"></i>
            </div>
            <a href="HpAccountHandle.php" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Blood Request</h5>
                <p class="card-text">14</p>
              </div>
              <i class="fas fa-tint icon text-danger"></i>
            </div>
            <a href="ReqHandle.php" class="stretched-link"></a>
          </div>
        </div>

       
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Inventory</h5>
                <p class="card-text">20</p>
              </div>
              <i class="fas fa-database icon text-info"></i>
            </div>
            <a href="../HpDashboard/HospitalInevntoryHandle.php" class="stretched-link"></a>
          </div>
        </div>
                <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Hospital</h5>
                        <p class="card-text">4</p>
                    </div>
                    <i class="fas fa-hospital icon icon-dark-red"></i>
                </div>
                <a href="Hospital.php" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h5 class="card-title">Blood Availability</h5>
                <p class="card-text">5</p>
              </div>
              <i class="fas fa-heartbeat icon text-success"></i>
            </div>
            <a href="Availability.php" class="stretched-link"></a>
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
