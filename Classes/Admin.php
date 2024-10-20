<?php
include_once 'Database.php';
include_once 'Validator.php';
include_once 'CreatehpEmail.php';

class Admin
{
    private $db;
    private $validator;
    private $emailSender;

    public function __construct()
    {
        $this->db = new Database();
        $this->validator = new Validator();
        $this->emailSender = new EmailSender();
    }

    public function updateAdminPassword($currentPassword, $newPassword)
    {
        $conn = $this->db->getConnection();

        // Fetch the current admin's data from both users and admin tables
        $stmt = $conn->prepare("
            SELECT u.userid, u.password, a.email 
            FROM users u
            JOIN admin a ON u.userid = a.userid
            WHERE u.roleID = 'admin' 
            LIMIT 1
        ");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['error' => 'Admin user not found.'];
        }

        $admin = $result->fetch_assoc();
        $stmt->close();

        // Verify current password
        if (!password_verify($currentPassword, $admin['password'])) {
            return ['error' => 'Current password is incorrect.'];
        }

        // Validate new password
        if (!$this->validator->isValidPassword($newPassword)) {
            return ['error' => 'New password does not meet the required criteria.'];
        }

        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the users table
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, modifieddate = NOW() WHERE userid = ?");
        $updateStmt->bind_param("si", $hashedNewPassword, $admin['userid']);

        if ($updateStmt->execute()) {
            $updateStmt->close();
            $this->db->close();

            // Send email notification
            $this->emailSender->sendPasswordChangeNotification($admin['email']);

            return ['success' => 'Password updated successfully.'];
        } else {
            $updateStmt->close();
            $this->db->close();
            return ['error' => 'Failed to update password. Please try again.'];
        }
    }

    public function generateRandomPassword($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }

    public function getHospitals()
    {
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

    public function createHealthcareProfessional($postData)
    {
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

        // Check if NIC number already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM healthcare_professionals WHERE hpnic = ?");
        $stmt->bind_param("s", $nic_number);
        $stmt->execute();
        $stmt->bind_result($nic_count);
        $stmt->fetch();
        $stmt->close();

        if ($nic_count > 0) {
            $errors[] = "The NIC number already exists in the system.";
        }

        // Check if registration number already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM healthcare_professionals WHERE hpRegNo = ?");
        $stmt->bind_param("s", $registration_number);
        $stmt->execute();
        $stmt->bind_result($reg_count);
        $stmt->fetch();
        $stmt->close();

        if ($reg_count > 0) {
            $errors[] = "The registration number already exists in the system.";
        }

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

                $this->emailSender->sendConfirmationEmail($email, $first_name, $username, $password);

                $stmtUser->close();
                $stmtHp->close();

                header("Location: ../HpDashboard/CreateDonor/Success.php");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                return ['error' => "Account creation failed: " . $e->getMessage()];
            }
        } else {
            return ['errors' => $errors];
        }
    }

    private function validateInputs($registration_number, $position, $nic_number, $phone_number, $email)
    {
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

    public function fetchHealthcareProfessional($registration_number)
    {
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

    public function updateHealthcareProfessional($data)
    {
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

    public function deleteHealthcareProfessional($registration_number)
    {
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