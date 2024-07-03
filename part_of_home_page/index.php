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
  <link rel="stylesheet" href="../assets/css/home.css">
  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/fe.css">
  <link rel="stylesheet" href="../assets/css/textcard.css">
  <link rel="stylesheet" href="../part_of_home_page/Slideshow/slideshow.css">
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
  
  <?php include './home/header.php'; ?>
  <?php include './Slideshow/slide.php'; ?>
 <!-- <?php include './home/feature.php'; ?> -->
 
  <?php include './home/discription.php'; ?>
  <div class="content-wrapper">
  <?php include './home/text.php'; ?>
</div>
<div class="content-wrapper">
  <?php include './home/card.php'; ?>
</div>
<div class="content-wrapper">
  <?php include './home/TextBar.php'; ?>
</div>
  <div class="content-wrapper">
  <?php include './home/pic.php'; ?>
</div>
  <?php include './home/footer.php'; ?>
 
  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Optional: jQuery for Bootstrap (if needed) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
