<!DOCTYPE html>
<html>
<head>
<title>Health-Care Professional</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  .w3-quarter a {
    text-decoration: none;
    color: inherit;
  }

  .w3-container {
    border-radius: 10px; /* Adjust the value as needed */
  }
</style>
</head>
<body class="w3-light-grey">

  <!--sidebar-->
  <?php include './HP_sidebar.php'; ?>

  <!-- !PAGE CONTENT! -->
  <div class="w3-main" style="margin-left:250px;margin-top:43px;">

    <!-- Header -->
    <header class="w3-container" style="padding-top:22px">
      <h5><b> Dashboard</b></h5>
    </header>

    <div class="w3-row-padding w3-margin-bottom">

      <!--Donor Account Notification-->
      <div class="w3-quarter">
        <a href="DonorAccountHandle.php">
          <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
            <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
            <div class="w3-right">
              <!-- number-->
            </div>
            <div class="w3-clear"></div>
            <h4>Donor Account</h4>
          </div>
        </a>
      </div>

      <!--Blood Request Notification-->
      <div class="w3-quarter">
        <a href="Request.php">
          <div class="w3-container  w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
            <div class="w3-left"><i class="fa fa-eye w3-xxxlarge"></i></div>
            <div class="w3-right">
              <!-- number-->
            </div>
            <div class="w3-clear"></div>
            <h4>Blood Request</h4>
          </div>
        </a>
      </div>

      <!--Inventory Notification-->
      <div class="w3-quarter">
        <a href="CRUD.php">
          <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
            <div class="w3-left"><i class="fa fa-database w3-xxxlarge"></i></div>
            <div class="w3-right">
              <!-- number-->
            </div>
            <div class="w3-clear"></div>
            <h4>Inventory</h4>
          </div>
        </a>
      </div>

      <!--Alerts Notification-->
      <div class="w3-quarter">
        <a href="CRUD.php">
          <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
            <div class="w3-left"><i class="fa fa-cog w3-xxxlarge"></i></div>
            <div class="w3-right">
              <!-- number-->
            </div>
            <div class="w3-clear"></div>
            <h4>Blood Availability</h4>
          </div>
        </a>
      </div>     

    </div>

    <div class="w3-row-padding w3-margin-bottom">

      <!--Blood Availability Notification-->
      <div class="w3-quarter">
        <a href="###">
          <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
            <div class="w3-left"><i class="fa fa-map-marker w3-xxxlarge"></i></div>
            <div class="w3-right">
              <!-- number-->
            </div>
            <div class="w3-clear"></div>
            <h4>Donation Camp</h4>
          </div>
        </a>
      </div>

      <!--Donation camps-->
      <div class="w3-quarter">
        <a href="">
          <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
            <div class="w3-left"><i class="fa fa-bell w3-xxxlarge"></i></div>
            <div class="w3-right">
              <!-- number-->
            </div>
            <div class="w3-clear"></div>
            <h4>Notify Donor</h4>
          </div>
        </a>
      </div>

      <div class="w3-quarter">
        <a href="">
          <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
            <div class="w3-left"><i class="fa fa-diamond w3-xxxlarge"></i></div>
            <div class="w3-right">
              <!-- number-->
            </div>
            <div class="w3-clear"></div>
            <h4>Warning</h4>
          </div>
        </a>
      </div>
      
      

    </div>
  </div> 
</body>
</html>
