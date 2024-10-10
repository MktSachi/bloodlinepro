<?php include 'DonorProfile.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Contact Information - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Profile.css">

</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="w3-main d-flex justify-content-center align-items-center" style="margin-left: 200px; min-height: 100vh;">
        <div class="contact-container">
            <div class="contact-header">
                <h2 class="text-center mb-0">Contact Information</h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-item">
                        <label>Email</label>
                        <div class="profile-text">bloodlinepro.lk@gmail.com</div>
                    </div>
                    <div class="contact-item">
                        <label>Phone Number</label>
                        <div class="profile-text">011 225 5555</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-item">
                        <label>Address</label>
                        <div class="profile-text">123,Narahenpita,Colombo</div>
                    </div>
                    <div class="contact-item">
                        <label>Social Media</label>
                        <div class="social-media-icons">
                            <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>