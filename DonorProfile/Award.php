<?php include 'DonorProfile.php'; ?>
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
            <div class="achievement-header animate__animated animate__fadeInDown">
                <h1 class="text-center mb-0">Your Donor Achievements</h1>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                $achievements = [
                    ['name' => 'Silver', 'required' => 5, 'image' => 'Image/Silver.png', 'color' => 'linear-gradient(135deg, #B7B7B7, #E8E8E8)'],
                    ['name' => 'Gold', 'required' => 10, 'image' => 'Image/Gold.png', 'color' => 'linear-gradient(135deg, #FFD700, #FFA500)'],
                    ['name' => 'Platinum', 'required' => 15, 'image' => 'Image/Platinum.png', 'color' => 'linear-gradient(135deg, #E5E4E2, #A9A9A9)']
                ];

                foreach ($achievements as $index => $achievement):
                    $progress = min(100, ($donationCount / $achievement['required']) * 100);
                    $isUnlocked = $donationCount >= $achievement['required'];
                ?>
                <div class="col animate__animated animate__fadeInUp" style="animation-delay: <?php echo $index * 0.2; ?>s;">
              <div class="achievement-card">
              <div class="card-body">
             <img src="<?php echo $isUnlocked ? $achievement['image'] : 'Image/locked-badge.png'; ?>" 
                 alt="<?php echo $achievement['name']; ?> Badge" 
                 class="img-fluid badge-image <?php echo $isUnlocked ? 'badge-unlocked' : 'badge-locked'; ?>">
            <h3 class="styled-title"><?php echo $achievement['name']; ?> Badge</h3>
            <?php if ($isUnlocked): ?>
                <p class="styled-text">Congratulations! You've earned the <?php echo $achievement['name']; ?> Badge!</p>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://bloodlinepro.com/achievements'); ?>" 
                       target="_blank" class="btn btn-primary btn-sm">
                       <i class="fab fa-facebook"></i> Share
                    </a>
                    <a href="https://web.whatsapp.com/send?text=<?php echo urlencode('I\'ve earned the ' . $achievement['name'] . ' Badge on BloodLinePro! Join me in saving lives: https://bloodlinepro.com'); ?>" 
                       target="_blank" class="btn btn-success btn-sm">
                       <i class="fab fa-whatsapp"></i> Share
                    </a>
                </div>
            <?php else: ?>
                <p class="styled-text">Donate <?php echo ($achievement['required'] - $donationCount); ?> more time(s) to unlock!</p>
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
            
            <div class="total-donations text-center animate__animated animate__fadeInUp" style="marging-top=20px;">
                <h4 class="mb-3">Total Donations: <span class="text-danger"><?php echo $donationCount; ?></span></h4>
                <p class="mb-4">Keep donating to unlock more achievements and save more lives!</p>
                <a href="#" class="btn btn-danger btn-lg">Donate Now</a>
            </div>
        </div>
    </div>
    <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Animate progress bars
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach((bar) => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    });
    </script>
</body>
</html>