<?php
session_start();
require '../../Classes/Database.php';
require 'Inventory.php';
require('fpdf/fpdf.php');

if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    if (isset($_SESSION['donations'])) {
        $data = $_SESSION['donations'];
        
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Hospital Donations Report', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Table Header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Donor Name', 1);
        $pdf->Cell(30, 10, 'Donor NIC', 1);
        $pdf->Cell(30, 10, 'Blood Count', 1);
        $pdf->Cell(40, 10, 'Donation Date', 1);
        $pdf->Cell(40, 10, 'Expiry Date', 1);
        $pdf->Ln();
        
        // Table data
        $pdf->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            $pdf->Cell(50, 10, $row['first_name'] . ' ' . $row['last_name'], 1);
            $pdf->Cell(30, 10, $row['donorNIC'], 1);
            $pdf->Cell(30, 10, $row['donatedBloodCount'], 1);
            $pdf->Cell(40, 10, $row['donationDate'], 1);
            $pdf->Cell(40, 10, $row['bloodExpiryDate'], 1);
            $pdf->Ln();
        }
        
        $pdf->Output('D', 'donation_report.pdf');
    } else {
        echo "No data available to download.";
    }
} else {
    echo "Invalid download request.";
}