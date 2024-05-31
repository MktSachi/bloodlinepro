<?php
$error_msg = "";

$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "bloodlinepro";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function validatePassword($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password);
    }

    $password = $_POST['password'];
    if (!validatePassword($password)) {
        $error_msg .= "Password must contain at least one uppercase letter, one lowercase letter, one symbol, and one number. ";
    }

    $username = $_POST['username'];
    $check_username_sql = "SELECT * FROM donors WHERE username = ?";
    $stmt = $conn->prepare($check_username_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_msg .= "Username '$username' already exists. Please choose a different username. ";
    }

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_size = $_FILES['profile_picture']['size'];
        $file_error = $_FILES['profile_picture']['error'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_types)) {
            if ($file_size <= 5000000) { // 5MB limit
                $new_file_name = uniqid('', true) . "." . $file_ext;
                $file_destination = $upload_dir . $new_file_name;
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    $profile_picture_path = $file_destination;
                } else {
                    $error_msg .= "Failed to upload the profile picture. ";
                }
            } else {
                $error_msg .= "File size exceeds the 5MB limit. ";
            }
        } else {
            $error_msg .= "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed. ";
        }
    } else {
        $error_msg .= "Error uploading file. ";
    }

    if (empty($error_msg)) {
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $donorNIC = $_POST['donorNIC'];
        $email = $_POST['email'];
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $address = $_POST['address'];
        $address2 = $_POST['address2'];
        $gender = $_POST['gender'];
        $bloodType = $_POST['bloodType'];
        $headache = isset($_POST['headache']) ? 1 : 0;
        $hiv = isset($_POST['hiv']) ? 1 : 0;
        $other_health_conditions = $_POST['otherHealthConditions'];

        $insert_sql = "INSERT INTO donors (first_name, last_name, donorNIC, username, email, password, address, address2, gender, bloodType, headache, hiv, other_health_conditions, profile_picture)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssssssssssssss", $first_name, $last_name, $donorNIC, $username, $email, $password_hashed, $address, $address2, $gender, $bloodType, $headache, $hiv, $other_health_conditions, $profile_picture_path);

        if ($stmt->execute()) {
            header("Location: success.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 CSS  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="/assets/css/footer.css">
  <script type="text/javascript" src="Js/slide.js"></script>
  <title>Blood Bank Management System</title>
</head>
<body class="p-1 m-0 border-0 bd-example">
<?php include '../home/header.php'; ?><br><br>
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
      
      <form class="needs-validation" novalidate method="POST" action="">
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
            <div class="input-group-prepend">
              <span class="input-group-text">@</span>
            </div>
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

        <div class="mb-3">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          <div class="invalid-feedback">
            Please enter a valid password.
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
          <label for="healthConditions">Health Conditions</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="headache" name="headache" value="1">
            <label class="form-check-label" for="headache">Headache</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="hiv" name="hiv" value="1">
            <label class="form-check-label" for="hiv">HIV</label>
          </div>
          <div class="form-group mt-2">
            <label for="otherHealthConditions">Other Health Conditions:</label>
            <textarea id="otherHealthConditions" name="otherHealthConditions" class="form-control"></textarea>
          </div>
        </div>

        <div class="form-group">
    <label for="exampleFormControlFile1">Upload Profile Picture</label><br>
    <input type="file" class="form-control-file" id="exampleFormControlFile1">
  </div>
        <hr class="mb-4">
        <button class="btn btn-primary btn-lg btn-block" type="submit">Register</button>
      </form>
      <form action="show_donors.php" method="POST">
        <button class="btn btn-secondary btn-lg btn-block mt-3" id="showDataBtn">Show Data</button>
      </form>
    </div>
    <div class="col-md-1"></div>
  </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
