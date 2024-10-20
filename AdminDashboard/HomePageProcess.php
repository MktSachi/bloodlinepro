<?php
require '../Classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

$totalunits = 0;
$queryTotalBlood = "SELECT SUM(quantity) AS total FROM hospital_blood_inventory";
$resultTotalBlood = $conn->query($queryTotalBlood);
if ($resultTotalBlood->num_rows > 0) {
  $row = $resultTotalBlood->fetch_assoc();
  $totalunits = $row['total'];
}
$resultTotalBlood->free();

$queryDonorStats = "SELECT 
    COUNT(*) as total_donors,
    AVG(donation_count) as avg_donations,
    SUM(CASE WHEN bloodType IN ('A+', 'A-') THEN 1 ELSE 0 END) as type_a_count,
    SUM(CASE WHEN bloodType IN ('B+', 'B-') THEN 1 ELSE 0 END) as type_b_count,
    SUM(CASE WHEN bloodType IN ('AB+', 'AB-') THEN 1 ELSE 0 END) as type_ab_count,
    SUM(CASE WHEN bloodType IN ('O+', 'O-') THEN 1 ELSE 0 END) as type_o_count
FROM donors";
$resultDonorStats = $conn->query($queryDonorStats);
$donorStats = $resultDonorStats->fetch_assoc();

$queryLastDonation = "SELECT MAX(donationDate) as last_donation FROM donations";
$resultLastDonation = $conn->query($queryLastDonation);
$lastDonation = $resultLastDonation->fetch_assoc();

$hospitals = [];
$queryHospitals = "SELECT hospitalName, SUM(quantity) AS totalBlood FROM hospital_blood_inventory hbi JOIN hospitals h ON hbi.hospitalID = h.hospitalID GROUP BY hospitalName";
$resultHospitals = $conn->query($queryHospitals);
if ($resultHospitals->num_rows > 0) {
  while ($row = $resultHospitals->fetch_assoc()) {
    $hospitals[$row['hospitalName']] = $row['totalBlood'];
  }
}
$resultHospitals->free();

$bloodTypeData = [];
$queryBloodType = "SELECT bloodType, SUM(quantity) AS total FROM hospital_blood_inventory GROUP BY bloodType";
$resultBloodType = $conn->query($queryBloodType);
if ($resultBloodType->num_rows > 0) {
  while ($row = $resultBloodType->fetch_assoc()) {
    $bloodTypeData[$row['bloodType']] = $row['total'];
  }
}
$resultBloodType->free();

$db->close();
?>
