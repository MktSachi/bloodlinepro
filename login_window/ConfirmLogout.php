<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Confirm Logout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .confirm-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f8f9fa;
    }
    .confirm-card {
      text-align: center;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
    }
    .confirm-card .icon {
      font-size: 2rem;
      color: #dc3545; /* Red color for danger */
      margin-bottom: 1rem;
    }
    .confirm-card .message {
      font-size: 1.25rem;
      font-weight: 700;
      color: #343a40;
      margin-bottom: 0.5rem;
    }
    .confirm-card .sub-message {
      font-size: 1rem;
      color: #6c757d;
      margin-bottom: 1.5rem;
    }
    .confirm-card .btn-danger {
      background-color: #dc3545; /* Red color for danger */
      border-color: #dc3545;
    }
    .confirm-card .btn-secondary {
      background-color: #6c757d; /* Gray color for cancel */
      border-color: #6c757d;
    }
  </style>
</head>
<body>
  <div class="confirm-container">
    <div class="confirm-card">
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i> <!-- Replace with appropriate icon -->
      </div>
      <div class="message">Confirm Logout</div>
      <div class="sub-message">Are you sure you want to log out?</div>
      <form action="Logout.php" method="post">
        <button type="submit" class="btn btn-danger" name="confirm_logout">Yes, Log Out</button>
        <a href="javascript:history.go(-1)" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
