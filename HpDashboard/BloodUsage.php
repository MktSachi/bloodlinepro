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
</head>
<body>
    <?php include './HpSidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="w3-main" style="margin-left:200px;margin-top:43px;">
        
        <div class="container">
            <h3><strong>Blood Usage</strong></h3>
            
            <!-- Operation links with icons -->
            <div class="operation-links">
                <a href="HandleBloodUsage/BloodUsagePatient.php" target="contentFrame" class="btn btn-primary">
                                       <i class="fa fa-user-injured"></i></i> Patient
                </a>
                
                <a href="HandleBloodUsage/TransferBlood.php" target="contentFrame" class="btn btn-info">
                                       <i class="fa fa-hospital" style="margin-right: 5px;"></i>Transfer to Other Hospital
                </a>
            </div>

            <!-- Iframe to load content -->
            <iframe name="contentFrame" style="width: 100%; height: 600px; border: none;"></iframe>
        </div>
    
    </div>
</body>
</html>
