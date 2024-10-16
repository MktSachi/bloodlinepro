<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>Inventory</title>
    <link rel="stylesheet" href="Css/DonorHandle.css">    
</head>
<body>
    <?php include './HpSidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="w3-main" style="margin-left:230px;margin-top:0px;">
        
        <div class="container">
                      <h3><i class="fa fa-bars" style="margin-right: 5px;"></i><strong>Inventory</strong></h3>
            
            <!-- Operation links with icons -->
            <div class="operation-links">
                <a href="BloodInventory/BloodCount.php" target="contentFrame" class="btn btn-primary">
                    <i class="fa fa-eye" style="margin-right: 5px;"></i>Show Blood Count
                </a>
                
                <a href="BloodInventory/BloodDonationReport.php" target="contentFrame" class="btn btn-info">
                    <i class="fa fa-eye" style="margin-right: 5px;"></i>Show Details Donation
                </a>

                <a href="BloodInventory/BloodUsageReport.php" target="contentFrame" class="btn btn-primary">
                    <i class="fa fa-eye" style="margin-right: 5px;"></i>Blood Usage
                </a>

                <a href="BloodInventory/PatientBloodUsage.php" target="contentFrame" class="btn btn-info">
                    <i class="fa fa-eye" style="margin-right: 5px;"></i>Patient Blood Usage
                </a>
                
            </div>

            <!-- Iframe to load content -->
            <iframe name="contentFrame" style="width: 100%; height: 600px; border: none;"></iframe>
        </div>
    
    </div>
    <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>
</body>
</html>
