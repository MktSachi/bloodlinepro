<?php
session_start();

if (isset($_GET['download']) && $_GET['download'] === 'html') {
    if (isset($_SESSION['donations'])) {
        $data = $_SESSION['donations'];

        // Function to generate HTML file and force download
        function generateHTMLDownload($data) {
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

        
        generateHTMLDownload($data);
    } else {
        echo "No data available to download.";
    }
} else {
    echo "Invalid download request.";
}
?>
