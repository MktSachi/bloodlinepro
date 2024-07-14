<?php include '../login_window/ForgotPasswordBack.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .forgot-password-container {
      max-width: 400px;
      width: 90%;
    }
    .forgot-password-card {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .icon {
      font-size: 3rem;
      color: #007bff;
      margin-bottom: 1rem;
    }
    .title {
      color: #333;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    .sub-title {
      color: #6c757d;
      margin-bottom: 1.5rem;
    }
    .form-group {
      margin-bottom: 1.5rem;
    }
    .form-control {
      border-radius: 8px;
      padding: 0.75rem 1rem;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <div class="forgot-password-container">
    <div class="forgot-password-card">
      <div class="text-center">
        <i class="fas fa-lock icon"></i>
        <h2 class="title">Reset Password</h2>
        <p class="sub-title">Enter your email to receive a password reset link</p>
      </div>
      <form action="ForgotPassword.php" method="post">
        <div class="form-group">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
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
        echo "
        Swal.fire({
          icon: 'info',
          title: 'Password Reset',
          text: '$message',
          confirmButtonColor: '#007bff'
        });
        ";
        unset($_SESSION['mail_sent_message']);
      }
      ?>
    });
  </script>
</body>
</html>