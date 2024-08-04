<?php
require '../Classes/Database.php';
require '../Classes/Validator.php';
require 'CreateHpEmail.php';

class Admin {
    private $db;
    private $validator;
    private $emailSender;

    public function __construct() {
        $this->db = new Database();
        $this->validator = new Validator();
        $this->emailSender = new EmailSender();
    }

    public function generateRandomPassword($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }

    public function getHospitals() {
        $conn = $this->db->getConnection();
        $sql = "SELECT hospitalID, hospitalName FROM hospitals";
        $result = $conn->query($sql);

        $hospitals = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hospitals[$row['hospitalID']] = $row['hospitalName'];
            }
        }

        $this->db->close();
        return $hospitals;
    }

    public function createHealthcareProfessional($postData) {
        $conn = $this->db->getConnection();

        $username = $this->validator->sanitizeInput($postData['username']);
        $roleID = 'hp';
        $active = 2;
        $registration_number = $this->validator->sanitizeInput($postData['registration_number']);
        $first_name = $this->validator->sanitizeInput($postData['first_name']);
        $last_name = $this->validator->sanitizeInput($postData['last_name']);
        $email = filter_var($postData['email'], FILTER_VALIDATE_EMAIL);
        $position = $this->validator->sanitizeInput($postData['position']);
        $phone_number = $this->validator->sanitizeInput($postData['phone_number']);
        $nic_number = $this->validator->sanitizeInput($postData['nic_number']);
        $hospital_id = filter_var($postData['hospital'], FILTER_VALIDATE_INT);

        $errors = $this->validateInputs($registration_number, $position, $nic_number, $phone_number, $email);

        if (empty($errors)) {
            try {
                $conn->begin_transaction();

                $password = $this->generateRandomPassword();
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmtUser = $conn->prepare("INSERT INTO users (username, password, roleID, createdate, modifieddate, active) VALUES (?, ?, ?, NOW(), NOW(), ?)");
                $stmtUser->bind_param("sssi", $username, $hashed_password, $roleID, $active);
                $stmtUser->execute();

                $userid = $conn->insert_id;

                $stmtHp = $conn->prepare("INSERT INTO healthcare_professionals (hpRegNo, userid, firstname, lastname, email, position, phonenumber, hpnic, hospitalid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmtHp->bind_param("ssssssssi", $registration_number, $userid, $first_name, $last_name, $email, $position, $phone_number, $nic_number, $hospital_id);
                $stmtHp->execute();

                $conn->commit();

                $success_message = "Account created successfully!<br>Here are the details:<br><br>";
                $success_message .= "First Name: " . htmlspecialchars($first_name) . "<br>";
                $success_message .= "Last Name: " . htmlspecialchars($last_name) . "<br>";
                $success_message .= "User Name: " . htmlspecialchars($username) . "<br>";
                $success_message .= "Email: " . htmlspecialchars($email) . "<br>";
                $success_message .= "Position: " . htmlspecialchars($position) . "<br>";
                $success_message .= "Registration Number: " . htmlspecialchars($registration_number) . "<br>";
                $success_message .= "NIC Number: " . htmlspecialchars($nic_number) . "<br>";
                $success_message .= "Hospital: " . htmlspecialchars($this->getHospitals()[$hospital_id]) . "<br>";
                $success_message .= "Contact Number: " . htmlspecialchars($phone_number) . "<br><br>";

                $this->emailSender->sendConfirmationEmail($email, $first_name, $username, $password);

                $stmtUser->close();
                $stmtHp->close();

                return ['success' => $success_message];
            } catch (Exception $e) {
                $conn->rollback();
                return ['error' => "Account creation failed: " . $e->getMessage()];
            }
        } else {
            return ['errors' => $errors];
        }
    }

    private function validateInputs($registration_number, $position, $nic_number, $phone_number, $email) {
        $errors = [];

        $position_prefix = [
            'ho' => 'HO',
            'mho' => 'MHO',
            'sho' => 'SHO'
        ];

        if (!preg_match('/^(' . $position_prefix[$position] . ')\d{5}$/', $registration_number)) {
            $errors[] = "Invalid registration number";
        }

        if (!$this->validator->validateNIC($nic_number)) {
            $errors[] = "Invalid NIC number.";
        }

        if (!preg_match('/^\d{10}$/', $phone_number)) {
            $errors[] = "Invalid phone number.";
        }

        if (!$email) {
            $errors[] = "Invalid email address.";
        }

        return $errors;
    }

    public function fetchHealthcareProfessional($registration_number) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM healthcare_professionals WHERE hpRegNo = ?");
        $stmt->bind_param("s", $registration_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $hpData = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        $this->db->close();
        return $hpData;
    }

    public function updateHealthcareProfessional($data) {
        $errors = $this->validateInputs($data['registration_number'], $data['position'], $data['nic_number'], $data['phone_number'], $data['email']);

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $conn = $this->db->getConnection();
        try {
            $conn->begin_transaction();
            $stmt = $conn->prepare("UPDATE healthcare_professionals SET firstname = ?, lastname = ?, email = ?, position = ?, phonenumber = ?, hpnic = ?, hospitalid = ? WHERE hpRegNo = ?");
            $stmt->bind_param("ssssssis", $data['first_name'], $data['last_name'], $data['email'], $data['position'], $data['phone_number'], $data['nic_number'], $data['hospital'], $data['registration_number']);
            $stmt->execute();
            $conn->commit();
            $stmt->close();
            return ['success' => "Healthcare professional account updated successfully!"];
        } catch (Exception $e) {
            $conn->rollback();
            return ['error' => "Account update failed: " . $e->getMessage()];
        } finally {
            $this->db->close();
        }
    }

    public function deleteHealthcareProfessional($registration_number) {
        $conn = $this->db->getConnection();
        try {
            $stmt = $conn->prepare("DELETE FROM healthcare_professionals WHERE hpRegNo = ?");
            $stmt->bind_param("s", $registration_number);
            $stmt->execute();
            $success = $stmt->affected_rows > 0 ? "Healthcare professional account deleted successfully!" : "No data found for the given registration number.";
            $stmt->close();
            return ['success' => $success];
        } catch (Exception $e) {
            return ['error' => "Account deletion failed: " . $e->getMessage()];
        } finally {
            $this->db->close();
        }
    }
}
?>
