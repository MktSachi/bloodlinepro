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

// Fetch low stock alerts
$lowStockBloodTypes = [];
$queryLowStock = "SELECT bloodType FROM hospital_blood_inventory WHERE quantity < 11";
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
  <link id="theme-link" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .dashboard-container {
      margin: 20px;
    }
    .card {
      margin-bottom: 20px;
    }
    .small-chart {
      max-width: 400px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Blood Units</h5>
            <p class="card-text" id="total-blood-units"><?= $totalunits ?></p>
            <h5 class="card-title">Hospital Blood Count</h5>
            <canvas id="hospitalBloodCountChart" class="small-chart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Blood Type Distribution</h5>
            <canvas id="bloodTypeChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Low Stock Alerts</h5>
            <ul id="low-stock-alerts" class="list-group">
                <?php foreach ($lowStockBloodTypes as $type): ?>
                    <li class="list-group-item list-group-item-danger">Low stock alert: <?= htmlspecialchars($type) ?></li>
                <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div style="position: fixed; bottom: 20px; right: 20px;">
    <button class="btn btn-secondary" onclick="toggleDarkMode()">Toggle Dark Mode</button>
  </div>

  <script>
    // Blood type distribution data
    const bloodTypeData = <?= json_encode($bloodTypeData) ?>;
    const hospitalBloodData = <?= json_encode($hospitals) ?>;

    document.getElementById('total-blood-units').innerText = <?= $totalunits ?>;

    // Blood type chart
    const ctx = document.getElementById('bloodTypeChart').getContext('2d');
    const bloodTypeChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: Object.keys(bloodTypeData),
        datasets: [{
          label: 'Units',
          data: Object.values(bloodTypeData),
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
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
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)'
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
        }
      }
    });

    // Low stock alerts
    const lowStockAlerts = document.getElementById('low-stock-alerts');
    <?php foreach ($lowStockBloodTypes as $type): ?>
        const li<?= md5($type) ?> = document.createElement('li');
        li<?= md5($type) ?>.className = 'list-group-item list-group-item-danger';
        li<?= md5($type) ?>.innerText = `Low stock alert: <?= htmlspecialchars($type) ?>`;
        lowStockAlerts.appendChild(li<?= md5($type) ?>);
    <?php endforeach; ?>

    const lightModeURL = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';

    function toggleDarkMode() {
      const themeLink = document.getElementById('theme-link');
      if (themeLink.getAttribute('href') === lightModeURL) {
        // Switch to dark mode theme
        themeLink.setAttribute('href', 'https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/darkly/bootstrap.min.css');
      } else {
        // Switch back to light mode theme
        themeLink.setAttribute('href', lightModeURL);
      }
    }
  </script>
</body>
</html>
