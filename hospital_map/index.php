
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uva Province Hospitals</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
    <div id="map"></div>
    <div id="chart-container">
        <canvas id="barChart"></canvas>
    </div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="script.js"></script>
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT hospital_name, blood_type, units, latitude, longitude FROM blood_data";
$result = $conn->query($sql);

$hospitals = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hospitals[$row['hospital_name']]['coords'] = [$row['latitude'], $row['longitude']];
        $hospitals[$row['hospital_name']]['data'][$row['blood_type']] = $row['units'];
    }
}

echo json_encode($hospitals);

$conn->close();
?>
</body>
</html>

