<?php
session_start();

require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';
require_once '../HpDashboard/BloodInventory/fpdf/fpdf.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Blood Requests Report', 0, 1, 'C');
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
    $requestDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Initialize database connection
    $db = new Database();
    $conn = $db->getConnection();

    // Create an instance of BloodRequest
    $bloodRequest = new BloodRequest($conn);

    // Get blood requests for the specified date
    $requests = $bloodRequest->getBloodRequestsByDate($requestDate);

    if (!empty($requests)) {
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Blood Requests for ' . date('d M Y', strtotime($requestDate)), 0, 1);
        $pdf->Ln(5);

        // Table Header
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 7, 'Request ID', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Donating Hospital', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Requesting Hospital', 1, 0, 'C', true);
        $pdf->Cell(20, 7, 'Blood Type', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Quantity', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Request Time', 1, 0, 'C', true);
        $pdf->Cell(20, 7, 'Status', 1, 1, 'C', true);

        // Table data
        $pdf->SetFont('Arial', '', 8);
        foreach ($requests as $request) {
            $donatingHospital = str_ireplace("- national blood bank", "", $request['donatingHospital']);
            $requestingHospital = str_ireplace("- national blood bank", "", $request['requestingHospital']);

            $pdf->Cell(20, 6, $request['requestID'], 1);
            $pdf->Cell(40, 6, $donatingHospital, 1);
            $pdf->Cell(40, 6, $requestingHospital, 1);
            $pdf->Cell(20, 6, $request['bloodType'], 1, 0, 'C');
            $pdf->Cell(25, 6, $request['requestedQuantity'], 1, 0, 'C');
            $pdf->Cell(25, 6, date('H:i:s', strtotime($request['requestDate'])), 1, 0, 'C');
            $pdf->Cell(20, 6, $request['status'], 1, 1, 'C');
        }

        $pdf->Output('D', 'blood_requests_report.pdf');
    } else {
        echo "No blood requests found for the selected date: " . htmlspecialchars($requestDate);
    }
} else {
    echo "Invalid download request.";
}
