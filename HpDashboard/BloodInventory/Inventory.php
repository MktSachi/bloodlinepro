<?php
include_once '../../DonorRegistration/Database.php';
class Inventory {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getBloodInventory($username) {
        $bloodInventory = [];
        $totalUnits = 0;

        $queryHpHospital = "SELECT h.hospitalID FROM healthcare_professionals hp 
                            JOIN users u ON hp.userid = u.userid
                            JOIN hospitals h ON hp.hospitalID = h.hospitalID
                            WHERE u.username = ?";
        $stmtHpHospital = $this->conn->prepare($queryHpHospital);
        $stmtHpHospital->bind_param('s', $username);
        $stmtHpHospital->execute();
        $resultHpHospital = $stmtHpHospital->get_result();

        if ($resultHpHospital->num_rows > 0) {
            $hpHospital = $resultHpHospital->fetch_assoc();
            $hospitalID = $hpHospital['hospitalID'];

            $queryHospitalBlood = "SELECT bloodType, quantity FROM hospital_blood_inventory 
                                  WHERE hospitalID = ?";
            $stmtHospitalBlood = $this->conn->prepare($queryHospitalBlood);
            $stmtHospitalBlood->bind_param('i', $hospitalID);
            $stmtHospitalBlood->execute();
            $resultHospitalBlood = $stmtHospitalBlood->get_result();

            while ($row = $resultHospitalBlood->fetch_assoc()) {
                $bloodType = $row['bloodType'];
                $quantity = $row['quantity'];
                $bloodInventory[$bloodType] = $quantity;
                $totalUnits += $quantity;
            }

            $stmtHospitalBlood->close();
        }

        $stmtHpHospital->close();
        return ['inventory' => $bloodInventory, 'totalUnits' => $totalUnits];
    }

    public function getDonationReport($username, $startDate, $endDate) {
        $donations = [];

        $queryHpHospital = "SELECT h.hospitalID FROM healthcare_professionals hp 
                            JOIN users u ON hp.userid = u.userid
                            JOIN hospitals h ON hp.hospitalID = h.hospitalID
                            WHERE u.username = ?";
        $stmtHpHospital = $this->conn->prepare($queryHpHospital);
        $stmtHpHospital->bind_param('s', $username);
        $stmtHpHospital->execute();
        $resultHpHospital = $stmtHpHospital->get_result();

        if ($resultHpHospital->num_rows > 0) {
            $hpHospital = $resultHpHospital->fetch_assoc();
            $hospitalID = $hpHospital['hospitalID'];

            $queryDonations = "SELECT d.id, d.donorNIC, d.donatedBloodCount, d.donationDate, d.bloodExpiryDate, 
                                       dn.first_name, dn.last_name
                                FROM donations d
                                JOIN donors dn ON d.donorNIC = dn.donorNIC
                                WHERE d.hospitalID = ? AND d.donationDate BETWEEN ? AND ?";
            $stmtDonations = $this->conn->prepare($queryDonations);
            $stmtDonations->bind_param('iss', $hospitalID, $startDate, $endDate);
            $stmtDonations->execute();
            $resultDonations = $stmtDonations->get_result();

            while ($row = $resultDonations->fetch_assoc()) {
                $donations[] = $row;
            }

            $stmtDonations->close();
        }

        $stmtHpHospital->close();
        return $donations;
    }

    public function generateHTMLDownload($data) {
        $filename = 'hospital_donations_report_' . date('Y-m-d') . '.html';
        $htmlContent = '<html><head><title>Hospital Donations Report</title>';
        $htmlContent .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
        $htmlContent .= '</head><body><div class="container"><h1>Hospital Donations Report</h1>';
        $htmlContent .= '<table class="table table-bordered"><thead><tr>';
        $htmlContent .= '<th>Donor Name</th><th>Donor NIC</th><th>Donated Blood Count</th><th>Donation Date</th><th>Blood Expiry Date</th>';
        $htmlContent .= '</tr></thead><tbody>';

        foreach ($data as $row) {
            $htmlContent .= '<tr>';
            $htmlContent .= '<td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>';
            $htmlContent .= '<td>' . htmlspecialchars($row['donorNIC']) . '</td>';
            $htmlContent .= '<td>' . htmlspecialchars($row['donatedBloodCount']) . '</td>';
            $htmlContent .= '<td>' . htmlspecialchars($row['donationDate']) . '</td>';
            $htmlContent .= '<td>' . htmlspecialchars($row['bloodExpiryDate']) . '</td>';
            $htmlContent .= '</tr>';
        }

        $htmlContent .= '</tbody></table></div></body></html>';

        file_put_contents($filename, $htmlContent);

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);
        unlink($filename);
        exit;
    }
}
?>
