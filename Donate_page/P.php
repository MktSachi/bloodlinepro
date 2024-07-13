<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 550px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
        
    /* On small screens, set height to 'auto' for the grid */
    @media screen and (max-width: 767px) {
      .row.content {height: auto;} 
    }
  </style>
</head>
<body>

<div>
<h2></h2></br>
      <div class="w3-justify">
        
</div>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav hidden-xs">
      <ul class="nav nav-pills nav-stacked">
      <h4>Who can donate blood?</h4>
        <p><ul>
          <li>Age above 18 years and below 60 years.</li></br>
          <li>If previously donated, at least 4 months should be elapsed since the date of previous donation.</li></br>
          <li>Hemoglobin level should be more than 12g/dL. (this blood test is done prior to each blood donation)</li></br>
          <li>Free from any serious disease condition or pregnancy.</li></br>
          <li>Should have a valid identity card or any other document to prove the identity.</li></br>
          <li>Free from "Risk Behaviours".</li></br>
        </ul></p>
      </ul><br>
    </div>
    <br>

  <!--mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm -->
    
    <div class="col-sm-9">
      <div class="well">
      <h2>Types Of Blood Donations</h2></br>
        <ol>
            <li>Whole Blood Donation:</li></br>
                <p><b>What:</b>The most common type of blood donation where approximately one pint of whole blood is collected.</p>
                <p><b>Uses:</b>Used for transfusions and can be separated into red cells, plasma, and platelets.</p>
                <p><b>Frequency:</b>Every 56 days.</p></br>
        
            <li>Platelet Donation (Apheresis):</li></br>
                <p><b>What:</b>Blood is drawn, platelets are separated, and the remaining blood components are returned to the donor.</p>
                <p><b>Uses:</b>Crucial for cancer patients, organ transplant recipients, and those undergoing major surgeries.</p>
                <p><b>Frequency:</b>Every 7 days, up to 24 times a year.</p></br>
        
            <li>Plasma Donation:</li></br>
                <p><b>What:</b>Blood is drawn, plasma is separated, and the rest is returned to the donor.</p>
                <p><b>Uses:</b>Treats patients with clotting disorders, burns, and shock.</p>
                <p><b>Frequency:</b>Every 28 days, up to 13 times a year.</p></br>
        
            <li>Double Red Cell Donation:</li></br>
                <p><b>What:</b>Two units of red blood cells are collected while returning plasma and platelets to the donor.</p>
                <p><b>Uses:</b>Beneficial for trauma patients, newborns, and those with sickle cell anemia.</p>
                <p><b>Frequency:</b>Every 112 days, up to 3 times a year.</p>    
          </ol>

      </div>


      <div class="row">
        <div class="col-sm-4">
          <div class="well">
          <h2>Risk Behaviours</h2>
        <ul>
            <li>Engaging in sex with any of the above.</li></br>
            <li>Having more than one sexual partner</li></br>
            <li>Sex workers and their clients.</li></br>
            <li>Homosexuals.</li></br>
            <li>Drug addicts.</li></br>
        </ul>
          </div>
        </div>


        <div class="col-sm-4">
          <div class="well">
          <h2>Type Of Donors</h2>
        <ul>
            <li>Voluntary non remunerated donors. (donate for the sake of others and 
              do not expect any benefit. their blood is considered safe and healthy)</li></br>
            <li>Replacement donors. (donate to replace the units used for their friends or family members)</li></br>
            <li>Paid donors. (receive payment for donation)</li></br>
            <li>Directed donors. (donate only for a specific patient's requirement)</li></br>
        </ul>
          </div>
        </div>


        <div class="col-sm-4">
          <div class="well">
          <h2>The Donation Process</h2>
        <ul>
            <li>Registration:</li>
              <p>You will fill out a donor registration form with basic information.</p>
            <li>Health Screening:</li>
              <p>A brief health history and mini-physical (checking temperature, pulse, blood pressure, and hemoglobin level).</p>
            <li>Donation:</li>
              <p>The actual blood draw typically takes about 10 minutes for whole blood donation. Platelet and plasma donations take longer.</p>
            <li>Recovery:</li>
              <p>After donation, you will rest and have refreshments. It’s important to 
                 drink plenty of fluids and avoid strenuous activities for the rest of the day</p>
        </ul>
          </div>
        </div>

      </div>




      <div class="row">
        <div class="col-sm-8">
          <div class="well">
            <p>Text</p> 
          </div>
        </div>
        <div class="col-sm-4">
          <div class="well">
            <p>Text</p> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
