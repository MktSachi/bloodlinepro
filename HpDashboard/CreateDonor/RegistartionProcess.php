<?php
session_start();

require '../../Classes/Database.php';
require '../../Classes/Donor.php';
require '../../Classes/Validator.php';
require 'Email.php'; // Assuming Email.php is in the same folder as other classes

$db = new Database();
$conn = $db->getConnection();
$donor = new Donor($db);
$validator = new Validator();

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $donorNIC = $_POST['donorNIC'];

    // Generate a default password
    $password = bin2hex(random_bytes(4)); // 8 characters long

    if ($donor->CheckUserName($username)) {
        $error_msg .= "Username '$username' already exists. Please choose a different username. ";
    }

    if ($donor->DonorNICExists($donorNIC)) {
        $error_msg .= "Donor NIC '$donorNIC' already exists. Please use a different NIC. ";
    }

    // Handle file upload if a file was selected
    $file_destination = '';
    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_dir = '../../Classes/Upload/'; // Ensure the path is correct
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
            // Send the confirmation email
            $emailSender = new EmailSender();
            $emailSender->sendConfirmationEmail($data['email'], $data['firstName'], $data['username'], $password);

            $_SESSION['status'] = "Thank you for registering. A confirmation email has been sent to your email address.";
            // Redirect to success page
            header("Location: Success.php");
            exit();
        } else {
            $error_msg .= "Error: Registration failed.";
        }
    }
}

$db->close();

if (!empty($error_msg)) {
    echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
}
?>
