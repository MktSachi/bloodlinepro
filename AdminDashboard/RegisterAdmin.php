<?php
require '../Classes/Database.php';
require '../Classes/Donor.php'; 

$db = new Database();
$connection = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        
        $connection->begin_transaction();

        
        $insertUserSQL = "INSERT INTO users (username, password, roleID, active) VALUES (?, ?, 'admin', 3)";
        $stmt = $connection->prepare($insertUserSQL);
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        $userid = $stmt->insert_id;

        
        $insertAdminSQL = "INSERT INTO admin (userid, username, full_name, email) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insertAdminSQL);
        $stmt->bind_param("isss", $userid, $username, $full_name, $email);
        $stmt->execute();

        
        $connection->commit();
        echo "Admin registered successfully!";
    } catch (Exception $e) {
        
        $connection->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        
        if (isset($stmt)) {
            $stmt->close();
        }

        
        $connection->close();
    }
}
?>
