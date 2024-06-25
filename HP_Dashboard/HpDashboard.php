<!DOCTYPE html>
<html>
<head>
<title>Health-Care Professional</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
        <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
          <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
          <div class="w3-right">
            <!-- number-->
          
          </div>
          <div class="w3-clear"></div>
          <a href="DonorAccountHandle.php"style="text-decoration:none;"><h4>Donor Account</h4></a>
        </div>
      </div>

      <!--Donor Account Notification-->
      <div class="w3-quarter">
        <div class="w3-container  w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
          <div class="w3-left"><i class="fa fa-eye w3-xxxlarge"></i></div>
          <div class="w3-right">
            <!-- number-->
            
          </div>
          <div class="w3-clear"></div>
          <a href="Request.php"style="text-decoration:none;"><h4>Blood Request</h4></a>
        </div>
      </div>

      <!--Donor Account Notification-->
      <div class="w3-quarter">
        <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
          <div class="w3-left"><i class="fa fa-bullseye w3-xxxlarge"></i></div>
          <div class="w3-right">
            <!-- number-->
           
          </div>
          <div class="w3-clear"></div>
          <a href="CRUD.php"style="text-decoration:none;"><h4>Inventory</h4></a>
        </div>
      </div>

      <!--Donor Account Notification-->
      <div class="w3-quarter">
        <div class="w3-container w3-text-white w3-padding-16" style="background-color: rgb(131, 26, 26);">
          <div class="w3-left"><i class="fa fa-bell w3-xxxlarge"></i></div>
          <div class="w3-right">
            <!-- number-->
          
          </div>
          <div class="w3-clear"></div>
          <a href="CRUD.php"style="text-decoration:none;"><h4>Alerts</h4></a>
        </div>
      </div>     

    </div>
    
  <!--Map-->
  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-third">
        <h5>Blood Availability</h5>
        <img src="" style="width:100%" alt="blood availability">
      </div>

      
    </div>
  </div>
</body>
</html>
