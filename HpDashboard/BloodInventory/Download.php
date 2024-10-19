<?php
session_start();
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Hospital Donations Report', 0, 1, 'C');
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
    if (isset($_SESSION['donations']) && !empty($_SESSION['donations'])) {
        $data = $_SESSION['donations'];

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Table Header
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 7, 'Donor Name', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Donor NIC', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Blood Type', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Donated Count', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Donation Date', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Expiry Date', 1, 1, 'C', true); // Added 1 to end the line

        // Table data
        $pdf->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            $pdf->Cell(50, 6, $row['first_name'] . ' ' . $row['last_name'], 1);
            $pdf->Cell(30, 6, $row['donorNIC'], 1);
            $pdf->Cell(25, 6, $row['bloodType'], 1);
            $pdf->Cell(30, 6, $row['donatedBloodCount'], 1);
            $pdf->Cell(30, 6, date('Y-m-d', strtotime($row['donationDate'])), 1);
            $pdf->Cell(30, 6, date('Y-m-d', strtotime($row['bloodExpiryDate'])), 1);
            $pdf->Ln(); // Move to the next line
        }

        $pdf->Output('D', 'donation_report.pdf');
    } else {
        echo "No data available to download.";
    }
} else {
    echo "Invalid download request.";
}
?>
