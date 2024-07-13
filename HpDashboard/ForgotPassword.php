<?php include '../login_window/ForgotPasswordBack.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <!-- Custom styles -->
  <link rel="stylesheet" href="../DonorProfile/style.css">    

  <style>
    body{
      background: linear-gradient(to bottom, #022F40 0%, #38AECC 100%);
      color: white;
    }
  </style>
</head>
<body>
  <div class="forgot-password-container">
    <div class="forgot-password-card">
      <div class="text-center">
        <i class="fas fa-key icon"></i>
        <h2 class="title">Change Password</h2>
        <p class="sub-title">Enter your email address to receive a password reset link.</p>
      </div>
      <form action="ForgotPassword.php" method="post">
        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
      </form>
    </div>
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  
  <script>
    $(document).ready(function() {
      <?php
      if (isset($_SESSION['mail_sent_message'])) {
        $message = $_SESSION['mail_sent_message'];
        echo "alert('$message');";
        unset($_SESSION['mail_sent_message']);
      }
      ?>
    });
  </script>
</body>
</html>
