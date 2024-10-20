<?php
require '../Classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch total blood units
$totalunits = 0;
$queryTotalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory";
$resultTotalBlood = $conn->query($queryTotalBlood);
if ($resultTotalBlood->num_rows > 0) {
  $row = $resultTotalBlood->fetch_assoc();
  $totalunits = $row['total'];
}
$resultTotalBlood->free();

// Fetch hospital blood counts
$hospitals = [];
$queryHospitals = "SELECT hospitalName, SUM(quantity) AS totalBlood FROM hospital_blood_inventory hbi JOIN hospitals h ON hbi.hospitalID = h.hospitalID GROUP BY hospitalName";
$resultHospitals = $conn->query($queryHospitals);
if ($resultHospitals->num_rows > 0) {
  while ($row = $resultHospitals->fetch_assoc()) {
    $hospitals[$row['hospitalName']] = $row['totalBlood'];
  }
}
$resultHospitals->free();

// Fetch blood type distribution
$bloodTypeData = [];
$queryBloodType = "SELECT bloodType, SUM(quantity) AS total FROM hospital_blood_inventory GROUP BY bloodType";
$resultBloodType = $conn->query($queryBloodType);
if ($resultBloodType->num_rows > 0) {
  while ($row = $resultBloodType->fetch_assoc()) {
    $bloodTypeData[$row['bloodType']] = $row['total'];
  }
}
$resultBloodType->free();

// Fetch low stock alerts (quantities < 200)
$lowStockBloodTypes = [];
$queryLowStock = "SELECT bloodType FROM hospital_blood_inventory WHERE quantity < 200";
$resultLowStock = $conn->query($queryLowStock);
if ($resultLowStock->num_rows > 0) {
  while ($row = $resultLowStock->fetch_assoc()) {
    $lowStockBloodTypes[] = $row['bloodType'];
  }
}
$resultLowStock->free();

$db->close();
?>
