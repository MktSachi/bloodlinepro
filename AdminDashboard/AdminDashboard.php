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
<html>
<head>
<title>Health-Care Professional</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4; 
    margin: 0; 
    padding: 0; 
  }
  .w3-quarter a {
    text-decoration: none;
    color: inherit;
  }
  .w3-container {
    border-radius: 10px;
    background-color: white;
    color: #333; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    height: 100px; 
  }
  .w3-container:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  }
  .w3-container h4 {
    margin: 0; 
  }
  .w3-container .button-text {
    text-align: left; 
  }
  .w3-container .icon {
    text-align: right; 
  }

  
  .fa-sky-blue {
    color: #87CEEB; 
  }
  .fa-dark-blue {
    color: #00008b;
  }
  .fa-green {
    color: #008000; 
  }
  .fa-gray {
    color: #808080; 
  }
  .fa-dark-pink {
    color: #8b0000; 
  }
  .fa-orange {
    color: #FFA500; 
  }

  
  p.dashboard-title {
    margin-top: 10px;
    color: black; 
    padding: 10px 20px; 
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    font-family: Arial, sans-serif;
    font-size: 23px;
  }

  .w3-main {
    margin-left: 200px;
    margin-top: 43px;
  }

  
  .gap-1 {
    margin-bottom: 10px;
  }
  .gap-2 {
    margin-bottom: 20px;
  }
  .gap-3 {
    margin-bottom: 30px;
  }
  .gap-4 {
    margin-bottom: 40px;
  }

  @media (max-width: 768px) {
    .w3-container {
      flex-direction: column;
      align-items: flex-start;
    }
    .button-text, .icon {
      text-align: left; 
    }
  }
</style>
</head>
<body class="w3-light-grey">




  <!-- Sidebar -->
  <?php include 'sidebar.php'; ?>


  <!-- !PAGE CONTENT! -->
  <div class="w3-main">

    <!-- Header -->
    <p class="dashboard-title">Admin Dashboard</p>

    <div class="w3-row-padding w3-margin-bottom">

      <!-- Admin Account Notification -->
      <div class="w3-quarter gap-1">
        <a href="HpAccountHandle.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>HP Account</h4>
              <p>15</p>
            </div>
            <div class="icon"><i class="fa fa-users fa-sky-blue" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>

      

     
    

      <!-- Notify -->
      <div class="w3-quarter gap-2">
        <a href="Hospital.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Hospital</h4>
              <p>10</p>
            </div>
            <div class="icon"><i class="fa fa-envelope fa-gray" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>  
       <!-- Usage-->
       
    </div>
  </div>

  <!-- Footer -->

</body>
</html>
