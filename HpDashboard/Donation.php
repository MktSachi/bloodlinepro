<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bloodlinepro/Classes/Database.php');


class Donation {
    private $db;
    private $conn;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->conn = $db->getConnection();
        
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
    
        // Validate donated blood count
        if ($donatedBloodCount <= 0) {
            return "Invalid blood count. Please enter a positive number.";
        }
    
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
            return "Error: " . $e->getMessage();
        }
    }
}