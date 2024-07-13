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
  <title>Registration Success</title>
</head>
<body>
<div class="success-container">
  <div class="success-card">
    <div class="icon">
      <i class="fas fa-check-circle"></i>
    </div>
    <div class="message">Success!</div>
    <div class="sub-message">You have registered successfully! You can now log into your dashboard.</div>
    <a href="../login_window/login.php" class="btn btn-primary btn-block" >Loging</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
