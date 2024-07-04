<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
  
  <!-- Your Custom CSS files -->
  <link rel="stylesheet" href="../Assets/css/home.css">
  <link rel="stylesheet" href="../Assets/css/header.css">
  <link rel="stylesheet" href="../Assets/css/footer.css">
  <link rel="stylesheet" href="../Assets/css/fe.css">
  <link rel="stylesheet" href="../Assets/css/textcard.css">
  <link rel="stylesheet" href="../part_of_home_page/Slideshow/SlideShow.css">
  <script type="text/javascript" src="Js/slide.js"></script>
  <title>Blood Bank Management System</title>
  <link rel="icon" href="../Image/logo3 (1).png" type="image/icon type">
  <style>body{
    background-color: white;

  
  }
  h1, h2{
    font-weight: bold; /* Makes all headings bold */
  }
.content-wrapper {
  margin: 60px; /* Adjust the margin as per your design needs */
}



  </style>
  
</head>
<body class="p-0 m-0 border-0 bd-example">
  
  <?php include './home/Header.php'; ?>
  <?php include './Slideshow/SlideShow.php'; ?>
 <!-- <?php include './home/Feature.php'; ?> -->
 
  <?php include './home/Discription.php'; ?>
  <div class="content-wrapper">
  <?php include './home/Text.php'; ?>
</div>
<div class="content-wrapper">
  <?php include './home/Card.php'; ?>
</div>
<div class="content-wrapper">
  <?php include './home/TextBar.php'; ?>
</div>
  <div class="content-wrapper">
  <?php include './home/Pic.php'; ?>
</div>
  <?php include './home/Footer.php'; ?>
 
  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Optional: jQuery for Bootstrap (if needed) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
