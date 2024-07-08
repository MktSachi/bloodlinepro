<?php include 'DonorProfile.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>Donor Account</title>
    <link rel="stylesheet" href="Css/DonorHandle.css">    
    <link rel="stylesheet" href="style.css">    


</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="w3-main d-flex justify-content-center align-items-center" style="margin-left: 200px; height: calc(85vh - 56px);"> 
        <div class="profile-container mt-5">
            <div class="profile-section" id="contact-info">
                <h2 class="text-center">Contact Information</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <div class="profile-text">contact@bloodlinepro.com</div>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <div class="profile-text">+1 234 567 890</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address</label>
                            <div class="profile-text">123 Main Street, City, Country</div>
                        </div>
                        <div class="form-group">
                            <label>Social Media</label>
                            <div class="profile-text">
                                <a href="#" class="btn btn-primary"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="btn btn-primary"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="btn btn-primary"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="btn btn-primary"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
