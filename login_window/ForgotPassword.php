<?php include 'ForgotPasswordBack.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <!-- Custom styles -->
  <style>
    .forgot-password-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f8f9fa;
    }
    .forgot-password-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
    }
    .forgot-password-card .icon {
      font-size: 3rem;
      color: #007bff; /* Bootstrap primary color */
      margin-bottom: 1rem;
    }
    .forgot-password-card .title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #343a40;
      margin-bottom: 1rem;
    }
    .forgot-password-card .sub-title {
      font-size: 1rem;
      color: #6c757d;
      margin-bottom: 2rem;
    }
    .forgot-password-card .form-group {
      margin-bottom: 1.5rem;
    }
    .forgot-password-card .btn-primary {
      background-color: #007bff; /* Bootstrap primary color */
      border-color: #007bff;
    }
  </style>
</head>
<body>
  <div class="forgot-password-container">
    <div class="forgot-password-card">
      <div class="text-center">
        <i class="fas fa-key icon"></i>
        <h2 class="title">Forgot Password</h2>
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

  <!-- Bootstrap 5 JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery for Bootstrap's modal -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- Script for showing popup message -->
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
