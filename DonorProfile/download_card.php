<?php
session_start();
require_once('../Classes/Database.php');

// Check if the user is logged in and has the necessary session variables
if (!isset($_SESSION['username']) || !isset($_SESSION['cardNumber'])) {
    header('Location: login.php');
    exit;
}

// Set the content type to force download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="donor_id_card.png"');

// Create image
$width = 500;
$height = 300;
$image = imagecreatetruecolor($width, $height);

// Define colors
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$maroon = imagecolorallocate($image, 162, 54, 112); // #a23670

// Fill the background
imagefill($image, 0, 0, $white);

// Draw border
imagerectangle($image, 0, 0, $width - 1, $height - 1, $maroon);
imagerectangle($image, 1, 1, $width - 2, $height - 2, $maroon);
imagerectangle($image, 2, 2, $width - 3, $height - 3, $maroon);

// Add header
$font = '../path/to/your/font.ttf'; // You need to provide the path to a TTF font file
imagettftext($image, 20, 0, 100, 40, $maroon, $font, 'BloodLinePro Donor ID Card');

// Add profile picture
$profilePic = imagecreatefromstring(file_get_contents($_SESSION['profilePicture']));
$profilePicWidth = 100;
$profilePicHeight = 100;
imagecopyresampled($image, $profilePic, 20, 60, 0, 0, $profilePicWidth, $profilePicHeight, imagesx($profilePic), imagesy($profilePic));

// Add donor information
$y = 70;
$fontSize = 12;
$lineHeight = 20;

$infoLines = [
    'Name: ' . $_SESSION['firstName'] . ' ' . $_SESSION['lastName'],
    'NIC: ' . $_SESSION['donorNIC'],
    'Blood Type: ' . $_SESSION['bloodType'],
    'Address: ' . $_SESSION['address'],
    'Card Number: ' . $_SESSION['cardNumber'],
    'Issued Date: ' . $_SESSION['issuedDate']
];

foreach ($infoLines as $line) {
    imagettftext($image, $fontSize, 0, 140, $y, $black, $font, $line);
    $y += $lineHeight;
}

// Add signature
imagettftext($image, 10, 0, 300, 260, $black, $font, 'Signature: HP/MHO/SHO');

// Add footer
imagettftext($image, 12, 0, 100, 280, $maroon, $font, 'BloodLinePro - Saving Lives Together');

// Output image
imagepng($image);

// Free memory
imagedestroy($image);
imagedestroy($profilePic);
?>