<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <title>HealthCare Person Account</title>
    <link rel="stylesheet" href="../HpDashboard/Css/DonorHandle.css">    
    <style>
        #content-loader {
            position: absolute;
            top: 0;
            left: 230px;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .main-content {
            display: none;
        }
        </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="w3-main" style="margin-left:230px;margin-top:0px;">
    <div id="content-loader">
            <div class="spinner"></div>
        </div>
        <div class="main-content">
        <div class="container">
            <h3><strong>HealthCare Person Account</strong></h3>
            
            <!-- Operation links with icons -->
            <div class="operation-links">
                <a href="CreateHp.php" target="contentFrame" class="btn btn-primary">
                    <i class="fa fa-plus" style="margin-right: 5px;"></i>Create HealthCare Person
                </a>
                
                <a href="ViewHp.php" target="contentFrame" class="btn btn-info">
                    <i class="fa fa-eye" style="margin-right: 5px;"></i>HealthCare Person Information
                </a>
            </div>

            <!-- Iframe to load content -->
            <iframe name="contentFrame" style="width: 100%; height: 600px; border: none;"></iframe>
        </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('content-loader').style.display = 'none';
                document.querySelector('.main-content').style.display = 'block';
            }, 1500); // 1500 milliseconds = 1.5 seconds
        });
    </script>
</body>
</html>
