<?php
session_start();
require_once('../Classes/Database.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Connect to the database
$db = new Database();
$conn = $db->getConnection();
$username = $_SESSION['username'];

// Fetch donor details
$sql = "SELECT * FROM donors WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $donorNIC = $row['donorNIC'];
    $bloodType = $row['bloodType'];
    $address = $row['address'];
    $donationCount = $row['donation_count'];
    $birthday = isset($row['birthday']) ? $row['birthday'] : 'N/A'; // Ensure $birthday is defined
    $issuedDate = date("Y-m-d");
    $profilePicture = $row['profile_picture'];

    // Check if the user is eligible for a donor ID card
    if ($donationCount = 5) {
        // Generate a unique card number if not already set
        if (!isset($_SESSION['cardNumber'])) {
            $cardNumber = uniqid("BLOODPRO-");
            $_SESSION['cardNumber'] = $cardNumber;
        } else {
            $cardNumber = $_SESSION['cardNumber'];
        }

        // Save card details to session for download
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['bloodType'] = $bloodType;
        $_SESSION['address'] = $address;
        $_SESSION['issuedDate'] = $issuedDate;
        $_SESSION['donorNIC'] = $donorNIC;
        $_SESSION['profilePicture'] = $profilePicture;
        $_SESSION['birthday'] = $birthday;
    } else {
        // Redirect back if not eligible
        header('Location: award.php');
        exit;
    }
} else {
    echo "Donor information not found.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor ID Card - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
            padding-left: 100px;
        }

        .card-container {
            width: 100%;
            max-width: 850px;
            margin: 10px auto;
            background: linear-gradient(135deg, #cc0000, #990000); 
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .card-header-design {
            background-color: #cc0000; 
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-text {
            color: white;
        }

        .header-text h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 700;
            text-align: center;
        }

        .header-text p {
            margin: 0;
            font-size: 14px;
        }

        .diagonal-design {
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 100%;
            background: linear-gradient(135deg, transparent 50%, #990000 50%); 
        }

        .card-body {
            background-color: white;
            padding: 30px;
            display: flex;
            gap: 30px;
            position: relative;
        }

        .profile-section {
            flex: 0 0 200px;
        }

        .profile-picture {
            width: 450px;  
            height: 450px; 
            object-fit: cover;
             
            border-radius: 10px;
        }

        .id-picture {
            width: 200px;  
            height: 200px; 
            object-fit: cover;
            border-radius: 10px;
            
        }

        .id-section {
            flex: 0 0 200px;
            padding-top: 70px;
        }

        .info-section {
            flex: 1;
            padding: 20px;
        }

        .card-title {
            background-color: #cc0000; 
            color: white;
            padding: 10px 30px;
            display: inline-block;
            border-radius: 25px;
            margin-bottom: 20px;
        }

        .donor-info {
            display: grid;
            gap: 2px;
        }

        .info-row {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 8px;
        }

        .info-label {
            flex: 0 0 120px;
            font-weight: 600;
            color: #cc0000; 
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .card-footer {
            background-color: #990000; 
            padding: 15px;
            text-align: right;
            color: white;
        }

        .download-button {
            margin-top: 30px;
            text-align: center;
        }

        .download-button .btn {
            background-color: #cc0000; 
            border: none;
            padding: 12px 35px;
            font-size: 18px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .download-button .btn:hover {
            background-color: #990000; 
        }

            @media (max-width: 768px) {
        .card-body {
            flex-direction: column;
            padding: 15px;
        }

        .profile-section {
            flex: 0 0 auto;
            text-align: center;
        }

        .profile-picture {
            width: 250px;  
            height: 250px;
            margin: 0 auto;
        }

        .info-section {
            padding: 15px 0;
        }

        .card-title {
            text-align: center;
            width: 100%;
            padding: 10px 15px;
        }

        .donor-info {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .info-row {
            flex-direction: column;
            text-align: center;
            padding: 10px 0;
            border-bottom: none; 
        }

        .info-label {
            flex: 0 0 auto;
            font-weight: 600;
        }

        .info-value {
            flex: 0 0 auto;
        }
    }


    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="container mt-5">
        <?php if ($donationCount = 5): ?>
            <div class="card-container" id="donorIdCard">
                <div class="card-header-design">
                    <div class="header-content">
                        
                        <div class="header-text">
                            <h1>BLOODLINEPRO</h1>
                            <p>Donor Identification Card</p>
                        </div>
                    </div>
                    <div class="diagonal-design"></div>
                </div>

                <div class="card-body">
                    <div class="id-section">
                        <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="id-picture">
                    </div>
                    <div class="info-section">
                        <div class="card-title">DONOR INFORMATION</div>
                        <div class="donor-info">
                            <div class="info-row">
                                <div class="info-label">Name</div>
                                <div class="info-value"><?php echo htmlspecialchars($firstName . " " . $lastName); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">NIC</div>
                                <div class="info-value"><?php echo htmlspecialchars($donorNIC); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Blood Type</div>
                                <div class="info-value"><?php echo htmlspecialchars($bloodType); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Birthday</div>
                                <div class="info-value">2001/01/17</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Address</div>
                                <div class="info-value"><?php echo htmlspecialchars($address); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Card Number</div>
                                <div class="info-value"><?php echo htmlspecialchars($_SESSION['cardNumber']); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Issued Date</div>
                                <div class="info-value"><?php echo htmlspecialchars($issuedDate); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="signature">Signature: HP/MHO/SHO</div>
                </div>
            </div>

            <div class="download-button">
                <button id="downloadBtn" class="btn btn-primary btn-lg">Download ID Card</button>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                You need to complete 15 donations to generate an ID card.
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('downloadBtn').addEventListener('click', function() {
            const cardBody = document.querySelector("#donorIdCard");
            html2canvas(cardBody, {
                scale: 2,
                useCORS: true,
                logging: true,
                backgroundColor: null
            }).then(canvas => {
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = 'donor_id_card.png';
                link.click();
            });
        });
    </script>
</body>
</html>