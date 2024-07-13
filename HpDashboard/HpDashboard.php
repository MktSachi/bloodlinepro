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
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 0; /* Remove default padding */
  }
  .w3-quarter a {
    text-decoration: none;
    color: inherit;
  }
  .w3-container {
    border-radius: 10px;
    background-color: white;
    color: #333; /* Darker text color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    height: 100px; /* Adjust the height as needed */
  }
  .w3-container:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  }
  .w3-container h4 {
    margin: 0; /* Remove default margin for h4 */
  }
  .w3-container .button-text {
    text-align: left; /* Align text to the left */
  }
  .w3-container .icon {
    text-align: right; /* Align icon to the right */
  }

  /* Custom colors for Font Awesome icons */
  .fa-sky-blue {
    color: #87CEEB; /* Sky blue */
  }
  .fa-dark-blue {
    color: #00008b; /* Dark blue */
  }
  .fa-green {
    color: #008000; /* Green */
  }
  .fa-gray {
    color: #808080; /* Gray */
  }
  .fa-dark-pink {
    color: #8b0000; /* Dark pink */
  }
  .fa-orange {
    color: #FFA500; /* Orange */
  }

  /* Styling for Dashboard title */
  p.dashboard-title {
    margin-top: 10px;
    color: black; /* Black text color */
    padding: 10px 20px; /* Padding around the title */
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    font-family: Arial, sans-serif;
    font-size: 23px;
  }

  .w3-main {
    margin-left: 200px;
    margin-top: 43px;
  }

  /* Custom margins for buttons */
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
      text-align: left; /* Align text and icon to the left on small screens */
    }
  }
</style>
</head>
<body class="w3-light-grey">




  <!-- Sidebar -->
  <?php include 'HpSidebar.php'; ?>


  <!-- !PAGE CONTENT! -->
  <div class="w3-main">

    <!-- Header -->
    <p class="dashboard-title">HealthCare Professional Dashboard</p>

    <div class="w3-row-padding w3-margin-bottom">

      <!-- Donor Account Notification -->
      <div class="w3-quarter gap-1">
        <a href="DonorAccountHandle.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Donor Account</h4>
              <p>15</p>
            </div>
            <div class="icon"><i class="fa fa-users fa-sky-blue" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>

      <!-- Blood Request Notification -->
      <div class="w3-quarter gap-2">
        <a href="RequestHandle.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Blood Request</h4>
              <p>14</p>
            </div>
            <div class="icon"><i class="fa fa-eye fa-dark-pink" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>

      <!-- Inventory Notification -->
      <div class="w3-quarter gap-3">
        <a href="HospitalInevntoryHandle.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Inventory</h4>
              <p>20</p>
            </div>
            <div class="icon"><i class="fa fa-database fa-dark-blue" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>

      <!-- Alerts Notification -->
      <div class="w3-quarter gap-4">
        <a href="Warning.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Warning</h4>
              <p>15</p>
            </div>
            <div class="icon"><i class="fa fa-bell fa-yellow" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>

      <!-- Blood Availability Notification -->
      <div class="w3-quarter gap-1">
        <a href="WholeBloodInv.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Blood Availability</h4>
              <p>5</p>
            </div>
            <div class="icon"><i class="fa fa-cog fa-green" style="font-size: 25px;"></i></div>
          </div>
        </a>
</div>

      <!-- Donation Camps -->
      <div class="w3-quarter gap-2">
        <a href="DonationCamp.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Donation Camp</h4>
              <p>10</p>
            </div>
            <div class="icon"><i class="fa fa-map-marker fa-orange" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>   

      <!-- Notify -->
      <div class="w3-quarter gap-2">
        <a href="SendBlood.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Notify</h4>
              <p>10</p>
            </div>
            <div class="icon"><i class="fa fa-envelope fa-gray" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div>  
       <!-- Usage-->
       <div class="w3-quarter gap-2">
        <a href="BloodUsage.php">
          <div class="w3-container">
            <div class="button-text">
              <h4>Blood Usage</h4>
              <p>10</p>
            </div>
            <div class="icon"><i class="fa fa-envelope fa-gray" style="font-size: 25px;"></i></div>
          </div>
        </a>
      </div> 
      
      <!-- WholInv-->
      
    </div>
  </div>

  <!-- Footer -->

</body>
</html>
