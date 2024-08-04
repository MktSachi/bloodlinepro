<?php include 'RegistartionProcess.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Assets/css/header.css">
  <title>Blood Bank Management System - Donor Registration</title>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
    }
    .container {
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      padding: 30px;
      margin-top: 50px;
    }
    h4, h5 {
      color: #dc3545;
    }
    .form-label {
      font-weight: 600;
    }
    .btn-primary {
      background-color: #dc3545;
      border-color: #dc3545;
    }
    .btn-primary:hover {
      background-color: #c82333;
      border-color: #bd2130;
    }
    hr {
      border-top: 2px solid #dc3545;
    }
    .form-control:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
  </style>
</head>
<body>
<?php include '../part_of_home_page/home/Header.php'; ?>

<main role="main" class="container">
  <h4 class="mb-4 text-center">Donor Registration</h4>
  
  <?php if (!empty($error_msg)) { ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $error_msg; ?>
    </div>
  <?php } ?>

  <form class="needs-validation" novalidate method="POST" action="" enctype="multipart/form-data">
    <section>
      <h5>Part 1: Personal Information</h5>
      <hr>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="firstName" class="form-label">First name</label>
          <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="lastName" class="form-label">Last name</label>
          <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="donorNIC" class="form-label">NIC Number</label>
        <input type="text" class="form-control" id="donorNIC" name="donorNIC" required>
      </div>

      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <div class="input-group">
          <span class="input-group-text">@</span>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Gender</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" checked>
          <label class="form-check-label" for="genderMale">Male</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female">
          <label class="form-check-label" for="genderFemale">Female</label>
        </div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="mb-3">
        <label for="phoneNumber" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" required>
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" required>
      </div>

      <div class="mb-3">
        <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
        <input type="text" class="form-control" id="address2" name="address2">
      </div>
    </section>

    <section class="mt-4">
      <h5>Part 2: Password</h5>
      <hr>
      <p class="fw-bold">Password must contain at least 8 characters with one uppercase letter, one lowercase letter, one symbol, and one number</p>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
      </div>
    </section>

    <section class="mt-4">
      <h5>Part 3: Health Conditions</h5>
      <hr>
      <div class="mb-3">
        <label for="bloodType" class="form-label">Blood Type:</label>
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
        <label class="form-label">Health Conditions</label>
        <?php
        $conditions = ['HIV', 'Heart Disease', 'Diabetes', 'Fits', 'Paralysis', 'Lung Diseases', 'Liver Diseases', 'Kidney Diseases', 'Blood Diseases', 'Cancer'];
        foreach ($conditions as $condition) {
          $id = strtolower(str_replace(' ', '_', $condition));
          echo "<div class='form-check'>
                  <input class='form-check-input' type='checkbox' id='$id' name='$id' value='1'>
                  <label class='form-check-label' for='$id'>$condition</label>
                </div>";
        }
        ?>
        <div class="form-group mt-2">
          <label for="otherHealthConditions" class="form-label">Other Health Conditions:</label>
          <textarea id="otherHealthConditions" name="otherHealthConditions" class="form-control"></textarea>
        </div>
      </div>
    </section>

    <section class="mt-4">
      <h5>Part 4: Upload Profile Picture</h5>
      <hr>
      <div class="mb-3">
        <label for="profile_picture" class="form-label">Upload Profile Picture</label>
        <input type="file" class="form-control" id="profile_picture" name="profile_picture">
      </div>
    </section>

    <hr class="mb-4">
    <button class="btn btn-primary btn-lg w-100" type="submit" name="submit" id="submit">Register</button>
    <p class="mt-3 text-center">Already have an account? <a href="../login_window/login.php">Login here</a></p>
  </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>