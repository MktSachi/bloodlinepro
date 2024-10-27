<?php include 'RegistartionProcess.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 CSS  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../Assets/css/header.css">
  <link rel="stylesheet" href="../Assets/css/footer.css">
  <title>Blood Bank Management System</title>
  <style>
    
    .loader {
        position: fixed;
        z-index: 9999;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        display: none;
    }

    .loader img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: white;
        color: #333;
    }
    .container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-top: 50px;
        margin-bottom: 50px;
    }
    h4 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .form-label {
        font-weight: 500;
        color: #34495e;
    }
    .form-control, .form-select {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        padding: 10px 20px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-primary:hover, .btn-primary:focus {
        background-color: #2980b9;
        border-color: #2980b9;
    }
    .form-check-input:checked {
        background-color: #3498db;
        border-color: #3498db;
    }
    .input-group-text {
        background-color: #ecf0f1;
        border: 1px solid #ced4da;
        color: #7f8c8d;
    }
  </style>
</head>
<body class="p-0 m-0 border-0 bd-example">
<div class="loader" id="loader">
    <img src="../../AdminDashboard/Animation - 1720851760552.gif" alt="Loading...">
</div>
<main role="main" class="container">
  <div class="row">
    <div class="col-md-6 mb-3"></div>
    <div class="col-md-10 blog-main">
      <h4 class="mb-3">Donor Registration</h4>
      
      <?php if (!empty($error_msg)) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_msg; ?>
        </div>
      <?php } ?>
      
      <form class="needs-validation" novalidate method="POST" action="" enctype="multipart/form-data" name="Donor_Creation">
        <div class="row g-4">
          <div class="col-md-6 mb-3">
            <label for="firstName" class="form-label">First name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="lastName" class="form-label">Last name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="donorNIC" class="form-label">NIC Num</label>
          <input type="text" class="form-control" id="donorNIC" name="donorNIC" required>
          <div class="invalid-feedback">
            Please enter NIC Number.
          </div>
        </div>

        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <div class="input-group">
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback" style="width: 50%;">
              Your username is required.
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email">
          <div class="invalid-feedback">
            Please enter a valid email.
          </div>
        </div>

        <div class="mb-3">
          <label for="phoneNumber" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" required>
          <div class="invalid-feedback">
            Please enter a valid 10-digit phone number.
          </div>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <input type="text" class="form-control" id="address" name="address" required>
          <div class="invalid-feedback">
            Please enter your address.
          </div>
        </div>

        <div class="mb-3">
          <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
          <input type="text" class="form-control" id="address2" name="address2">
        </div>

        <div class="mb-3">
          <label for="gender" class="form-label">Gender</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" checked>
            <label class="form-check-label" for="genderMale">Male</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female">
            <label class="form-check-label" for="genderFemale">Female</label>
          </div>
        </div>

        <div class="form-group mb-3">
          <label for="bloodType" class="form-label">Blood Type</label>
          <select id="bloodType" name="bloodType" class="form-select" style="width:200px;" required>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="otherHealthConditions" class="form-label">Other Health Conditions</label>
          <input type="text" class="form-control" id="otherHealthConditions" name="otherHealthConditions" placeholder="Any other health conditions (if applicable)">
        </div>

        <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Register</button>
      </form>
    </div>
  </div>
 
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form[name="Donor_Creation"]');
    var loader = document.getElementById('loader');
    form.addEventListener('submit', function() {
        loader.style.display = 'block';
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
