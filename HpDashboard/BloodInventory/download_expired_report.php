<?php
session_start();

require '../../Classes/Database.php';
require '../Donation.php';
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Expired Blood Donations Report', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    $hospitalID = isset($_SESSION['hospitalID']) ? $_SESSION['hospitalID'] : null;
    
    if (!$hospitalID) {
        die("Hospital ID not found in session. Please login again.");
    }

    $db = new Database();
    $donationManager = new Donation($db);

    $selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $result = $donationManager->getExpiredDonationsByHospitalAndDate($hospitalID, $selectedDate);
    
    if ($result['success']) {
        $expiredDonations = $result['data'];

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Expired Blood Donations as of ' . date('d M Y', strtotime($selectedDate)), 0, 1);
        $pdf->Ln(5);

        // Table Header
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(25, 7, 'Donation ID', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Donor NIC', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Blood Type', 1, 0, 'C', true);
        $pdf->Cell(20, 7, 'Amount', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Donation Date', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Expiry Date', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Status', 1, 1, 'C', true);

        // Table data
        $pdf->SetFont('Arial', '', 9);
        foreach ($expiredDonations as $donation) {
            $pdf->Cell(25, 6, $donation['donation_id'], 1);
            $pdf->Cell(30, 6, $donation['donorNIC'], 1);
            $pdf->Cell(25, 6, $donation['bloodType'], 1, 0, 'C');
            $pdf->Cell(20, 6, $donation['donatedBloodCount'] . ' units', 1, 0, 'C');
            $pdf->Cell(30, 6, date('Y-m-d', strtotime($donation['donationDate'])), 1, 0, 'C');
            $pdf->Cell(30, 6, date('Y-m-d', strtotime($donation['bloodExpiryDate'])), 1, 0, 'C');
            
            $daysExpired = floor((strtotime($selectedDate) - strtotime($donation['bloodExpiryDate'])) / (60 * 60 * 24));
            $status = $daysExpired > 7 ? 'Expired >7 days' : 'Expired';
            $pdf->Cell(25, 6, $status, 1, 1, 'C');
        }

        $pdf->Output('D', 'expired_blood_report.pdf');
    } else {
        echo "Error: " . $result['error'];
    }
} else {
    echo "Invalid download request.";
}