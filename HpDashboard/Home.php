<?php
require '../DonorRegistration/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch total blood units
$totalunits = 0;
$queryTotalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory";
$resultTotalBlood = $conn->query($queryTotalBlood);
if ($resultTotalBlood->num_rows > 0) {
    $row = $resultTotalBlood->fetch_assoc();
    $totalunits = $row['total'];
}
$resultTotalBlood->free();

// Fetch hospital blood counts
$hospitals = [];
$queryHospitals = "SELECT hospitalName, SUM(quantity) AS totalBlood FROM hospital_blood_inventory hbi JOIN hospitals h ON hbi.hospitalID = h.hospitalID GROUP BY hospitalName";
$resultHospitals = $conn->query($queryHospitals);
if ($resultHospitals->num_rows > 0) {
    while ($row = $resultHospitals->fetch_assoc()) {
        $hospitals[$row['hospitalName']] = $row['totalBlood'];
    }
}
$resultHospitals->free();

// Fetch blood type distribution
$bloodTypeData = [];
$queryBloodType = "SELECT bloodType, SUM(quantity) AS total FROM hospital_blood_inventory GROUP BY bloodType";
$resultBloodType = $conn->query($queryBloodType);
if ($resultBloodType->num_rows > 0) {
    while ($row = $resultBloodType->fetch_assoc()) {
        $bloodTypeData[$row['bloodType']] = $row['total'];
    }
}
$resultBloodType->free();

// Fetch low stock alerts (quantities < 200)
$lowStockBloodTypes = [];
$queryLowStock = "SELECT bloodType FROM hospital_blood_inventory WHERE quantity < 200";
$resultLowStock = $conn->query($queryLowStock);
if ($resultLowStock->num_rows > 0) {
    while ($row = $resultLowStock->fetch_assoc()) {
        $lowStockBloodTypes[] = $row['bloodType'];
    }
}
$resultLowStock->free();

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BloodLinePro Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    .dashboard-container {
      padding: 20px;
    }
    h3 {
      font-weight: 700;
      font-size: 24px; 
      color: black;
    }
    .card {
      border-radius: 15px;
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .card-body {
      padding: 20px;
    }
    .card-title {
      font-size: 1.2rem;
      margin-bottom: 10px;
    }
    .small-chart {
      max-width: 100%;
      margin: 0 auto;
    }
    .list-group-item {
      border: none;
      border-radius: 0;
    }
    .list-group-item-danger {
      background-color: rgba(255, 0, 0, 0.1);
    }
    .btn-consult {
      background-color: #007bff;
      color: white;
      border-radius: 20px;
    }
    .fixed-action-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
    }
    .heart-image {
      max-width: 100%;
      height: auto;
    }
    .blue-dot {
      color: #007bff;
      font-size: 24px;
    }
    .organ-icon {
      width: 80px;
      height: 80px;
      background-color: #f8f9fa;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    @media (max-width: 768px) {
      .fixed-action-button {
        position: static;
        margin-top: 20px;
      }
    }
  </style>
</head>
<body>
  <?php include 'HpSidebar.php'; ?>
  <div class="w3-main" style="margin-left:200px;">
    <div class="dashboard-container">
      <!-- Total Blood Units as H3 Heading -->
      <div class="row">
        <div class="col-md-12 text-center">
          <h3>Total Blood Units: <?= $totalunits ?></h3>
        </div>
      </div>

      <!-- Existing Dashboard Cards -->
      <div class="row mt-5">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><span class="blue-dot">•</span> Hospital Blood Count</h5>
              <canvas id="hospitalBloodCountChart" class="small-chart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><span class="blue-dot">•</span> Blood Type Distribution</h5>
              <canvas id="bloodTypeChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title"><span class="blue-dot">•</span>Blood</h5>
    <div class="row">
      <!-- Blood Pressure -->
      <div class="col-6 mb-3">
        <small>Blood Pressure</small>
        <h4>116/70</h4>
      </div>
      <!-- Heart Rate -->
      <div class="col-6 mb-3">
        <small>Heart Rate</small>
        <h4>130 bpm</h4>
      </div>
      <!-- Blood Count -->
      <div class="col-6 mb-3">
        <small>Blood Count</small>
        <h4>80.90</h4>
      </div>
      <!-- Glucose Level -->
      <div class="col-6 mb-3">
        <small>Glucose Level</small>
        <h4>230 mg/dL</h4>
      </div>
    </div>
  </div>
</div>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><span class="blue-dot">•</span> My Body Condition</h5>
              <div class="d-flex justify-content-between">
                <div class="organ-icon">Liver</div>
                <div class="organ-icon">Heart</div>
                <div class="organ-icon">Kidney</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Blood type distribution data
        const bloodTypeData = <?= json_encode($bloodTypeData) ?>;
        const hospitalBloodData = <?= json_encode($hospitals) ?>;

        // Blood type chart
        const ctx = document.getElementById('bloodTypeChart').getContext('2d');
        const bloodTypeChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: Object.keys(bloodTypeData),
            datasets: [{
              label: 'Units',
              data: Object.values(bloodTypeData),
              backgroundColor: 'rgba(131, 26, 26)',
              borderColor: 'rgba(131, 26, 26)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });

        // Hospital blood count chart
        const ctxPie = document.getElementById('hospitalBloodCountChart').getContext('2d');
        const hospitalBloodCountChart = new Chart(ctxPie, {
          type: 'pie',
          data: {
            labels: Object.keys(hospitalBloodData),
            datasets: [{
              label: 'Blood Units',
              data: Object.values(hospitalBloodData),
              backgroundColor: [
                '#F88FB2',
                '#D5255E',
                '#A31246',
                '#740030'
              ],
              borderColor: [
                'white',
                'white',  
                'white',
                'white',
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              title: {
                display: true,
                text: 'Blood Count by Hospital'
              }
            },
            onClick: function (event, elements) {
              if (elements.length > 0) {
                const index = elements[0].index;
                const hospitalName = hospitalBloodCountChart.data.labels[index];
                window.location.href = `hospitalBloodDistribution.php?hospital=${encodeURIComponent(hospitalName)}`;
              }
            }
          }
        });
    });
  </script>
</body>
</html>
