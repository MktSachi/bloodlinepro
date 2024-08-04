<?php
require '../Classes/Database.php';

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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    body {
      background-color: #f0f2f5;
      font-family: 'Poppins', sans-serif;
    }

    .dashboard-container {
      padding: 30px;
    }

    h3 {
      font-weight: 700;
      font-size: 28px;
      color: #2c3e50;
      margin-bottom: 30px;
    }

    .card {
      border-radius: 15px;
      border: none;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 30px;
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      padding: 25px;
    }
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>
  <div class="w3-main" style="margin-left:200px;">
    <div class="dashboard-container">
      <h3 class="text-center mb-4">Blood Availability</h3>

      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-hospital-alt me-2" style="color: #3498db;"></i>Hospital Blood
                Count</h5>
              <canvas id="hospitalBloodCountChart" class="small-chart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-tint me-2" style="color: #e74c3c;"></i>Blood Type Distribution
              </h5>
              <canvas id="bloodTypeChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-heartbeat me-2" style="color: #2ecc71;"></i>Blood Availability
                Status</h5>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Blood Type</th>
                      <th>Available Units</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($bloodTypeData as $type => $count): ?>
                      <tr>
                        <td><?= $type ?></td>
                        <td><?= $count ?></td>
                        <td>
                          <?php if ($count > 100): ?>
                            <span class="badge bg-success">Sufficient</span>
                          <?php elseif ($count > 50): ?>
                            <span class="badge bg-warning">Moderate</span>
                          <?php else: ?>
                            <span class="badge bg-danger">Low</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const bloodTypeData = <?= json_encode($bloodTypeData) ?>;
      const hospitalBloodData = <?= json_encode($hospitals) ?>;

      const ctx = document.getElementById('bloodTypeChart').getContext('2d');
      const bloodTypeChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: Object.keys(bloodTypeData),
          datasets: [{
            label: 'Units',
            data: Object.values(bloodTypeData),
            backgroundColor: 'rgba(231, 76, 60, 0.8)',
            borderColor: 'rgba(231, 76, 60, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            legend: {
              display: false
            },
            title: {
              display: true,
              text: 'Blood Type Distribution',
              font: {
                family: "'Poppins', sans-serif",
                size: 16,
                weight: 'bold'
              }
            }
          },
          animation: {
            animateScale: true,
            animateRotate: true
          }
        }
      });

      const ctxPie = document.getElementById('hospitalBloodCountChart').getContext('2d');
      const hospitalBloodCountChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
          labels: Object.keys(hospitalBloodData),
          datasets: [{
            label: 'Blood Units',
            data: Object.values(hospitalBloodData),
            backgroundColor: [
              '#3498db',
              '#2ecc71',
              '#e74c3c',
              '#f39c12',
              '#9b59b6'
            ],
            borderColor: 'white',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'right',
              labels: {
                font: {
                  family: "'Poppins', sans-serif",
                  size: 12
                }
              }
            },
            title: {
              display: true,
              text: 'Blood Count by Hospital',
              font: {
                family: "'Poppins', sans-serif",
                size: 16,
                weight: 'bold'
              }
            }
          },
          cutout: '60%',
          radius: '90%',
          animation: {
            animateScale: true,
            animateRotate: true
          },
          onClick: function (event, elements) {
            if (elements.length > 0) {
              const index = elements[0].index;
              const hospitalName = this.data.labels[index];
              window.location.href = `hospitalBloodDistribution.php?hospital=${encodeURIComponent(hospitalName)}`;
            }
          }
        }
      });
    });
  </script>
</body>

</html>
