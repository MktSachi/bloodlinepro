<?php 
include 'Badge.php';
include 'DonorProfile.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Donor Achievements - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="Profile.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <h1 class="text-center mb-4 animate__animated animate__fadeInDown">Your Donor Achievements</h1>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                $badges = Badge::getAllBadges();
                foreach ($badges as $index => $badge):
                    $progress = $badge->getProgress($donationCount);
                    $isUnlocked = $badge->isUnlocked($donationCount);
                ?>
                <div class="col animate__animated animate__fadeInUp" style="animation-delay: <?php echo $index * 0.2; ?>s;">
                    <div class="achievement-card">
                        <div class="card-body">
                            <img src="<?php echo $isUnlocked ? $badge->getImageUnlocked() : $badge->getImageLocked(); ?>" 
                                 alt="<?php echo $badge->getName(); ?> Badge" 
                                 class="img-fluid badge-image <?php echo $isUnlocked ? 'badge-unlocked' : 'badge-locked'; ?>">
                            <h3><?php echo $badge->getName(); ?> Badge</h3>
                            <?php if ($isUnlocked): ?>
                                <p>Congratulations! You've earned this badge!</p>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://bloodlinepro.com/achievements'); ?>" 
                                       target="_blank" class="btn btn-primary btn-sm">
                                       <i class="fab fa-facebook"></i> Share
                                    </a>
                                    <a href="https://web.whatsapp.com/send?text=<?php echo urlencode('I\'ve earned the ' . $badge->getName() . ' Badge on BloodLinePro! Join me in saving lives: https://bloodlinepro.com'); ?>" 
                                       target="_blank" class="btn btn-success btn-sm">
                                       <i class="fab fa-whatsapp"></i> Share
                                    </a>
                                </div>
                            <?php else: ?>
                                <p>Donate <?php echo $badge->getRemainingDonations($donationCount); ?> more time(s) to unlock!</p>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%" 
                                         aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?php echo round($progress); ?>%
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="total-donations text-center animate__animated animate__fadeInUp mt-4">
                <h4 class="mb-3">Total Donations: <span class="text-danger"><?php echo $donationCount; ?></span></h4>
                <p class="mb-4">Keep donating to unlock more achievements and save more lives!</p>
            </div>
        </div>
    </div>
    <div class="footer">
        @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="DonorScript.js"></script>
</body>
</html>