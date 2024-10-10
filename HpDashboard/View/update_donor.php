<?php
require '../../Classes/Database.php';
require '../../Classes/Donor.php';

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorNIC = $_POST['donorNIC'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $gender = $_POST['gender'];
    $bloodType = $_POST['bloodType'];

    
    $CheckUserName = $donor->CheckUserName($username);
    if ($CheckUserName) {
        $errorMessage = "Username already exists. Please choose a different username.";
    } else {
        
        $sql_donors = "UPDATE donors SET first_name = ?, last_name = ?, username = ?, email = ?, phoneNumber = ?, address = ?, address2 = ?, gender = ?, bloodType = ? WHERE donorNIC = ?";
        $stmt_donors = $conn->prepare($sql_donors);
        $stmt_donors->bind_param("ssssssssss", $firstName, $lastName, $username, $email, $phoneNumber, $address, $address2, $gender, $bloodType, $donorNIC);

        if ($stmt_donors->execute()) {
            
            $userid = $donor->getUserIDByDonorNIC($donorNIC); // Fetch userid using method in Donor class
            if ($userid !== false) {
                $sql_users = "UPDATE users SET username = ? WHERE userid = ?";
                $stmt_users = $conn->prepare($sql_users);
                $stmt_users->bind_param("si", $username, $userid);

                if ($stmt_users->execute()) {
                    $successMessage = "Donor details updated successfully!";
                } else {
                    $errorMessage = "Failed to update user details.";
                }
            } else {
                $errorMessage = "Failed to fetch user ID from donors table.";
            }
        } else {
            $errorMessage = "Failed to update donor details.";
        }

        $stmt_donors->close();
        if (isset($stmt_users)) {
            $stmt_users->close();
        }
    }
}

$db->close();
?>

   
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../Assets/css/header.css">
  <link rel="stylesheet" href="/Assets/css/footer.css">
  <style>
    .success-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f8f9fa;
    }
    .success-card {
      text-align: center;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
    }
    .success-card .icon {
      font-size: 2rem;
      color: #28a745;
      margin-bottom: 1rem;
    }
    .success-card .message {
      font-size: 1.25rem;
      font-weight: 700;
      color: #343a40;
      margin-bottom: 0.5rem;
    }
    .success-card .sub-message {
      font-size: 1rem;
      color: #6c757d;
      margin-bottom: 1.5rem;
    }
    .success-card .btn-primary {
      background-color: #031529;
      border-color: #031529;
    }
    
  </style>
  <title>Update Success</title>
</head>
<body>
<div class="success-container">
  <div class="success-card">
    <div class="icon">
      <i class="fas fa-check-circle"></i>
    </div>
    <?php if (isset($successMessage)): ?>
      <div class="message">Success!</div>
      <div class="sub-message"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif (isset($errorMessage)): ?>
      <div class="message">Error!</div>
      <div class="sub-message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
