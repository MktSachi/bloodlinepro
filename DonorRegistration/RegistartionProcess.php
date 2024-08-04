<?php
session_start();

$error_msg = "";
$success_msg = "";

// Include classes
require '../Classes/Database.php';
require '../Classes/Donor.php';
require '../Classes/Validator.php';
require 'Email.php';

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);
$validator = new Validator();
$emailSender = new EmailSender();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $username = $_POST['username'];
    $donorNIC = $_POST['donorNIC'];

    // Validate password
    $error_msg .= $validator->validatePasswordStrength($password);
    $error_msg .= $validator->validatePasswordMatch($password, $confirmPassword);

    // Validate username
    $error_msg .= $validator->validateUsername($username, $donor);

    // Validate NIC
    if (!$validator->validateNIC($donorNIC)) {
        $error_msg .= "Invalid NIC number format. Please check your NIC Number ";
    }

    // Validate unique NIC
    $error_msg .= $validator->validateUniqueNIC($donorNIC, $donor);

    // Handle file upload if a file was selected
    $file_destination = '';
    if (!empty($_FILES['profile_picture']['name'])) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $error_msg .= $validator->validateFileUpload($file_name, $file_tmp);
        $file_destination = '../Upload/' . $file_name;
    }

    // Check if any health conditions are selected that prevent blood donation
    $data = [
        'hiv' => isset($_POST['hiv']) ? 1 : 0,
        'heart_disease' => isset($_POST['heart_disease']) ? 1 : 0,
        'diabetes' => isset($_POST['diabetes']) ? 1 : 0,
        'fits' => isset($_POST['fits']) ? 1 : 0,
        'paralysis' => isset($_POST['paralysis']) ? 1 : 0,
        'lung_diseases' => isset($_POST['lung_diseases']) ? 1 : 0,
        'liver_diseases' => isset($_POST['liver_diseases']) ? 1 : 0,
        'kidney_diseases' => isset($_POST['kidney_diseases']) ? 1 : 0,
        'blood_diseases' => isset($_POST['blood_diseases']) ? 1 : 0,
        'cancer' => isset($_POST['cancer']) ? 1 : 0
    ];

    $error_msg .= $validator->validateHealthConditionsSelection($data);

    if (empty($error_msg)) {
        // Prepare data for registration
        $data = array_merge($data, [
            'firstName' => $validator->sanitizeInput($_POST['firstName']),
            'lastName' => $validator->sanitizeInput($_POST['lastName']),
            'donorNIC' => $donorNIC,
            'username' => $validator->sanitizeInput($username),
            'email' => $validator->sanitizeInput($_POST['email']),
            'password_hashed' => password_hash($password, PASSWORD_DEFAULT),
            'phoneNumber' => $validator->sanitizeInput($_POST['phoneNumber']),
            'address' => $validator->sanitizeInput($_POST['address']),
            'address2' => $validator->sanitizeInput($_POST['address2']),
            'gender' => $validator->sanitizeInput($_POST['gender']),
            'bloodType' => $validator->sanitizeInput($_POST['bloodType']),
            'otherHealthConditions' => $validator->sanitizeInput($_POST['otherHealthConditions'])
        ]);

        // Register donor
        try {
            if ($donor->register($data, $file_destination)) {
                // Send confirmation email
                $emailSender->sendConfirmationEmail($data['email'], $data['firstName'], $data['username']);
                $_SESSION['status'] = "Thank you for registering. A confirmation email has been sent to your email address.";
                header("Location: Success.php");
                exit();
            } else {
                $error_msg .= "Error: Registration failed.";
            }
        } catch (Exception $e) {
            $error_msg .= $e->getMessage();
        }
    }
}

$db->close();
?>
