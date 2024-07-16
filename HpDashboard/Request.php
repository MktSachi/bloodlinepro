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

  <style>
    .btn-block {
      background-color: #1E7CE7; /* Light blue color */
      color: #fff; /* White text color */
      border-color: #1E7CE7; /* Matching border color */
    }
  </style>
</head>

<body class="p-0 m-0 border-0 bd-example">

<main role="main" class="container">
  <div class="row">
    <div class="col-md-6 mb-3"></div>
    <div class="col-md-10 blog-main">
      <h4 class="mb-3">Blood Request Form</h4>
      
      <?php if (!empty($error_msg)) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_msg; ?>
        </div>
      <?php } ?>
      
      <form action="ProcessForm.php" method="POST">
        <div class="mb-3">
          <label for="hospital">Hospital</label>
          <select id="hospital" name="hospital" class="form-control" required>
            <option value="">-Select Hospital-</option>
            <option value="Teaching hospital Badulla">Teaching hospital Badulla</option>
            <option value="Monaragala Hospital">Monaragala Hospital</option>
            <option value="Welimada Hospital">Welimada Hospital</option>
            <option value="Diyathalawa Hospital">Diyathalawa Hospital</option>
            <option value="Mahiyanganaya Hospital">Mahiyanganaya Hospital</option>
            <option value="Bibila Hospital">Bibila Hospital</option>
            <option value="Wellawaya Hospital">Wellawaya Hospital</option>
          </select>
        </div>

        

        <div class="mb-3">
          <label for="blood">Blood Group</label>
          <select id="blood" name="blood" class="form-control" required>
            <option value="">-Select Blood Group-</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="quantity">Blood quantity (paints)</label>
          <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Required blood quantity" required>
        </div>

        <div class="mb-3">
    <label for="patientname">Patient Name</label>
    <input type="text" class="form-control" id="patientname" name="patientname" placeholder="Enter patient name" required>
</div>

        <div class="mb-3">
          <label for="description">Description</label>
          <input type="text" class="form-control" id="description" name="description" placeholder="Enter the reason" required>
        </div>

        <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Send Request</button>
      </form>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>