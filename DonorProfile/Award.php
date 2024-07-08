<?php include 'DonorProfile.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>Donor Account</title>
    <link rel="stylesheet" href="Css/DonorHandle.css">    
    <style>
 .bgcolor {
    background: linear-gradient(to right, #8e1b1b 0%, #230606 100%);
    color: white; /* Ensure text stands out against the background */
}


</style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    
    <div class="w3-main" style="margin-left:200px;margin-top:43px;">
        <div class="container mt-5">
            <h2 class="text-center mb-4">Awards</h2>
            <div class="row justify-content-center">
                <div class="col-md-4 ">
                    <div class="card text-center">
                        <div class="card-body bgcolor">
                            <?php if ($donationCount >= 1): ?>
                                <img src="Image/Silver.png" alt="Silver Badge" class="img-fluid mb-3 badge-image">
                                <h5 class="card-title styled-title">Silver Badge</h5>
                                <p class="card-text styled-text">You have donated 1 or more times and earned this silver badge!</p>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://example.com'); ?>" target="_blank" class="btn btn-primary btn-sm me-2"><i class="fab fa-facebook"></i> Share on Facebook</a>
                                    <a href="https://web.whatsapp.com/send?text=<?php echo urlencode('Check out my Silver Badge achievement on BloodLinePro! https://example.com'); ?>" target="_blank" class="btn btn-success btn-sm"><i class="fab fa-whatsapp"></i> Share on WhatsApp</a>
                                </div>
                            <?php else: ?>
                                <img src="Image/lock.png" alt="Locked Badge" class="img-fluid mb-3 badge-image">
                                <h5 class="card-title styled-title">Locked</h5>
                                <p class="card-text styled-text">You need to donate 1 more time(s) to earn the silver badge.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body bgcolor">
                            <?php if ($donationCount >= 5): ?>
                                <img src="/Image/Gold.png" alt="Platinum Badge" class="img-fluid mb-3 badge-image">
                                <h5 class="card-title styled-title">Platinum Badge</h5>
                                <p class="card-text styled-text">You have donated 5 or more times and earned this platinum badge!</p>
                        
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://example.com'); ?>" target="_blank" class="btn btn-primary btn-sm me-2"><i class="fab fa-facebook"></i> Share on Facebook</a>
                                    <a href="https://web.whatsapp.com/send?text=<?php echo urlencode('Check out my Platinum Badge achievement on BloodLinePro! https://example.com'); ?>" target="_blank" class="btn btn-success btn-sm"><i class="fab fa-whatsapp"></i> Share on WhatsApp</a>
                                </div>
                            <?php else: ?>
                                <img src="Image/lock.png" alt="Locked Badge" class="img-fluid mb-3 badge-image">
                                <h5 class="card-title styled-title">Locked</h5>
                                <p class="card-text styled-text">You need to donate <?php echo (5 - $donationCount); ?> more time(s) to earn the platinum badge.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body bgcolor">
                            <?php if ($donationCount >= 10): ?>
                                <img src="Image/Platinum.png" alt="Gold Badge" class="img-fluid mb-3 badge-image">
                                <h5 class="card-title styled-title">Gold Badge</h5>
                                <p class="card-text styled-text">You have donated 10 or more times and earned this gold badge!</p>
                                
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://example.com'); ?>" target="_blank" class="btn btn-primary btn-sm me-2"><i class="fab fa-facebook"></i> Share on Facebook</a>
                                    <a href="https://web.whatsapp.com/send?text=<?php echo urlencode('Check out my Gold Badge achievement on BloodLinePro! https://example.com'); ?>" target="_blank" class="btn btn-success btn-sm"><i class="fab fa-whatsapp"></i> Share on WhatsApp</a>
                                </div>
                            <?php else: ?>
                                <img src="Image/lock.png" alt="Locked Badge" class="img-fluid mb-3 badge-image">
                                <h5 class="card-title styled-title">Locked</h5>
                                <p class="card-text styled-text">You need to donate <?php echo (10 - $donationCount); ?> more time(s) to earn the gold badge.</p>
                            <?php endif; ?>
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
