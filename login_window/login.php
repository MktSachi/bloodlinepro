
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
    body {
      background-color: #131212;
      color: #1b1919;
    }
    .login-container {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-frame {
      display: flex;
      width: 80%;
      max-width: 1000px;
      background: #fff;
      border: 1px solid #1b1919;
      box-shadow: 0 8px 8px 8px rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      overflow: hidden;
    }
    .movie-image, .login-form {
      flex: 1;
    }
    .movie-image {
      position: relative;
      overflow: hidden;
    }
    .movie-image img {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 100%;
      height: 100%;
      object-fit: cover;
      transform: translate(-50%, -50%);
    }
    .login-form {
      padding: 20px;
    }
    .login-form h1 {
      font-weight: 700;
      text-align: center;
      margin-bottom: 50px;
    }
    .login-form .form-group {
      margin-bottom: 20px;
    }
    .login-form .reset-password {
      text-align: right;
    }
    .login-form hr {
      border-color: #1b1919;
    }
    .social-icons {
      text-align: center;
    }
    .social-icons i {
      font-size: 24px;
      margin: 0 10px;
    }

    @media (max-width: 992px) {
      .login-frame {
        flex-direction: column;
      }
      .movie-image, .login-form {
        width: 100%;
        height: 50%;
      }
    }
    @media (max-width: 576px) {
      .login-form {
        padding: 15px;
      }
      .login-form h1 {
        font-size: 1.5rem;
      }
      .social-icons i {
        font-size: 20px;
        margin: 0 5px;
      }
    }
  </style>
</head>
<body>


<div class="container">
  <div class="login-container">
    <div class="login-frame">
      <div class="movie-image">
        <img src="../Image/1212log.png" alt="Movie Image">
      </div>
      <div class="login-form">
        <h1>Hello! Welcome to Bloodlinepro</h1>
        <?php if (!empty($error_msg)) { ?>
          <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php } ?>
        <form action="login.php" method="post">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
          </div>
          <div class="form-group reset-password">
            <a href="ForgotPassword.php">Forgot Password?</a>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
          <hr>
          <div class="social-icons">
            <a href="#" class="text-dark"><i class="fab fa-google"></i></a>
            <a href="#" class="text-dark"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-dark"><i class="fab fa-apple"></i></a>
          </div>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="">Create Account</a></p>
      </div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row shadow-lg p-0 mb-5 bg-white rounded w-100">
            <div class="col-md-6 d-none d-md-block p-0">
                <div class="img-box h-100"></div>
            </div>
            <div class="col-md-6 p-5 form-wrap">
                <div class="text-right mb-3">
                    <span>Don't you have an account?</span>
                    <a href="register.php" class="btn btn-outline-primary btn-sm">Sign Up</a>
                </div>
                <div class="text-center mb-4">
                    <h1>Welcome to BloodLinePro</h1>
                    <h6>Login to your Account</h6>
                </div>
                <?php if (!empty($error_msg)) { ?>
                    <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                <?php } ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                    </div>
                    <div class="form-group text-right">
                        <a href="forgot_password.php" class="btn btn-outline-primary btn-sm">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>