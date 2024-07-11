<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      transition: transform 0.2s;
      cursor: pointer;
    }
    .card:hover {
      transform: scale(1.05);
    }
    .card-title {
    font-weight: 700;
      color: darkred;
    }
    .card-text {
      color: darkred;
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }
    .card-body {
      flex: 1 1 auto;
    }
    .card-body > p {
      margin-bottom: 0;
    }
    .card-group .card {
      height: 100%;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="row row-cols-1 row-cols-md-3">
      <div class="col mb-4">
        <div class="card shadow h-100"> 
          <img src="../Image/Blood-Donation-2.jpg" class="card-img-top" alt="Image1">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">About Blood</h5>
            <p class="card-text">
              The ABO blood group system was discovered by Karl Landsteiner in 1900. 46 years later (1946) the Blood Transfusion Service was formed. In 1996, the National Blood Service was formed to collect and provide blood supplies for all the hospitals in Sri Lanka.
            </p>
          </div>
        </div>
      </div>
      <div class="col mb-4">
        <div class="card shadow h-100"> 
          <img src="../Image/BloodTopics_share.jpg" class="card-img-top" alt="Image2">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Components of Blood</h5>
            <p class="card-text">
              When we receive your donation, we separate it into individual components by spinning it in a machine called a centrifuge. The individual components are red cells, white cells, platelets, and plasma. These can all be put to different uses.
            </p>
          </div>
        </div>
      </div>
      <div class="col mb-4">
        <div class="card shadow h-100"> 
          <img src="../Image/w1600-removebg-preview.png" class="card-img-top" alt="Image3">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">How does the Body Replace Blood</h5>
            <p class="card-text">
              During a whole blood donation, we aim to take just under a pint (about 470mls) of blood, which works out at no more than 13 percent of your blood volume. After donation, your body has an amazing capacity to replace all the cells and fluids that have been lost.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
