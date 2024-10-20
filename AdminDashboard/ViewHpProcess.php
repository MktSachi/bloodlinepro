<?php
require '../Classes/Database.php';
require '../Classes/Validator.php';

function getHospitals()
{
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT hospitalID, hospitalName FROM hospitals";
    $result = $conn->query($sql);

    $hospitals = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hospitals[$row['hospitalID']] = $row['hospitalName'];
        }
    }

    $db->close();
    return $hospitals;
}

$validator = new Validator();
$errors = [];
$success_message = '';
$hpData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    if (isset($_POST['registration_number']) && !isset($_POST['first_name'])) {
        // Step 1: Fetch data based on registration number
        $registration_number = $validator->sanitizeInput($_POST['registration_number']);

        $stmt = $conn->prepare("SELECT * FROM healthcare_professionals WHERE hpRegNo = ?");
        $stmt->bind_param("s", $registration_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $hpData = $result->fetch_assoc();
        } else {
            $errors[] = "No data found for the given registration number.";
        }

        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Step 2: Update form data
        $registration_number = $validator->sanitizeInput($_POST['registration_number']);
        $first_name = $validator->sanitizeInput($_POST['first_name']);
        $last_name = $validator->sanitizeInput($_POST['last_name']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $position = $validator->sanitizeInput($_POST['position']);
        $phone_number = $validator->sanitizeInput($_POST['phone_number']);
        $nic_number = $validator->sanitizeInput($_POST['nic_number']);
        $hospital_id = filter_var($_POST['hospital'], FILTER_VALIDATE_INT);

        // Validate registration number and position match
        $position_prefix = [
            'ho' => 'HO',
            'mho' => 'MHO',
            'sho' => 'SHO'
        ];

        if (!preg_match('/^(' . $position_prefix[$position] . ')\d{5}$/', $registration_number)) {
            $errors[] = "Invalid registration number";
        }

        // Validate NIC number
        if (!$validator->validateNIC($nic_number)) {
            $errors[] = "Invalid NIC number.";
        }

        // Validate phone number
        if (!preg_match('/^\d{10}$/', $phone_number)) {
            $errors[] = "Invalid phone number.";
        }

        // Validate email
        if (!$email) {
            $errors[] = "Invalid email address.";
        }

        if (empty($errors)) {
            try {
                $conn->begin_transaction();

                $stmtHp = $conn->prepare("UPDATE healthcare_professionals SET firstname = ?, lastname = ?, email = ?, position = ?, phonenumber = ?, hpnic = ?, hospitalid = ? WHERE hpRegNo = ?");
                $stmtHp->bind_param("ssssssis", $first_name, $last_name, $email, $position, $phone_number, $nic_number, $hospital_id, $registration_number);
                $stmtHp->execute();

                $conn->commit();

                $stmtHp->close();
                $success_message = "Healthcare professional account updated successfully!";
            } catch (Exception $e) {
                $conn->rollback();
                $errors[] = "Account update failed: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['delete'])) {
        // Delete functionality
        $registration_number = $validator->sanitizeInput($_POST['registration_number']);

        try {
            $stmtHp = $conn->prepare("DELETE FROM healthcare_professionals WHERE hpRegNo = ?");
            $stmtHp->bind_param("s", $registration_number);
            $stmtHp->execute();

            if ($stmtHp->affected_rows > 0) {
                $success_message = "Healthcare professional account deleted successfully!";
            } else {
                $errors[] = "No data found for the given registration number.";
            }

            $stmtHp->close();
        } catch (Exception $e) {
            $errors[] = "Account deletion failed: " . $e->getMessage();
        }
    }

    $db->close();
}
?>