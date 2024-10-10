<?php
session_start();
require_once('../Classes/Database.php');


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();


$username = $_SESSION['username'];
$sql = "SELECT hp.*, h.hospitalname FROM healthcare_professionals hp
        JOIN users u ON hp.userid = u.userid
        JOIN hospitals h ON hp.hospitalid = h.hospitalid
        WHERE u.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

$hp = $result->fetch_assoc();
if ($hp) {
    $hpRegNo = $hp['hpRegNo'];
    $firstName = $hp['firstname'];
    $lastName = $hp['lastname'];
    $position = $hp['position'];
    $email = $hp['email'];
    $phoneNumber = $hp['phonenumber'];
    $hpnic = $hp['hpnic'];
    $hospitalName = $hp['hospitalname'];
} else {
    $error_msg = "Error fetching HP information.";
    $stmt->close();
    $db->close();
    die($error_msg);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateHP'])) {
    $errors = array();

    
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    
    $phoneNumber = trim($_POST['phoneNumber']);
    if (!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
        $errors['phoneNumber'] = "Invalid phone number format";
    }

    
  

    
    $newUsername = trim($_POST['username']);
    if (empty($newUsername)) {
        $errors['username'] = "Username is required";
    }


    if ($newUsername !== $_SESSION['username']) {
        $sql_check_username = "SELECT * FROM users WHERE username = ?";
        $stmt_check_username = $conn->prepare($sql_check_username);
        $stmt_check_username->bind_param('s', $newUsername);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();
        if ($result_check_username->num_rows > 0) {
            $errors['username'] = "Username already exists. Please choose a different username.";
        }
        $stmt_check_username->close();
    }

    
    if (empty($errors)) {
        $conn->begin_transaction();
        try {
        
            $updateHPSql = "UPDATE healthcare_professionals SET email = ?, phonenumber = ?, position = ? WHERE hpRegNo = ?";
            $updateHPStmt = $conn->prepare($updateHPSql);
            $updateHPStmt->bind_param('sssi', $email, $phoneNumber, $position, $hpRegNo);
            $updateHPStmt->execute();

            
            $fetchUseridSql = "SELECT userid FROM users WHERE username = ?";
            $fetchUseridStmt = $conn->prepare($fetchUseridSql);
            $fetchUseridStmt->bind_param('s', $_SESSION['username']);
            $fetchUseridStmt->execute();
            $fetchUseridResult = $fetchUseridStmt->get_result();

            if ($fetchUseridResult->num_rows > 0) {
                $row = $fetchUseridResult->fetch_assoc();
                $userid = $row['userid'];

                
                $updateUsersSql = "UPDATE users SET username = ? WHERE userid = ?";
                $updateUsersStmt = $conn->prepare($updateUsersSql);
                $updateUsersStmt->bind_param("si", $newUsername, $userid);

                if ($updateUsersStmt->execute()) {
                    $conn->commit();

                    
                    $_SESSION['username'] = $newUsername;

                    
                    $_SESSION['success_msg'] = "HP details updated successfully";
                    header('Location: view_hp_details.php');
                    exit;
                } else {
                    throw new Exception("Failed to update user details.");
                }

                $updateUsersStmt->close();
            } else {
                throw new Exception("User not found in users table.");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error_msg = "Error updating HP details: " . $e->getMessage();
        }

        $updateHPStmt->close();
    }

    $db->close();
}
?>

