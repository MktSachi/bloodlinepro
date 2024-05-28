<?php


$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with  your database password
$dbname = "blood_donations";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $donorName = $_POST['donorName'];
    $donorNIC = $_POST['donorNIC'];
    $bloodType = $_POST['bloodType'];
    $hospital = $_POST['hospital'];
    $expireDate = $_POST['expireDate'];
    $quantity = $_POST['quantity'];
    $donationDate = date('Y-m-d'); // Today's date

// Prepare SQL query
$sql = "INSERT INTO donations (donorName, donorNIC, bloodType, hospital, expireDate, quantity, donationDate) 
        VALUES ('$donorName', '$donorNIC', '$bloodType', '$hospital', '$expireDate', '$quantity', '$donationDate')";

// Execute SQL query
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


    $conn->close();
}



// Fetch existing records
$sql = "SELECT donorName, donorNIC, bloodType, hospital, expireDate, quantity, donationDate FROM donations";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
} else {
    echo "No records found";
}


$conn->close();
?>
