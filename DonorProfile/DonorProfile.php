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
$sql = "SELECT * FROM donors WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();


$firstName = '';
$lastName = '';
$donorNIC = '';
$email = '';
$phoneNumber = '';
$address = '';
$address2 = '';
$gender = '';
$bloodType = '';
$profilePicture = '';
$donationCount = '';


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $donorNIC = $row['donorNIC'];
    $email = $row['email'];
    $phoneNumber = $row['phoneNumber'];
    $address = $row['address'];
    $address2 = $row['address2'];
    $gender = $row['gender'];
    $bloodType = $row['bloodType'];
    $profilePicture = $row['profile_picture'];
    $donationCount = $row['donation_count'];
    
    
    $_SESSION['donorNIC'] = $donorNIC;

    $stmt->close();
} else {
    $error_msg = "Error fetching donor information.";
    $stmt->close();
    $db->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateDonor'])) {
    
    $errors = array();

    
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    
    $phoneNumber = trim($_POST['phoneNumber']);
    if (!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
        $errors['phoneNumber'] = "Invalid phone number format";
    }

    
    $address = trim($_POST['address']);
    if (empty($address)) {
        $errors['address'] = "Address is required";
    }

    
    $newUsername = trim($_POST['username']);
    if (empty($newUsername)) {
        $errors['username'] = "Username is required";
    }

    
    $CheckUserName = false;
    if ($newUsername !== $_SESSION['username']) {
        $sql_check_username = "SELECT * FROM donors WHERE username = ?";
        $stmt_check_username = $conn->prepare($sql_check_username);
        $stmt_check_username->bind_param('s', $newUsername);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();

        if ($result_check_username->num_rows > 0) {
            $CheckUserName = true;
            $errors['username'] = "Username already exists. Please choose a different username.";
        }

        $stmt_check_username->close();
    }

    
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
            $uploadDir = '../Upload/';
            $profilePicturePath = $uploadDir . basename($_FILES['profile_picture']['name']);
            if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicturePath)) {
                $errors['profile_picture'] = "Failed to upload profile picture";
            }
        } else {
            $errors['profile_picture'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    }

    
    if (empty($errors)) {
        $conn->begin_transaction();

        try {
            
            $updateDonorsSql = "UPDATE donors SET email = ?, phoneNumber = ?, address = ?, username = ?, profile_picture = ? WHERE username = ?";
            $updateDonorsStmt = $conn->prepare($updateDonorsSql);
            $updateDonorsStmt->bind_param('ssssss', $email, $phoneNumber, $address, $newUsername, $profilePicturePath, $_SESSION['username']);
            $updateDonorsStmt->execute();

            
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

                    
                    $_SESSION['success_msg'] = "Donor details updated successfully";
                    header('Location: view_donor_details.php');
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
            $error_msg = "Error updating donor details: " . $e->getMessage();
        }

        $updateDonorsStmt->close();
    }

    $db->close();
}
?>