<?php
require '../Classes/Database.php';
require '../Classes/Donor.php';

$db = new Database();
$connection = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Begin the transaction
        $connection->begin_transaction();

        // Insert into the users table with roleID as 'admin' and active status 3
        $insertUserSQL = "INSERT INTO users (username, password, roleID, active) VALUES (?, ?, 'admin', 3)";
        $stmt = $connection->prepare($insertUserSQL);
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        $userid = $stmt->insert_id;

        // Insert into the admin table
        $insertAdminSQL = "INSERT INTO admin (userid, username, full_name, email) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insertAdminSQL);
        $stmt->bind_param("isss", $userid, $username, $full_name, $email);
        $stmt->execute();

        // Commit the transaction
        $connection->commit();
        echo "Admin registered successfully!";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $connection->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the statement
        if (isset($stmt)) {
            $stmt->close();
        }

        // Close the connection
        $connection->close();
    }
}
?>