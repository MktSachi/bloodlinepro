<?php include 'RegistartionProcess.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 CSS  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../Assets/css/header.css">
  <link rel="stylesheet" href="../Assets/css/footer.css">
  <title>Blood Bank Management System</title>
</head>
<body class="p-0 m-0 border-0 bd-example">

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
      
      <form class="needs-validation" novalidate method="POST" action="" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="firstName">First name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="lastName">Last name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="donorNIC">NIC Num</label>
          <input type="text" class="form-control" id="donorNIC" name="donorNIC" placeholder="National Identity Card Number" required>
          <div class="invalid-feedback">
            Please enter NIC Number.
          </div>
        </div>

        <div class="mb-3">
          <label for="username">Username</label>
          <div class="input-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            <div class="invalid-feedback" style="width: 50%;">
              Your username is required.
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="you@gmail.com">
          <div class="invalid-feedback">
            Please enter a valid email.
          </div>
        </div>

      <!--  <div class="mb-3">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          <div class="invalid-feedback">
            Please enter a valid password.
          </div>
        </div>

        <div class="mb-3">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
          <div class="invalid-feedback">
            Please confirm your password.
          </div>
        </div> -->

        <div class="mb-3">
          <label for="phoneNumber">Phone Number</label>
          <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number" pattern="[0-9]{10}" required>
          <div class="invalid-feedback">
            Please enter a valid 10-digit phone number.
          </div>
        </div>

        <div class="mb-3">
          <label for="address">Address</label>
          <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
          <div class="invalid-feedback">
            Please enter your address.
          </div>
        </div>

        <div class="mb-3">
          <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
          <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment or suite">
        </div>

        <div class="mb-3">
          <label for="gender">Gender</label>
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
          <label for="bloodType">Blood Type:</label>
          <select id="bloodType" name="bloodType" style="width:200px;" required>
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
          <label for="otherHealthConditions">Other Health Conditions</label>
          <input type="text" class="form-control" id="otherHealthConditions" name="otherHealthConditions" placeholder="Any other health conditions (if applicable)">
        </div>

        <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Register</button>
      </form>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
