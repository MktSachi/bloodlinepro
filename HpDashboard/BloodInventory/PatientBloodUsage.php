<?php
session_start();

// Include the FPDF library and the Inventory class
// Include the FPDF library and the Inventory class
require 'fpdf/fpdf.php';
require_once '../../Classes/Database.php'; // Ensure this path is correct
require_once dirname(__FILE__) . '../Inventory.php'; // Update the path if necessary


// Initialize Database and Inventory
try {
    $db = new Database();
    $conn = $db->getConnection();
    $inventory = new Inventory($conn);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize variables
$username = $_SESSION['username'] ?? '';
$reportData = [];
$reportPeriod = ['start' => '', 'end' => ''];
$error = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['startDate'], $_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Validate date inputs
    if (validateDate($startDate) && validateDate($endDate)) {
        if (!empty($username)) {
            try {
                $reportData = $inventory->getPatientReport($startDate, $endDate);
                $reportPeriod = ['start' => $startDate, 'end' => $endDate];
                // Store in session if needed
                $_SESSION['reportData'] = $reportData;
                $_SESSION['reportPeriod'] = $reportPeriod;
            } catch (Exception $e) {
                $error = "Error generating report: " . $e->getMessage();
            }
        } else {
            $error = 'User not authenticated.';
        }
    } else {
        $error = 'Invalid date format.';
    }
}

// Handle PDF Download
if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    $downloadStartDate = $_GET['startDate'] ?? '';
    $downloadEndDate = $_GET['endDate'] ?? '';

    if (validateDate($downloadStartDate) && validateDate($downloadEndDate)) {
        try {
            // Fetch report data
            $downloadReportData = $inventory->getPatientReport($downloadStartDate, $downloadEndDate);

            // Generate PDF
            generatePDFReport($downloadStartDate, $downloadEndDate, $downloadReportData);
        } catch (Exception $e) {
            $error = "Error generating PDF: " . $e->getMessage();
        }
        exit;
    } else {
        $error = 'Invalid download parameters.';
    }
}

/**
 * Validates a date string.
 *
 * @param string $date The date string to validate.
 * @return bool True if valid, false otherwise.
 */
function validateDate($date) {
    $d = DateTime::createFromFormat("Y-m-d", $date);
    return $d && $d->format("Y-m-d") === $date;
}

/**
 * Generates a PDF report using FPDF.
 *
 * @param string $startDate The start date of the report.
 * @param string $endDate The end date of the report.
 * @param array $reportData The data to include in the report.
 */
function generatePDFReport($startDate, $endDate, $reportData) {
    // Create a new PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font for the header
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Patient Blood Usage Report', 0, 1, 'C');

    // Set font for the period
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Report Period: $startDate to $endDate", 0, 1, 'C');

    // Add some space
    $pdf->Ln(10);

    // Table headers
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Blood Type', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Number of Patients', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Total Blood Quantity Used', 1, 1, 'C');

    // Table data
    $pdf->SetFont('Arial', '', 12);
    foreach ($reportData as $report) {
        $bloodType = $report['bloodType'];
        $patientCount = $report['patientCount'];
        $totalBloodUsed = $report['totalBloodUsed'] . ' units';

        $pdf->Cell(60, 10, $bloodType, 1, 0, 'C');
        $pdf->Cell(60, 10, $patientCount, 1, 0, 'C');
        $pdf->Cell(60, 10, $totalBloodUsed, 1, 1, 'C');
    }

    // Output the PDF as a download
    $pdf->Output('D', 'Patient_Blood_Usage_Report.pdf');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Blood Usage Report - BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            background-color: white;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .table {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
<div class="container dashboard-container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Patient Blood Usage Report</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" id="startDate" name="startDate" class="form-control" value="<?= htmlspecialchars($reportPeriod['start'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" id="endDate" name="endDate" class="form-control" value="<?= htmlspecialchars($reportPeriod['end'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate Report</button>

                        <?php if (!empty($reportPeriod['start']) && !empty($reportPeriod['end'])): ?>
                            <a href="?download=pdf&startDate=<?= urlencode($reportPeriod['start']) ?>&endDate=<?= urlencode($reportPeriod['end']) ?>" class="btn btn-success">Download PDF</a>
                        <?php endif; ?>
                    </form>

                    <!-- Display Report Data if available -->
                    <?php if (!empty($reportData)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Blood Type</th>
                                    <th>Number of Patients</th>
                                    <th>Total Blood Quantity Used</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $report): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($report['bloodType']) ?></td>
                                        <td><?= htmlspecialchars($report['patientCount']) ?></td>
                                        <td><?= htmlspecialchars($report['totalBloodUsed']) ?> units</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap JS for functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
