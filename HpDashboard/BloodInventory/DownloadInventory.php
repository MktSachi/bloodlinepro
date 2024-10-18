<?php
session_start();

require '../../Classes/Database.php';
require 'Inventory.php';
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    function BarChart($w, $h, $data, $format, $color, $maxVal, $nbDiv)
    {
        $this->SetFont('Courier', '', 10);
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0);
        $this->Ln(5);
        $this->SetX(45);
        $y = $this->GetY();
        $barWidth = $w / count($data);
        
        // Axis
        $this->Line(45, $y, 45, $y+$h);
        $this->Line(45, $y+$h, 45+$w, $y+$h);
        
        // Bars
        $i = 0;
        foreach($data as $key => $val) {
            $barHeight = ($h / $maxVal) * $val;
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $this->Rect(45 + ($i * $barWidth) + 1, $y + ($h - $barHeight), $barWidth - 1, $barHeight, 'DF');
            $this->SetXY(45 + ($i * $barWidth), $y + $h);
            $this->Cell($barWidth, 5, $key, 0, 0, 'C');
            $i++;
        }
        
        // Y-Axis values
        for($j=0; $j<=$nbDiv; $j++) {
            $yval = $maxVal/$nbDiv*$j;
            $yaxis = sprintf($format, $yval);
            $this->SetXY(40, $y+($h-($h/$nbDiv*$j)));
            $this->Cell(5, 5, $yaxis, 0, 0, 'R');
        }
    }
}

if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    if (isset($_SESSION['inventoryReport'])) {
        $inventoryReport = $_SESSION['inventoryReport'];

        $pdf = new PDF();
        $pdf->AddPage();

        // Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Hospital Blood Inventory Report', 0, 1, 'C');
        $pdf->Ln(10);

        // Table Header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(95, 10, 'Blood Type', 1);
        $pdf->Cell(95, 10, 'Quantity', 1);
        $pdf->Ln();

        // Table data
        $pdf->SetFont('Arial', '', 12);
        foreach ($inventoryReport['inventory'] as $bloodType => $quantity) {
            $pdf->Cell(95, 10, $bloodType, 1);
            $pdf->Cell(95, 10, $quantity, 1);
            $pdf->Ln();
        }

        // Total Units
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Total Units: ' . $inventoryReport['totalUnits'], 0, 1);

        // Bar Chart
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Blood Inventory Bar Chart', 0, 1, 'C');
        $pdf->Ln(10);

        $data = $inventoryReport['inventory'];
        $maxVal = max($data);
        $pdf->BarChart(160, 80, $data, '%.0f', array(255,100,100), $maxVal, 5);

        $pdf->Output('D', 'inventory_report.pdf');
    } else {
        echo "No inventory data available to download.";
    }
} elseif (isset($_GET['download']) && $_GET['download'] === 'html') {
    if (isset($_SESSION['inventoryReport'])) {
        $inventoryReport = $_SESSION['inventoryReport'];

        // Generate HTML content with improved styling
        $html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Hospital Blood Inventory Report</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    max-width: 800px; 
                    margin: 0 auto; 
                    padding: 20px;
                }
                h1 { 
                    color: #2c3e50; 
                    text-align: center; 
                    border-bottom: 2px solid #3498db; 
                    padding-bottom: 10px;
                }
                table { 
                    border-collapse: collapse; 
                    width: 100%; 
                    margin-top: 20px;
                }
                th, td { 
                    border: 1px solid #ddd; 
                    padding: 12px; 
                    text-align: left; 
                }
                th { 
                    background-color: #3498db; 
                    color: white; 
                }
                tr:nth-child(even) { 
                    background-color: #f2f2f2; 
                }
                .total-units {
                    font-weight: bold;
                    margin-top: 20px;
                    font-size: 18px;
                    color: #2c3e50;
                }
            </style>
        </head>
        <body>
            <h1>Hospital Blood Inventory Report</h1>
            <table>
                <tr>
                    <th>Blood Type</th>
                    <th>Quantity</th>
                </tr>';

        foreach ($inventoryReport['inventory'] as $bloodType => $quantity) {
            $html .= "
                <tr>
                    <td>" . htmlspecialchars($bloodType) . "</td>
                    <td>" . htmlspecialchars($quantity) . "</td>
                </tr>";
        }

        $html .= '
            </table>
            <p class="total-units">Total Units: ' . htmlspecialchars($inventoryReport['totalUnits']) . '</p>
        </body>
        </html>';

        // Set headers for download
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="inventory_report.html"');
        
        echo $html;
    } else {
        echo "No inventory data available to download.";
    }
} else {
    echo "Invalid download request.";
}