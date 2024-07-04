<?php
require 'Database.php';
require 'Donor.php';

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorNIC = $_POST['donorNIC'];

    $donorDetails = $donor->getDonorDetailsByNIC($donorNIC);
    if ($donorDetails) {
        echo json_encode([
            'success' => true,
            'firstName' => $donorDetails['first_name'],
            'lastName' => $donorDetails['last_name'],
            'bloodType' => $donorDetails['bloodType'],
            'email' => $donorDetails['email'],
            'phoneNumber' => $donorDetails['phoneNumber'],
            'username' => $donorDetails['username'],
            'address' => $donorDetails['address'],
            'gender' => $donorDetails['gender']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}

$db->close();
?>
