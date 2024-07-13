<?php
require '../DonorRegistration/Database.php';

$db = new Database();
$conn = $db->getConnection();

$hospitalName = $_GET['hospital'] ?? '';
$hospitalName = $conn->real_escape_string($hospitalName);

// Fetch blood type distribution for the specific hospital
$bloodTypeData = [];
$queryBloodType = "SELECT bloodType, quantity FROM hospital_blood_inventory hbi JOIN hospitals h ON hbi.hospitalID = h.hospitalID WHERE h.hospitalName = '$hospitalName'";
$resultBloodType = $conn->query($queryBloodType);
if ($resultBloodType->num_rows > 0) {
    while ($row = $resultBloodType->fetch_assoc()) {
        $bloodTypeData[$row['bloodType']] = $row['quantity'];
    }
}
$resultBloodType->free();

// Fetch hospital contact details
$hospitalDetails = [];
$queryHospitalDetails = "SELECT * FROM hospitals WHERE hospitalName = '$hospitalName'";
$resultHospitalDetails = $conn->query($queryHospitalDetails);
if ($resultHospitalDetails->num_rows > 0) {
    $hospitalDetails = $resultHospitalDetails->fetch_assoc();
}
$resultHospitalDetails->free();

// Fetch total blood units for the specific hospital
$totalUnits = 0;
$queryTotalUnits = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory WHERE hospitalID = (SELECT hospitalID FROM hospitals WHERE hospitalName = '$hospitalName')";
$resultTotalUnits = $conn->query($queryTotalUnits);
if ($resultTotalUnits->num_rows > 0) {
    $row = $resultTotalUnits->fetch_assoc();
    $totalUnits = $row['total'];
}
$resultTotalUnits->free();

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BloodLinePro - <?= htmlspecialchars($hospitalName) ?> Blood Distribution</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f4f7f6;
      color: #333;
      font-family: 'Roboto', sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }
    .card-header {
      background: linear-gradient(135deg, #5a090a 0%, #060606 100%);
      color: #fff;
      border-radius: 10px 10px 0 0;
      font-size: 1.5rem;
      text-align: center;
    }
    .card-body {
      background-color: #fff;
      color: #333;
      border-radius: 0 0 10px 10px;
      padding: 20px;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
      border-radius: 50px;
      padding: 10px 20px;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .chart-container {
      position: relative;
      height: 400px;
    }
    .contact-info {
      margin-top: 20px;
    }
    .contact-info p {
      margin: 0;
      padding: 0;
    }
    .icon {
      margin-right: 10px;
      color: #007bff;
    }
  </style>
</head>
<body>
<?php include 'HpSidebar.php'; ?>
<div class="w3-main" style="margin-left:200px;margin-top:43px;">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Blood Distribution <br> <?= htmlspecialchars($hospitalName) ?>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="hospitalBloodDistributionChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Total Blood Units
          </div>
          <div class="card-body">
            <h3><?= $totalUnits ?></h3>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            Contact Information
          </div>
          <div class="card-body contact-info">
            <?php if ($hospitalDetails): ?>
              <p><i class="fas fa-map-marker-alt icon"></i><strong>Address:</strong> <?= htmlspecialchars($hospitalDetails['address']) ?></p>
              <p><i class="fas fa-envelope icon"></i><strong>Email:</strong> <?= htmlspecialchars($hospitalDetails['email']) ?></p>
              <p><i class="fas fa-phone icon"></i><strong>Phone:</strong> <?= htmlspecialchars($hospitalDetails['phone'] ?? 'N/A') ?></p>
            <?php else: ?>
              <p>No contact information available for this hospital.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center">
      <a href="index.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>
  </div>
            </div>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const bloodTypeData = <?= json_encode($bloodTypeData) ?>;
        const ctx = document.getElementById('hospitalBloodDistributionChart').getContext('2d');
        const hospitalBloodDistributionChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: Object.keys(bloodTypeData),
            datasets: [{
              label: 'Units',
              data: Object.values(bloodTypeData),
              backgroundColor: 'rgb(131, 26, 26)',
              borderColor: 'rgb(131, 26, 26)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            },
            plugins: {
              legend: {
                display: false
              }
            }
          }
        });
    });
  </script>
</body>
</html>
