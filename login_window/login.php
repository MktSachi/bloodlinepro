<?php include 'LoginProcess.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    .login-section {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: hsl(0, 0%, 96%);
    }
    .login-card {
      max-width: 600px;
      width: 100%;
    }
    .form-check {
      text-align: center;
    }
    .text-dark-red {
    color: darkred;
}

.btn-dark-red {
    background-color: darkred;
    border-color: darkred;
    color: white; /* Ensure the text is readable */
}

.btn-dark-red:hover {
    background-color: #a00000; /* Darker shade for hover effect */
    border-color: #a00000;
}
  </style>
</head>
<body>
<section class="login-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <h1 class="my-5 display-4 fw-bold text-center">
          Sri-Lanka <br />
          <span class="text-dark-red" style="color: darkred;">Blood Line Pro</span>
        </h1>
        <p class="text-center" style="color: hsl(217, 10%, 50.8%)">
        Our platform simplifies the entire process of blood donation, from donor registration to hospital inventory management. With real-time updates, automated notifications, and comprehensive reporting, BloodLinePro ensures that the right resources are always available at the right time.
        </p>
      </div>

      <div class="col-lg-6">
        <div class="card login-card">
          <div class="card-body py-5 px-md-5">
            <?php if (!empty($error_msg)) { ?>
              <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php } ?>
            <form action="login.php" method="post">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
              </div>
              <div class="form-group form-check text-left">
                  <input type="checkbox" name="remember_me" id="remember_me" class="form-check-input">
                  <label for="remember_me" class="form-check-label">Remember Me</label>
              </div>
             
              <button type="submit" class="btn btn-dark-red btn-block">Login</button>
              <div class="form-group text-right">
                  <a href="ForgotPassword.php">Forgot Password?</a>
              </div>
            </form>
            <div class="text-center ">
                <p>or sign up with:</p>
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-facebook-f text-dark-red"></i>
                </button>
            
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-google text-dark-red"></i>
                </button>
            
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-twitter text-dark-red"></i>
                </button>
            
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-github text-dark-red"></i>
                </button>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
