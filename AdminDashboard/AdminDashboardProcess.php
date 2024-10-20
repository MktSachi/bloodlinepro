<?php
session_start();
require_once('../Classes/Database.php');

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit;
}

$totalunits = 0;
$totalHealthcareProfessionals = 0;
$totalHospitals = 0; // Initialize total hospitals variable

// Query to get total blood inventory
$queryTotalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory";
$resultTotalBlood = $conn->query($queryTotalBlood);
if ($resultTotalBlood->num_rows > 0) {
    $row = $resultTotalBlood->fetch_assoc();
    $totalunits = $row['total'];
}
$resultTotalBlood->free();

// Query to get total healthcare professionals
$queryTotalHealthcareProfessionals = "SELECT COUNT(*) AS total FROM healthcare_professionals";
$resultTotalHealthcareProfessionals = $conn->query($queryTotalHealthcareProfessionals);
if ($resultTotalHealthcareProfessionals->num_rows > 0) {
    $row = $resultTotalHealthcareProfessionals->fetch_assoc();
    $totalHealthcareProfessionals = $row['total'];
}
$resultTotalHealthcareProfessionals->free();

// Query to get total hospitals
$queryTotalHospitals = "SELECT COUNT(*) AS total FROM hospitals";
$resultTotalHospitals = $conn->query($queryTotalHospitals);
if ($resultTotalHospitals->num_rows > 0) {
    $row = $resultTotalHospitals->fetch_assoc();
    $totalHospitals = $row['total'];
}
$resultTotalHospitals->free();

$db->close();
?>
