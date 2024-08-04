<?php
$root = $_SERVER['DOCUMENT_ROOT'] . '/bloodlinepro/';
require_once $root . 'DonorRegistration/Database.php';

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
    
}
?>