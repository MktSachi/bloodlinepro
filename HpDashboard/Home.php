<?php
require '../DonorRegistration/Database.php';

$db = new Database();
$conn = $db->getConnection();


$totalunits = 0;
$queryTotalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory";
$resultTotalBlood = $conn->query($queryTotalBlood);
if ($resultTotalBlood->num_rows > 0) {
    $row = $resultTotalBlood->fetch_assoc();
    $totalunits = $row['total'];
}
$resultTotalBlood->free();



$queryDonorStats = "SELECT 
    COUNT(*) as total_donors,
    AVG(donation_count) as avg_donations,
    SUM(CASE WHEN bloodType IN ('A+', 'A-') THEN 1 ELSE 0 END) as type_a_count,
    SUM(CASE WHEN bloodType IN ('B+', 'B-') THEN 1 ELSE 0 END) as type_b_count,
    SUM(CASE WHEN bloodType IN ('AB+', 'AB-') THEN 1 ELSE 0 END) as type_ab_count,
    SUM(CASE WHEN bloodType IN ('O+', 'O-') THEN 1 ELSE 0 END) as type_o_count
FROM donors";
$resultDonorStats = $conn->query($queryDonorStats);
$donorStats = $resultDonorStats->fetch_assoc();


$queryLastDonation = "SELECT MAX(donationDate) as last_donation FROM donations";
$resultLastDonation = $conn->query($queryLastDonation);
$lastDonation = $resultLastDonation->fetch_assoc();

$hospitals = [];
$queryHospitals = "SELECT hospitalName, SUM(quantity) AS totalBlood FROM hospital_blood_inventory hbi JOIN hospitals h ON hbi.hospitalID = h.hospitalID GROUP BY hospitalName";
$resultHospitals = $conn->query($queryHospitals);
if ($resultHospitals->num_rows > 0) {
    while ($row = $resultHospitals->fetch_assoc()) {
        $hospitals[$row['hospitalName']] = $row['totalBlood'];
    }
}
$resultHospitals->free();


$bloodTypeData = [];
$queryBloodType = "SELECT bloodType, SUM(quantity) AS total FROM hospital_blood_inventory GROUP BY bloodType";
$resultBloodType = $conn->query($queryBloodType);
if ($resultBloodType->num_rows > 0) {
    while ($row = $resultBloodType->fetch_assoc()) {
        $bloodTypeData[$row['bloodType']] = $row['total'];
    }
}
$resultBloodType->free();

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
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    body {
      background-color: #f0f2f5;
      font-family: 'Roboto', sans-serif;
    }
    .dashboard-container {
      padding: 30px;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: #34495e;
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
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: #34495e;
    }
    .small-chart {
      max-width: 100%;
      margin: 0 auto;
    }
    .blue-dot {
      color: #3498db;
      font-size: 20px;
      margin-right: 10px;
    }
    .organ-icon {
      width: 70px;
      height: 70px;
      background-color: #ecf0f1;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: #2c3e50;
      transition: all 0.3s ease;
    }
    .organ-icon:hover {
      background-color: #3498db;
      color: white;
    }
 
    .small-chart {
      max-width: 100%;
      margin: 0 auto;
    }
    .icon-circle {
      width: 50px;
      height: 50px;
      background-color: #e74c3c;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      margin-bottom: 15px;
    }
    .stat-card {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }
    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #2c3e50;
    }
    .stat-label {
      font-size: 0.9rem;
      color: #7f8c8d;
      margin-top: 5px;
    }

    .bg-light-red {
        background-color: #f8d7da; /* Light Red */
    }
    .text-dark {
        color: #343a40; /* Dark text */
    }

    @media (max-width: 768px) {
  .dashboard-container {
    padding: 15px;
  }

  .row {
    margin-left: -10px;
    margin-right: -10px;
  }

  .col-md-3 {
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 20px;
  }

  .stat-card {
    padding: 15px;
  }

  .stat-value {
    font-size: 1.5rem;
  }

  .stat-label {
    font-size: 0.8rem;
  }

  .icon-circle {
    width: 40px;
    height: 40px;
    font-size: 18px;
  }
}

@media (max-width: 576px) {
  .col-md-3 {
    width: 50%;
  }
}

@media (max-width: 400px) {
  .col-md-3 {
    width: 100%;
  }
}


  </style>
