<?php
require_once 'Database.php';
require_once 'BloodReqEmail.php'; // Ensure the EmailSender class is included

class BloodRequest {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getHospitals() {
        $hospitals = [];
        $query = "SELECT hospitalID, hospitalName FROM hospitals";
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hospitals[$row['hospitalID']] = $row['hospitalName'];
            }
        }
        return $hospitals;
    }

    public function getAvailableBloodGroups($hospitalID) {
        $bloodGroups = [];
        $query = "SELECT DISTINCT bloodType FROM hospital_blood_inventory WHERE hospitalID = ? AND quantity > 0";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $hospitalID);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $bloodGroups[] = $row['bloodType'];
            }
            $stmt->close();
        }
        return $bloodGroups;
    }

    public function processBloodRequest($donatingHospitalID, $bloodType, $quantity, $username) {
        // Fetch the hpRegNo and requesting hospital ID based on the username
        $query = "
            SELECT hp.hpRegNo, hp.hospitalid
            FROM users u
            JOIN healthcare_professionals hp ON u.userid = hp.userid
            WHERE u.username = ?
        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($hpRegNo, $requestingHospitalID);
            $stmt->fetch();
            $stmt->close();
            
            if (empty($hpRegNo)) {
                echo "No hpRegNo found for username: $username<br>";
            }
            if (empty($requestingHospitalID)) {
                echo "No requesting hospital ID found for username: $username<br>";
            }
        } else {
            die("Failed to prepare SQL SELECT statement: " . $this->conn->error);
        }

        // Fetch the email of the donating hospital
        $query = "SELECT email, hospitalName FROM hospitals WHERE hospitalID = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $donatingHospitalID);
            $stmt->execute();
            $stmt->bind_result($donatingHospitalEmail, $donatingHospitalName);
            $stmt->fetch();
            $stmt->close();
            
            if (empty($donatingHospitalEmail)) {
                echo "No email found for donating hospital ID: $donatingHospitalID<br>";
                return false;
            }
        } else {
            die("Failed to prepare SQL SELECT statement: " . $this->conn->error);
        }

        // Fetch the requesting hospital name
        $query = "SELECT hospitalName FROM hospitals WHERE hospitalID = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $requestingHospitalID);
            $stmt->execute();
            $stmt->bind_result($requestingHospitalName);
            $stmt->fetch();
            $stmt->close();
        } else {
            die("Failed to prepare SQL SELECT statement: " . $this->conn->error);
        }

        // Insert the blood request into the blood_requests table
        $query = "INSERT INTO blood_requests (DonatingHospitalID, RequestingHospitalID, bloodType, requestedQuantity) VALUES (?, ?, ?, ?)";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('iisi', $donatingHospitalID, $requestingHospitalID, $bloodType, $quantity);
            if ($stmt->execute()) {
                echo "Data saved successfully!<br>";
                
                // Send confirmation email to the donating hospital
                $emailSender = new EmailSender();
                $emailSender->sendConfirmationEmail(
                    $donatingHospitalEmail, // Recipient email (donating hospital)
                    $donatingHospitalName, // Donating hospital name
                    $requestingHospitalName, // Requesting hospital name
                    $bloodType, // Blood type
                    $quantity // Requested quantity
                );
                return true;
            } else {
                echo "Error inserting data: " . $stmt->error . "<br>";
                return false;
            }
            $stmt->close();
        } else {
            die("Failed to prepare SQL INSERT statement: " . $this->conn->error);
        }
    }

   

    public function updateHospitalBloodInventory($requestID) {
        // Fetch the details of the blood request
        $query = "
            SELECT DonatingHospitalID, RequestingHospitalID, bloodType, requestedQuantity 
            FROM blood_requests 
            WHERE requestID = ?
        ";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $requestID);
            $stmt->execute();
            $stmt->bind_result($donatingHospitalID, $requestingHospitalID, $bloodType, $requestedQuantity);
            $stmt->fetch();
            $stmt->close();
        } else {
            die("Failed to fetch blood request details: " . $this->conn->error);
        }
    
        // Reduce the blood quantity from the donating hospital
        $query = "
            UPDATE hospital_blood_inventory 
            SET quantity = quantity - ? 
            WHERE hospitalID = ? AND bloodType = ? AND quantity >= ?
        ";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('iisi', $requestedQuantity, $donatingHospitalID, $bloodType, $requestedQuantity);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                echo "Error: Donating hospital does not have enough blood in stock.";
                return false;
            }
            $stmt->close();
        } else {
            die("Failed to update donating hospital blood inventory: " . $this->conn->error);
        }
    
        // Increase the blood quantity in the requesting hospital
        $query = "
            UPDATE hospital_blood_inventory 
            SET quantity = quantity + ? 
            WHERE hospitalID = ? AND bloodType = ?
        ";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('iis', $requestedQuantity, $requestingHospitalID, $bloodType);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                // If no row is updated, it means the blood type does not exist in the requesting hospital, so we need to insert it.
                $insertQuery = "
                    INSERT INTO hospital_blood_inventory (hospitalID, bloodType, quantity) 
                    VALUES (?, ?, ?)
                ";
                if ($insertStmt = $this->conn->prepare($insertQuery)) {
                    $insertStmt->bind_param('isi', $requestingHospitalID, $bloodType, $requestedQuantity);
                    $insertStmt->execute();
                    $insertStmt->close();
                } else {
                    die("Failed to insert blood inventory for requesting hospital: " . $this->conn->error);
                }
            }
            $stmt->close();
        } else {
            die("Failed to update requesting hospital blood inventory: " . $this->conn->error);
        }
    
        // Update the request status to 'Completed'
        $this->updateRequestStatus($requestID, 'Completed');
        
        echo "Blood inventory updated successfully!";
        return true;
    }
    
    public function getBloodRequests($username) {
        $bloodRequests = [];
        
        // Fetch the HP's hospital ID based on the username
        $query = "
            SELECT hp.hospitalid
            FROM users u
            JOIN healthcare_professionals hp ON u.userid = hp.userid
            WHERE u.username = ?
        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($hpHospitalID);
            $stmt->fetch();
            $stmt->close();

            // Check if HP's hospital ID is found
            if (!empty($hpHospitalID)) {
                // Fetch blood requests where requesting hospital matches HP's hospital
                $query = "
                    SELECT br.requestID, hr.hospitalName AS requestingHospital, br.bloodType, br.requestedQuantity, br.status
                    FROM blood_requests br
                    JOIN hospitals hr ON br.RequestingHospitalID = hr.hospitalID
                    WHERE br.DonatingHospitalID = ?
                ";
                if ($stmt = $this->conn->prepare($query)) {
                    $stmt->bind_param('i', $hpHospitalID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $bloodRequests[] = $row;
                    }
                    $stmt->close();
                }
            }
        }
        return $bloodRequests;
    }
    
    public function getAllBloodRequests() {
        $requests = [];
        $query = "
            SELECT 
                br.requestID, 
                h_donating.hospitalName AS donatingHospital,
                h_requesting.hospitalName AS requestingHospital,
                br.bloodType, 
                br.requestedQuantity, 
                br.requestDate, 
                br.status
            FROM blood_requests br
            JOIN hospitals h_donating ON br.DonatingHospitalID = h_donating.hospitalID
            JOIN hospitals h_requesting ON br.RequestingHospitalID = h_requesting.hospitalID
            ORDER BY br.requestDate ASC
        ";

        if ($result = $this->conn->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $requests[] = $row;
            }
            $result->free();
        } else {
            die("Error fetching blood requests: " . $this->conn->error);
        }

        return $requests;
    }

    public function getBloodRequestsByDate($requestDate) {
        $requests = [];
        $query = "
            SELECT 
                br.requestID, 
                h_donating.hospitalName AS donatingHospital,
                h_requesting.hospitalName AS requestingHospital,
                br.bloodType, 
                br.requestedQuantity, 
                br.requestDate, 
                br.status
            FROM blood_requests br
            JOIN hospitals h_donating ON br.DonatingHospitalID = h_donating.hospitalID
            JOIN hospitals h_requesting ON br.RequestingHospitalID = h_requesting.hospitalID
            WHERE DATE(br.requestDate) = ?
            ORDER BY br.requestDate ASC
        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $requestDate);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $requests[] = $row;
            }
            $stmt->close();
        } else {
            die("Error preparing query: " . $this->conn->error);
        }

        return $requests;
    }

    public function deleteRequestByID($requestID) {
        $query = "DELETE FROM blood_requests WHERE requestID = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $requestID);
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                return false;
            }
        } else {
            die("Failed to prepare statement: " . $this->conn->error);
        }
    }
    

}
?>
