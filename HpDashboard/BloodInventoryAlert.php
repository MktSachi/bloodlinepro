<?php
require '../DonorRegistration/Database.php'; 
session_start();

if (!isset($_SESSION['hospitalID'])) {
    die("Hospital ID not set.");
}


$hospitalID = $_SESSION['hospitalID'];


$db = new Database();
$conn = $db->getConnection();


$lowStockBloodTypes = [];
$queryLowStock = "SELECT bloodType, quantity FROM hospital_blood_inventory 
                  WHERE hospitalID = ? AND quantity < 11";
$stmt = $conn->prepare($queryLowStock);
$stmt->bind_param("i", $hospitalID);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error fetching data: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $bloodType = $row['bloodType'];
    $quantity = $row['quantity'];

    
    echo '<div class="blood-group">';
    echo '<h2>Blood Type: ' . htmlspecialchars($bloodType) . '</h2>';
    echo '<p>Available Quantity: ' . htmlspecialchars($quantity) . '</p>';
    echo '</div>';
}

$stmt->close();
$conn->close();
?>
