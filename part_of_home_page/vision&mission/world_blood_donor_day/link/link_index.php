<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badulla District Blood Hospitals</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <header class="jumbotron jumbotron-fluid text-white bg-gradient">
        <div class="container text-center">
            <h1 class="display-4">Badulla District Blood Hospitals</h1>
            <p class="lead">Easily locate blood hospitals in the Badulla district and contact them directly.</p>
        </div>
    </header>

    <section class="container my-5">
        <h2>How to Find Blood Hospitals</h2>
        <p>Follow the instructions below to locate the nearest blood hospital and contact them:</p>
        <ul>
            <li>Click on a marker on the map to see the hospital's details.</li>
            <li>The hospital's name and phone number will be displayed in a popup.</li>
            <li>Click on the phone number to call the hospital directly from your mobile device.</li>
        </ul>
    </section>

    <section class="container my-5">
        <div id="map" style="height: 500px;"></div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="script.js"></script>
</body>
</html>
