<?php
// Database configuration
$servername = "localhost"; // Replace with your database server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "blood_donations"; // Replace with your database name

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example: Inserting a new donor into the 'donors' table
$first_name = "John";
$last_name = "Doe";
$email = "john.doe@example.com";

$sql_insert = "INSERT INTO donors (first_name, last_name, email) VALUES ('$first_name', '$last_name', '$email')";

if ($conn->query($sql_insert) === TRUE) {
    echo "New record inserted successfully<br>";
} else {
    echo "Error inserting record: " . $conn->error . "<br>";
}

// Example: Updating the email of a donor in the 'donors' table
$donor_id = 1;
$new_email = "new.email@example.com";

$sql_update = "UPDATE donors SET email='$new_email' WHERE id=$donor_id";

if ($conn->query($sql_update) === TRUE) {
    echo "Record updated successfully<br>";
} else {
    echo "Error updating record: " . $conn->error . "<br>";
}

// Example: Deleting a donor from the 'donors' table
$donor_id = 1;

$sql_delete = "DELETE FROM donors WHERE id=$donor_id";

if ($conn->query($sql_delete) === TRUE) {
    echo "Record deleted successfully<br>";
} else {
    echo "Error deleting record: " . $conn->error . "<br>";
}

// Example: Selecting all donors from the 'donors' table
$sql_select = "SELECT id, first_name, last_name, email FROM donors";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["first_name"]. " " . $row["last_name"]. " - Email: " . $row["email"]. "<br>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