</head>
<body>
  <?php include 'HpSidebar.php'; ?>
  <div class="w3-main" style="margin-left:200px; margin-top:0;"> <!-- Updated margin-top to 0 -->
  <div class="dashboard-container">
      <h3 class="text-center mb-4">Blood Inventory Dashboard</h3>
      <div class="row mb-4">
                
      <div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-light-red text-dark">
            <div class="card-body">
                <h5 class="card-title">Total Donors</h5>
                <h2 class="card-text"><?php echo number_format($donorStats['total_donors']); ?></h2>
                <p class="card-text text-success"><i class="fas fa-arrow-up me-2"></i>5% increase</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-light-red text-dark">
            <div class="card-body">
                <h5 class="card-title">Blood Units Available</h5>
                <h2 class="card-text"><?php echo number_format($totalunits); ?></h2>
                <p class="card-text text-danger"><i class="fas fa-arrow-down me-2"></i>2% decrease</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-light-red text-dark">
            <div class="card-body">
                <h5 class="card-title">Avg Donations</h5>
                <h2 class="card-text"><?php echo number_format($donorStats['avg_donations'], 1); ?></h2>
                <p class="card-text text-success"><i class="fas fa-arrow-up me-2"></i>3% increase</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-light-red text-dark">
            <div class="card-body">
                <h5 class="card-title">Last Donation Date</h5>
                <h2 class="card-text"><?php echo date('M d, Y', strtotime($lastDonation['last_donation'])); ?></h2>
                <p class="card-text text-success"><i class="fas fa-check-circle me-2"></i>Recent</p>
            </div>
        </div>
    </div>
</div>
                
      

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-hospital-alt blue-dot"></i>Hospital Blood Count</h5>
              <canvas id="hospitalBloodCountChart" class="small-chart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-tint blue-dot"></i>Blood Type Distribution</h5>
              <canvas id="bloodTypeChart"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-md-4 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <h5 class="card-title"><i class="fas fa-heartbeat blue-dot"></i>Donor Statistics</h5>
      <div class="row">
        <div class="col-6 mb-3">
          <small class="text-muted">Total Donors</small>
          <h4 class="mb-0"><?= number_format($donorStats['total_donors']) ?></h4>
        </div>
        <div class="col-6 mb-3">
          <small class="text-muted">Avg Donations/Donor</small>
          <h4 class="mb-0"><?= number_format($donorStats['avg_donations'], 1) ?></h4>
        </div>
        <div class="col-6 mb-3">
          <small class="text-muted">Type A Donors</small>
          <h4 class="mb-0"><?= number_format($donorStats['type_a_count']) ?></h4>
        </div>
        <div class="col-6 mb-3">
          <small class="text-muted">Type B Donors</small>
          <h4 class="mb-0"><?= number_format($donorStats['type_b_count']) ?></h4>
        </div>
        <div class="col-6 mb-3">
          <small class="text-muted">Type AB Donors</small>
          <h4 class="mb-0"><?= number_format($donorStats['type_ab_count']) ?></h4>
        </div>
        <div class="col-6 mb-3">
          <small class="text-muted">Type O Donors</small>
          <h4 class="mb-0"><?= number_format($donorStats['type_o_count']) ?></h4>
        </div>
        <div class="col-12 mb-3">
          <small class="text-muted">Last Donation</small>
          <h4 class="mb-0"><?= date('M d, Y', strtotime($lastDonation['last_donation'])) ?></h4>
        </div>
      </div>
    </div>
  </div>


      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-user-md blue-dot"></i>Body Condition Overview</h5>
              <div class="d-flex justify-content-around">
                <div class="organ-icon"><i class="fas fa-heart"></i></div>
                <div class="organ-icon"><i class="fas fa-brain"></i></div>
                <div class="organ-icon"><i class="fas fa-lungs"></i></div>
                <div class="organ-icon"><i class="fas fa-kidney"></i></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
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
              backgroundColor: 'rgba(52, 152, 219, 0.8)',
              borderColor: 'rgba(52, 152, 219, 1)',
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
                  family: "'Roboto', sans-serif",
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
                    family: "'Roboto', sans-serif",
                    size: 12
                  }
                }
              },
              title: {
                display: true,
                text: 'Blood Count by Hospital',
                font: {
                  family: "'Roboto', sans-serif",
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