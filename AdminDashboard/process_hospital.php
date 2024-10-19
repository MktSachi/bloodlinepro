<?php
// Include database connection
include '../Classes/Database.php'; // Adjust the path as necessary

// Include the Hospital class
include '../Classes/Hospital.php'; // Adjust the path as necessary

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Create a new Hospital object
$hospital = new Hospital($db);

// Get form data
$hospital->hospitalName = $_POST['hospitalName'];
$hospital->address = $_POST['address'];
$hospital->phoneNumber = $_POST['phoneNumber'];
$hospital->email = $_POST['email'];

// Add the hospital to the database
if ($hospital->addHospital()) {
    echo "<p>Hospital added successfully.</p>";
} else {
    echo "<p>Failed to add hospital.</p>";
}

?>