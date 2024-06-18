<?php
session_start();


$error_msg = "";
$success_msg = "";

// Include classes
require 'Database.php';
require 'Donor.php';
require 'Validator.php';
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

    if ($password !== $confirmPassword) {
        $error_msg .= "Passwords do not match. ";
    }

    if (!$validator->validatePassword($password)) {
        $error_msg .= "Password must contain at least one uppercase letter, one lowercase letter, one symbol, and one number. ";
    }

    if ($donor->isUsernameExists($username)) {
        $error_msg .= "Username '$username' already exists. Please choose a different username. ";
    }

    if ($donor->isDonorNICExists($donorNIC)) {
        $error_msg .= "Donor NIC '$donorNIC' already exists. Please use a different NIC. ";
    }

    // Handle file upload if a file was selected
    $file_destination = '';
    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_dir = '../Upload/';
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_destination = $upload_dir . $file_name;

        if (!in_array($file_ext, $allowed_types)) {
            $error_msg .= "Only JPG, JPEG, PNG, and GIF files are allowed. ";
        }

        if (!move_uploaded_file($file_tmp, $file_destination)) {
            $error_msg .= "Error occurred while uploading the file. ";
        }
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

    if ($validator->validateHealthConditions($data)) {
        $error_msg .= "Sorry, you cannot donate blood due to health conditions. ";
    }

    if (empty($error_msg)) {
        $data = array_merge($data, [
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'donorNIC' => $donorNIC,
            'username' => $username,
            'email' => $_POST['email'],
            'password_hashed' => password_hash($password, PASSWORD_DEFAULT),
            'phoneNumber' => $_POST['phoneNumber'],
            'address' => $_POST['address'],
            'address2' => $_POST['address2'],
            'gender' => $_POST['gender'],
            'bloodType' => $_POST['bloodType'],
            'otherHealthConditions' => $_POST['otherHealthConditions']
        ]);

        if ($donor->register($data, $file_destination)) {
            try {
                $emailSender->sendConfirmationEmail($data['email'], $data['firstName'], $data['username']);
                $_SESSION['status'] = "Thank you for registering. A confirmation email has been sent to your email address.";
                header("Location: success.php");
                exit();
            } catch (Exception $e) {
                $error_msg .= $e->getMessage();
            }
        } else {
            $error_msg .= "Error: Registration failed.";
        }
    }
}

$db->close();

?>
