<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood_donations";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data based on NIC number
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['donorNIC'])) {
    $donorNIC = $_POST['donorNIC'];

    $sql_select = "SELECT first_name, last_name, bloodType FROM donors WHERE donorNIC = ?";
    $stmt = $conn->prepare($sql_select);
    $stmt->bind_param("s", $donorNIC);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = [
            'status' => 'success',
            'donorName' => $row['first_name'] . ' ' . $row['last_name'], // Combine first_name and last_name
            'bloodType' => $row['bloodType']
        ];
        echo json_encode($response);
    } else {
        $response = [
            'status' => 'error'
        ];
        echo json_encode($response);
    }

    $stmt->close();
}

$conn->close();
?>
