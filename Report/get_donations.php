<?php
// Database connection parameters (same as save_donation.php)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all donations
$sql = "SELECT donorName, bloodType, expireDate, quantity FROM donations";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Output JSON encoded data
echo json_encode($data);

// Close connection
$conn->close();
?>
