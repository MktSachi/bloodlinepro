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
    if ($donationCount >= 15) {
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
        }

        .card-container {
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            max-width: 900px;
            margin: 50px auto;
            background-color: #ffffff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .card-container::before,
        .card-container::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            z-index: 0;
        }

        .card-container::before {
            top: -50px;
            left: -50px;
            width: 100px;
            height: 100px;
            background-color: rgba(230, 57, 70, 0.1);
        }

        .card-container::after {
            bottom: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background-color: rgba(29, 53, 87, 0.1);
        }

        .card-header {
            font-weight: 700;
            font-size: 28px;
            color: #1d3557;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .card-body {
            display: flex;
            flex-direction: row;
            align-items: center;
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .profile-picture {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 30px;
            border: 5px solid #e63946;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .donor-info {
            width: 100%;
        }

        .donor-info p {
            margin: 12px 0;
            font-size: 15px;
            color: #333;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 8px;
        }

        .donor-info strong {
            color: #1d3557;
            font-weight: 600;
        }

        .signature {
            margin-top: 30px;
            font-size: 14px;
            font-style: italic;
            color: #1d3557;
            text-align: right;
        }

        .download-button {
            margin-top: 30px;
            text-align: center;
        }

        .download-button .btn {
            font-size: 18px;
            padding: 12px 35px;
            background-color: #e63946;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.3);
            color: #fff;
        }

        .download-button .btn:hover {
            background-color: #1d3557;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(29, 53, 87, 0.4);
            color: #fff;
        }

        @media (max-width: 768px) {
            .card-container {
                flex-direction: column;
            }

            .profile-picture {
                margin: 0 auto 30px;
            }
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="container mt-5">
        <?php if ($donationCount >= 15): ?>
            <div class="card-container" id="donorIdCard"> <!-- Added id for capturing -->
                <div class="card-header">
                    BloodLinePro Donor ID
                </div>
                <div class="card-body">
                    <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="profile-picture">
                    <div class="donor-info">
                        <p><strong>Name:</strong> <span><?php echo htmlspecialchars($firstName . " " . $lastName); ?></span></p>
                        <p><strong>NIC:</strong> <span><?php echo htmlspecialchars($donorNIC); ?></span></p>
                        <p><strong>Blood Type:</strong> <span><?php echo htmlspecialchars($bloodType); ?></span></p>
                        <p><strong>Birthday:</strong> <span><?php echo htmlspecialchars($birthday); ?></span></p>
                        <p><strong>Address:</strong> <span><?php echo htmlspecialchars($address); ?></span></p>
                        <p><strong>Card Number:</strong> <span><?php echo htmlspecialchars($_SESSION['cardNumber']); ?></span></p>
                        <p><strong>Issued Date:</strong> <span><?php echo htmlspecialchars($issuedDate); ?></span></p>
                    </div>
                </div>
                <div class="signature">
                    <strong>Signature:</strong> HP/MHO/SHO
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('downloadBtn').addEventListener('click', function() {
            // Get the card-body element
            const cardBody = document.querySelector("#donorIdCard");

            // Use html2canvas to capture the card body
            html2canvas(cardBody, {
                scale: 2 // Increase the scale for better image quality
            }).then(canvas => {
                // Create a link element for downloading
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png'); // Convert the canvas to data URL
                link.download = 'donor_id_card.png'; // Set the name of the downloaded file
                link.click(); // Trigger the download
            });
        });
    </script>
</body>

</html>