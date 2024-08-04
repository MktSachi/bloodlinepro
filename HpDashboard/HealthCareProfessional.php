<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bloodlinepro/Classes/Database.php');

require '../Classes/Validator.php';
require '../Classes/Donor.php';


class HealthCareProfessional {
    private $db;
    private $conn;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->conn = $db->getConnection();
        $this->validator = new Validator();
    }
    public function registerDonor($data, $file_destination = '') {
        // Check if username already exists
        if ($this->checkUserName($data['username'])) {
            return "Username '{$data['username']}' already exists. Please choose a different username.";
        }

        // Check if NIC already exists
        if ($this->donorNICExists($data['donorNIC'])) {
            return "Donor NIC '{$data['donorNIC']}' already exists. Please use a different NIC.";
        }

        // Validate health conditions
        $healthConditions = [
            'hiv' => $data['hiv'] ?? 0,
            'heart_disease' => $data['heart_disease'] ?? 0,
            'diabetes' => $data['diabetes'] ?? 0,
            'fits' => $data['fits'] ?? 0,
            'paralysis' => $data['paralysis'] ?? 0,
            'lung_diseases' => $data['lung_diseases'] ?? 0,
            'liver_diseases' => $data['liver_diseases'] ?? 0,
            'kidney_diseases' => $data['kidney_diseases'] ?? 0,
            'blood_diseases' => $data['blood_diseases'] ?? 0,
            'cancer' => $data['cancer'] ?? 0
        ];

        if ($this->validator->validateHealthConditions($healthConditions)) {
            return "Sorry, you cannot register as a donor due to health conditions.";
        }

        // Generate a default password
        $password = bin2hex(random_bytes(4)); // 8 characters long
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $query = "INSERT INTO donors (firstName, lastName, donorNIC, username, email, password, phoneNumber, address, address2, gender, bloodType, otherHealthConditions, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            'sssssssssssss',
            $data['firstName'],
            $data['lastName'],
            $data['donorNIC'],
            $data['username'],
            $data['email'],
            $password_hashed,
            $data['phoneNumber'],
            $data['address'],
            $data['address2'],
            $data['gender'],
            $data['bloodType'],
            $data['otherHealthConditions'],
            $file_destination
        );

        if ($stmt->execute()) {
            $stmt->close();

            // Send confirmation email
            $emailSender = new EmailSender();
            $emailSender->sendConfirmationEmail($data['email'], $data['firstName'], $data['username'], $password);

            return "success";
        } else {
            $stmt->close();
            return "Error: Registration failed.";
        }
    }

    private function checkUserName($username) {
        $query = "SELECT * FROM donors WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows > 0;
    }

    private function donorNICExists($donorNIC) {
        $query = "SELECT * FROM donors WHERE donorNIC = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $donorNIC);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows > 0;
    }

    public function insertDonation($donorNIC, $hospitalID, $donatedBloodCount, $donationDate, $bloodExpiryDate) {
        $query = "INSERT INTO donations (donorNIC, hospitalID, donatedBloodCount, donationDate, bloodExpiryDate) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('siiss', $donorNIC, $hospitalID, $donatedBloodCount, $donationDate, $bloodExpiryDate);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function updateDonorDonationCount($donorNIC) {
        $query = "UPDATE donors SET donation_count = donation_count + 1 WHERE donorNIC = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $donorNIC);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function updateHospitalBloodInventory($hospitalID, $bloodType, $donatedBloodCount) {
        $selectQuery = "SELECT * FROM hospital_blood_inventory WHERE hospitalID = ? AND bloodType = ?";
        $selectStmt = $this->conn->prepare($selectQuery);
        $selectStmt->bind_param('is', $hospitalID, $bloodType);
        $selectStmt->execute();
        $result = $selectStmt->get_result();

        if ($result->num_rows > 0) {
            $updateQuery = "UPDATE hospital_blood_inventory SET quantity = quantity + ? WHERE hospitalID = ? AND bloodType = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bind_param('iis', $donatedBloodCount, $hospitalID, $bloodType);
        } else {
            $updateQuery = "INSERT INTO hospital_blood_inventory (hospitalID, bloodType, quantity) VALUES (?, ?, ?)";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bind_param('isi', $hospitalID, $bloodType, $donatedBloodCount);
        }

        if ($updateStmt->execute()) {
            $updateStmt->close();
            return true;
        } else {
            $updateStmt->close();
            return false;
        }
    }

    public function processDonation($donorNIC, $hospitalID, $donatedBloodCount, $bloodType) {
        $donationDate = date('Y-m-d');
        $bloodExpiryDate = date('Y-m-d', strtotime($donationDate . ' + 40 days'));

        $this->conn->begin_transaction();

        try {
            if (!$this->insertDonation($donorNIC, $hospitalID, $donatedBloodCount, $donationDate, $bloodExpiryDate)) {
                throw new Exception("Failed to insert donation");
            }

            if (!$this->updateDonorDonationCount($donorNIC)) {
                throw new Exception("Failed to update donor donation count");
            }

            if (!$this->updateHospitalBloodInventory($hospitalID, $bloodType, $donatedBloodCount)) {
                throw new Exception("Failed to update hospital blood inventory");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}