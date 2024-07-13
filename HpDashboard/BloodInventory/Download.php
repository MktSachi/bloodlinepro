<?php
session_start();
require '../../DonorRegistration/Database.php';
require 'Inventory.php';

if (isset($_GET['download']) && $_GET['download'] === 'html') {
    if (isset($_SESSION['donations'])) {
        $data = $_SESSION['donations'];
        $inventory = new Inventory(null); // No DB connection needed for this function
        $inventory->generateHTMLDownload($data);
    } else {
        echo "No data available to download.";
    }
} else {
    echo "Invalid download request.";
}
?>
