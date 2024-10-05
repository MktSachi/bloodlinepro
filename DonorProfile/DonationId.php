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
    $issuedDate = date("Y-m-d");
    $profilePicture = $row['profile_picture'];

    // Check if the user is eligible for a donor ID card
    if ($donationCount >= 15) {
        // Generate a unique card number
        $cardNumber = uniqid("BLOODPRO-");

        // Save card details to session for download
        $_SESSION['cardNumber'] = $cardNumber;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['bloodType'] = $bloodType;
        $_SESSION['address'] = $address;
        $_SESSION['issuedDate'] = $issuedDate;
        $_SESSION['donorNIC'] = $donorNIC;
        $_SESSION['profilePicture'] = $profilePicture;
       
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
    <style>
        body {
            background-color: #f8f9fa;
             padding-left: 50px;
        }
        .card-container {
            
            border-radius: 15px;
            padding: 10px;
            width: 100%;
            max-width: 700px;
            margin: 30px auto;
            background-color: #ffffff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            
           
        }
        .card-header {
            text-align: center;
            font-weight: bold;
            font-size: 28px;
            color: darkred;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .card-body {
            display: flex;
            align-items: flex-start;
            background-color:#f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        .profile-picture {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 30px;
            margin-top: 60px;
            border: 3px solid darkred;
        }
        .donor-info {
            flex: 1;
        }
        .donor-info p {
            margin: 8px 0;
            font-size: 16px;
            color: #333;
        }
        .donor-info strong {
            color: #000;
            font-weight: 600;
        }
        .signature {
            text-align: right;
            margin-top: 250px;
            font-size: 16px;
            font-style: italic;
            color: black    ;
        }
        .card-footer {
            margin-top: 25px;
            text-align: center;
            color: darkred;
            font-size: 16px;
            font-weight: bold;
        }
        .download-button {
            margin-top: 30px;
            text-align: center;
        }
        .download-button .btn {
            font-size: 18px;
            padding: 10px 30px;
        }
        @media (max-width: 768px) {
            .card-body {
                flex-direction: column;
                align-items: center;
            }
            .profile-picture {
                margin-right: 0;
                margin-bottom: 20px;
            }
            .donor-info {
                text-align: center;
            }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container mt-5">
    <?php if ($donationCount >= 15): ?>
        <div class="card-container">
            <div class="card-header">
                BloodLinePro Donor ID Card
            </div>
            <div class="card-body">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture">
                <div class="donor-info">
                    <p><strong>Name:</strong> <?php echo $firstName . " " . $lastName; ?></p>
                    <p><strong>NIC:</strong> <?php echo $donorNIC; ?></p>
                    <p><strong>Blood Type:</strong> <?php echo $bloodType; ?></p>
                    <p><strong>Birthday:</strong> </p>
                    <p><strong>Address:</strong> <?php echo $address; ?></p>
                    <p><strong>Card Number:</strong> <?php echo $_SESSION['cardNumber']; ?></p>
                    <p><strong>Issued Date:</strong> <?php echo $issuedDate; ?></p>
                </div>
                <div class="signature">
                <strong>Signature:</strong> HP/MHO/SHO
               </div>
            </div>
            
            <div class="card-footer">
                <p>BloodLinePro - Saving Lives Together</p>
            </div>
        </div>

        <div class="download-button">
            <form method="post" action="download_card.php">
                <button type="submit" class="btn btn-success btn-lg">Download ID Card</button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            You need to complete 15 donations to generate an ID card.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.css"></script>
</body>
</html>