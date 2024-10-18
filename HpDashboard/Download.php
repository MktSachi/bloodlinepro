<?php
session_start();
require('BloodInventory/fpdf/fpdf.php'); // Ensure FPDF is included in the correct path

if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    // Check if the blood requests data is available
    if (isset($_SESSION['allRequests'])) {
        // Create a new PDF instance
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10); // Set left, top, and right margins
        $pdf->SetFont('Arial', 'B', 16);

        // Title of the report
        $pdf->Cell(0, 10, 'Blood Requests Report', 0, 1, 'C'); // Centered title without border
        $pdf->Ln(10); // Add space after title

        // Set the header of the table
        $pdf->SetFont('Arial', 'B', 12);
        
        // Define the header titles
        $header = ['Hospital Name', 'Blood Type', 'Blood Quantity', 'Requested Date', 'Status'];
        
        // Initialize array to store max widths for each column
        $maxWidths = array_fill(0, count($header), 0);

        // Calculate maximum width for each column based on headers and data
        foreach ($header as $index => $colTitle) {
            $maxWidths[$index] = $pdf->GetStringWidth($colTitle) + 6; // Get width of header + padding
        }

        foreach ($_SESSION['allRequests'] as $request) {
            // Remove "National Blood Bank" and hyphens from the hospital name
            $hospitalName = str_replace(["National Blood Bank", "-"], "", $request['hospitalName']);
            // Collect current widths for each column
            $currentWidths = [
                $pdf->GetStringWidth($hospitalName) + 6,
                $pdf->GetStringWidth($request['bloodType']) + 6,
                $pdf->GetStringWidth($request['bloodQuantity']) + 6,
                $pdf->GetStringWidth($request['requestDate']) + 6,
                $pdf->GetStringWidth($request['status']) + 6,
            ];

            // Update max widths for each column
            foreach ($maxWidths as $index => $maxWidth) {
                $maxWidths[$index] = max($maxWidths[$index], $currentWidths[$index]);
            }
        }

        // Calculate total width of the table
        $totalWidth = array_sum($maxWidths);

        // Center the table
        $pdf->SetX((210 - $totalWidth) / 2); // 210 is the width of A4 page in mm

        // Set the header with calculated widths
        foreach ($maxWidths as $index => $maxWidth) {
            $pdf->Cell($maxWidth, 10, $header[$index], 1, 0, 'C'); // Added border
        }
        $pdf->Ln();

        // Set the font for table rows
        $pdf->SetFont('Arial', '', 12);

        // Now, render each row with the specified column widths
        foreach ($_SESSION['allRequests'] as $request) {
            // Remove "National Blood Bank" and hyphens from the hospital name for the output
            $hospitalName = str_replace(["National Blood Bank", "-"], "", htmlspecialchars($request['hospitalName']));
            $pdf->SetX((210 - $totalWidth) / 2); // Center each row
            $pdf->Cell($maxWidths[0], 10, $hospitalName, 1); // Added border
            $pdf->Cell($maxWidths[1], 10, htmlspecialchars($request['bloodType']), 1); // Added border
            $pdf->Cell($maxWidths[2], 10, htmlspecialchars($request['bloodQuantity']), 1); // Added border
            $pdf->Cell($maxWidths[3], 10, htmlspecialchars($request['requestDate']), 1); // Added border
            $pdf->Cell($maxWidths[4], 10, htmlspecialchars($request['status']), 1); // Added border
            $pdf->Ln();
        }

        // Output the PDF to the browser
        $pdf->Output('D', 'blood_requests_report.pdf');
    } else {
        echo "No blood requests data available for generating PDF.";
    }
    exit;
}
?>
