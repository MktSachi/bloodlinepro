<?php
$root = $_SERVER['DOCUMENT_ROOT'] . '/bloodlinepro/';
require_once $root . 'Classes/Database.php';

/**
 * Inventory Class
 * Handles operations related to blood inventory and hospital-related data.
 */
class Inventory {
    private $conn;

    /**
     * Constructor to initialize database connection.
     * 
     * @param object $dbConnection Database connection object.
     */
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    /**
     * Get the blood inventory for a specific hospital associated with a username.
     * 
     * @param string $username The username of the healthcare professional.
     * @return array The blood inventory data and total units.
     */
    public function getBloodInventory($username) {
        $bloodInventory = [];
        $totalUnits = 0;

        $query = "
            SELECT h.hospitalID, hbi.bloodType, hbi.quantity 
            FROM healthcare_professionals hp 
            JOIN users u ON hp.userid = u.userid
            JOIN hospitals h ON hp.hospitalID = h.hospitalID
            LEFT JOIN hospital_blood_inventory hbi ON h.hospitalID = hbi.hospitalID
            WHERE u.username = ?
        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $bloodType = $row['bloodType'];
                $quantity = $row['quantity'];
                $bloodInventory[$bloodType] = $quantity;
                $totalUnits += $quantity;
            }

            $stmt->close();
        } else {
            // Error handling
            die('Database query failed: ' . $this->conn->error);
        }

        return ['inventory' => $bloodInventory, 'totalUnits' => $totalUnits];
    }

    /**
     * Get the donation report for a specific hospital within a date range.
     * 
     * @param string $username The username of the healthcare professional.
     * @param string $startDate The start date for the report.
     * @param string $endDate The end date for the report.
     * @return array The donation report data.
     */
    public function getDonationReport($username, $startDate, $endDate) {
        $donations = [];

        $query = "
            SELECT d.id, d.donorNIC, d.donatedBloodCount, d.donationDate, d.bloodExpiryDate, 
                   dn.first_name, dn.last_name
            FROM donations d
            JOIN donors dn ON d.donorNIC = dn.donorNIC
            JOIN healthcare_professionals hp ON d.hospitalID = hp.hospitalID
            JOIN users u ON hp.userid = u.userid
            WHERE u.username = ? AND d.donationDate BETWEEN ? AND ?
        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('sss', $username, $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $donations[] = $row;
            }

            $stmt->close();
        } else {
            // Error handling
            die('Database query failed: ' . $this->conn->error);
        }

        return $donations;
    }

    /**
     * Generate an HTML file for download with donation report data.
     * 
     * @param array $data The donation report data.
     */
    public function generateHTMLDownload($data) {
        $filename = 'hospital_donations_report_' . date('Y-m-d') . '.html';
        $htmlContent = '
            <html>
            <head>
                <title>Hospital Donations Report</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container">
                    <h1>Hospital Donations Report</h1>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Donor Name</th>
                                <th>Donor NIC</th>
                                <th>Donated Blood Count</th>
                                <th>Donation Date</th>
                                <th>Blood Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
        ';

        foreach ($data as $row) {
            $htmlContent .= '
                <tr>
                    <td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>
                    <td>' . htmlspecialchars($row['donorNIC']) . '</td>
                    <td>' . htmlspecialchars($row['donatedBloodCount']) . '</td>
                    <td>' . htmlspecialchars($row['donationDate']) . '</td>
                    <td>' . htmlspecialchars($row['bloodExpiryDate']) . '</td>
                </tr>
            ';
        }

        $htmlContent .= '
                        </tbody>
                    </table>
                </div>
            </body>
            </html>
        ';

        file_put_contents($filename, $htmlContent);

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);
        unlink($filename);
        exit;
    }

    /**
     * Get blood distribution data for a specific hospital.
     * 
     * @param string $hospitalName The name of the hospital.
     * @return array The blood distribution data and hospital details.
     */
    public function getHospitalBloodDistribution($hospitalName) {
        $hospitalName = $this->conn->real_escape_string($hospitalName);
        $data = [
            'bloodTypeData' => [],
            'hospitalDetails' => [],
            'totalUnits' => 0
        ];

        // Fetch blood type distribution
        $queryBloodType = "
            SELECT bloodType, quantity 
            FROM hospital_blood_inventory hbi 
            JOIN hospitals h ON hbi.hospitalID = h.hospitalID 
            WHERE h.hospitalName = ?
        ";
        if ($stmt = $this->conn->prepare($queryBloodType)) {
            $stmt->bind_param('s', $hospitalName);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $data['bloodTypeData'][$row['bloodType']] = $row['quantity'];
            }

            $stmt->close();
        } else {
            // Error handling
            die('Database query failed: ' . $this->conn->error);
        }

        // Fetch hospital contact details
        $queryHospitalDetails = "
            SELECT * 
            FROM hospitals 
            WHERE hospitalName = ?
        ";
        if ($stmt = $this->conn->prepare($queryHospitalDetails)) {
            $stmt->bind_param('s', $hospitalName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data['hospitalDetails'] = $result->fetch_assoc();
            }

            $stmt->close();
        } else {
            // Error handling
            die('Database query failed: ' . $this->conn->error);
        }

        // Fetch total blood units
        $queryTotalUnits = "
            SELECT SUM(quantity) AS total 
            FROM hospital_blood_inventory 
            WHERE hospitalID = (SELECT hospitalID FROM hospitals WHERE hospitalName = ?)
        ";
        if ($stmt = $this->conn->prepare($queryTotalUnits)) {
            $stmt->bind_param('s', $hospitalName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $data['totalUnits'] = $row['total'];
            }

            $stmt->close();
        } else {
            // Error handling
            die('Database query failed: ' . $this->conn->error);
        }

        return $data;
    }

    public function getBloodUsageReport($hospitalID, $startDate, $endDate) {
        $query = "
            SELECT 
                bu.senderHospitalID, 
                sh.hospitalName AS senderHospitalName, 
                bu.receiverHospitalID, 
                rh.hospitalName AS receiverHospitalName, 
                bu.bloodType, 
                bu.bloodQuantity, 
                bu.transferDate, 
                bu.description
            FROM blood_usage bu
            JOIN hospitals sh ON bu.senderHospitalID = sh.hospitalID
            JOIN hospitals rh ON bu.receiverHospitalID = rh.hospitalID
            WHERE bu.transferDate BETWEEN ? AND ? AND (bu.senderHospitalID = ? OR bu.receiverHospitalID = ?)
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssii', $startDate, $endDate, $hospitalID, $hospitalID);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }

    public function generateHTMLDownloadUsage($data) {
        $filename = 'blood_usage_report_' . date('Y-m-d') . '.html';
        $htmlContent = '
            <html>
            <head>
                <title>Blood Usage Report</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container mt-4">
                    <h1>Blood Usage Report</h1>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sender Hospital</th>
                                <th>Receiver Hospital</th>
                                <th>Blood Type</th>
                                <th>Blood Quantity</th>
                                <th>Transfer Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
        ';
    
        foreach ($data as $row) {
            $htmlContent .= '
                <tr>
                    <td>' . htmlspecialchars($row['senderHospitalName']) . '</td>
                    <td>' . htmlspecialchars($row['receiverHospitalName']) . '</td>
                    <td>' . htmlspecialchars($row['bloodType']) . '</td>
                    <td>' . htmlspecialchars($row['bloodQuantity']) . '</td>
                    <td>' . htmlspecialchars($row['transferDate']) . '</td>
                    <td>' . htmlspecialchars($row['description']) . '</td>
                </tr>
            ';
        }
    
        $htmlContent .= '
                        </tbody>
                    </table>
                </div>
            </body>
            </html>
        ';
    
        file_put_contents($filename, $htmlContent);
    
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);
        unlink($filename);
        exit;
    }
    
    public function getHospitals() {
        $hospitals = [];
        $query = "SELECT hospitalID, hospitalName FROM hospitals";
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hospitals[] = $row;
            }
        }
        $result->free();
        return $hospitals;
    }

    public function transferBlood($senderHospitalID, $receiverHospitalID, $bloodType, $bloodQuantity, $description) {
        if ($senderHospitalID == $receiverHospitalID) {
            return 'Sender and receiver hospitals cannot be the same.';
        }
    
        // Check for negative or zero quantity
        if ($bloodQuantity <= 0) {
            return 'Error: Blood quantity must be a positive number.';
        }
        $conn = $this->conn;
        $conn->begin_transaction();
    
        try {
            // Check if sender hospital has sufficient quantity
            $checkSenderInventoryQuery = "SELECT quantity FROM hospital_blood_inventory WHERE hospitalID = ? AND bloodType = ?";
            $checkSenderInventoryStmt = $conn->prepare($checkSenderInventoryQuery);
            $checkSenderInventoryStmt->bind_param('is', $senderHospitalID, $bloodType);
            $checkSenderInventoryStmt->execute();
            $result = $checkSenderInventoryStmt->get_result();
            
            if ($result->num_rows === 0) {
                throw new Exception('Sender hospital does not have the specified blood type in inventory.');
            }
            
            $row = $result->fetch_assoc();
            $currentQuantity = $row['quantity'];
            
            if ($currentQuantity < $bloodQuantity) {
                throw new Exception('Insufficient blood quantity in sender hospital inventory.');
            }
            
            $checkSenderInventoryStmt->close();
    
            // Update sender hospital blood inventory
            $updateSenderInventoryQuery = "UPDATE hospital_blood_inventory SET quantity = quantity - ? WHERE hospitalID = ? AND bloodType = ?";
            $updateSenderInventoryStmt = $conn->prepare($updateSenderInventoryQuery);
            $updateSenderInventoryStmt->bind_param('iis', $bloodQuantity, $senderHospitalID, $bloodType);
            $updateSenderInventoryStmt->execute();
    
            if ($updateSenderInventoryStmt->affected_rows === 0) {
                throw new Exception('Failed to update sender hospital inventory.');
            }
            $updateSenderInventoryStmt->close();
    
            // Check receiver hospital blood inventory
            $selectReceiverInventoryQuery = "SELECT * FROM hospital_blood_inventory WHERE hospitalID = ? AND bloodType = ?";
            $selectReceiverInventoryStmt = $conn->prepare($selectReceiverInventoryQuery);
            $selectReceiverInventoryStmt->bind_param('is', $receiverHospitalID, $bloodType);
            $selectReceiverInventoryStmt->execute();
            $resultReceiver = $selectReceiverInventoryStmt->get_result();
    
            if ($resultReceiver->num_rows > 0) {
                // Update receiver hospital blood inventory
                $updateReceiverInventoryQuery = "UPDATE hospital_blood_inventory SET quantity = quantity + ? WHERE hospitalID = ? AND bloodType = ?";
                $updateReceiverInventoryStmt = $conn->prepare($updateReceiverInventoryQuery);
                $updateReceiverInventoryStmt->bind_param('iis', $bloodQuantity, $receiverHospitalID, $bloodType);
            } else {
                // Insert new record for receiver hospital
                $insertReceiverInventoryQuery = "INSERT INTO hospital_blood_inventory (hospitalID, bloodType, quantity) VALUES (?, ?, ?)";
                $updateReceiverInventoryStmt = $conn->prepare($insertReceiverInventoryQuery);
                $updateReceiverInventoryStmt->bind_param('isi', $receiverHospitalID, $bloodType, $bloodQuantity);
            }
    
            $updateReceiverInventoryStmt->execute();
            $updateReceiverInventoryStmt->close();
            $selectReceiverInventoryStmt->close();
    
            // Log the transfer
            $insertBloodUsageQuery = "INSERT INTO blood_usage (senderHospitalID, receiverHospitalID, bloodType, bloodquantity, description, transferDate) VALUES (?, ?, ?, ?, ?, NOW())";
            $insertBloodUsageStmt = $conn->prepare($insertBloodUsageQuery);
            $insertBloodUsageStmt->bind_param('iisis', $senderHospitalID, $receiverHospitalID, $bloodType, $bloodQuantity, $description);
            $insertBloodUsageStmt->execute();
            $insertBloodUsageStmt->close();
    
            // Commit transaction
            $conn->commit();
            return 'Blood transfer was successful!';
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            return 'Error: ' . $e->getMessage();
        }
    }
    public function getAvailableBloodGroups($hospitalID) {
        $bloodGroups = [];
    
        $query = "
            SELECT bloodType 
            FROM hospital_blood_inventory 
            WHERE hospitalID = ? AND quantity > 0
        ";
    
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $hospitalID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                $bloodGroups[] = $row['bloodType'];
            }
    
            $stmt->close();
        } else {
            die('Database query failed: ' . $this->conn->error);
        }
    
        return $bloodGroups;
    }
    public function getHospitalBloodInventory($hospitalID) {
        $bloodInventory = [];
        $totalUnits = 0;

        $query = "
            SELECT bloodType, quantity 
            FROM hospital_blood_inventory 
            WHERE hospitalID = ?
        ";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param('i', $hospitalID);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $bloodType = $row['bloodType'];
                $quantity = $row['quantity'];
                $bloodInventory[$bloodType] = $quantity;
                $totalUnits += $quantity;
            }

            $stmt->close();
        } else {
            die('Database query failed: ' . $this->conn->error);
        }

        return ['inventory' => $bloodInventory, 'totalUnits' => $totalUnits];
    }
    
    public function getPatientReport($startDate, $endDate) {
        $query = "
            SELECT 
                bloodType, 
                COUNT(patientID) AS patientCount, 
                SUM(bloodQuantity) AS totalBloodUsed
            FROM 
                patients
            WHERE 
                admissionDate BETWEEN ? AND ?
            GROUP BY 
                bloodType
            ORDER BY 
                bloodType ASC
        ";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }

        // Bind parameters
        $stmt->bind_param('ss', $startDate, $endDate);
        
        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . $stmt->error);
        }

        
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error fetching results: " . $stmt->error);
        }

        
        $reportData = $result->fetch_all(MYSQLI_ASSOC);
        
        
        $result->free();
        $stmt->close();
        
        return $reportData;
    }

}
?>