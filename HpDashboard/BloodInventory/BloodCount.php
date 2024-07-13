<?php
session_start();
require '../../DonorRegistration/Database.php';
require 'Inventory.php';

$db = new Database();
$conn = $db->getConnection();

$inventory = new Inventory($conn);

$username = $_SESSION['username'] ?? '';
if (!empty($username)) {
    $result = $inventory->getBloodInventory($username);
    $bloodInventory = $result['inventory'];
    $totalUnits = $result['totalUnits'];
}

$db->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Blood Inventory - BloodLinePro</title>
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
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Hospital Blood Inventory</h5>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Blood Type</th>
                  <th>Quantity (ml)</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($bloodInventory as $bloodType => $quantity): ?>
                  <tr>
                    <td><?= htmlspecialchars($bloodType) ?></td>
                    <td><?= htmlspecialchars($quantity) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Total Units</th>
                  <td><?= htmlspecialchars($totalUnits) ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart.js Bar Chart -->
  <div class="card small-chart">
    <div class="card-body">
      <h5 class="card-title">Blood Inventory Chart</h5>
      <canvas id="bloodInventoryChart"></canvas>
    </div>
  </div>

  <div style="position: fixed; bottom: 20px; right: 20px;">
    <button class="btn btn-secondary" onclick="toggleDarkMode()">Toggle Dark Mode</button>
  </div>

  <script>
    const bloodTypes = <?= json_encode(array_keys($bloodInventory)) ?>;
    const quantities = <?= json_encode(array_values($bloodInventory)) ?>;

    // Chart.js Bar Chart
    var ctx = document.getElementById('bloodInventoryChart').getContext('2d');
    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: bloodTypes,
        datasets: [{
          label: 'Blood Quantity (ml)',
          data: quantities,
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
