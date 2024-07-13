<?php include 'DonorProfile.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
<style>
    body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    background-color: #f4f4f4;
}





.main-content {
    margin-left: 20px;
    padding: 20px;
    width: calc(100% - 250px);
}

header {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.card {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card h2 {
    color: #333;
    margin-top: 0;
}

.btn {
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

.map-image {
    width: 100%;
    max-width: 300px;
    height: auto;
    margin-bottom: 10px;
}

.stat {
    text-align: center;
    margin-bottom: 15px;
}

.stat-number {
    font-size: 2em;
    font-weight: bold;
    color: #007bff;
}

.stat-label {
    display: block;
    color: #666;
}
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Welcome,<?php echo htmlspecialchars($firstName); ?> </h1>
            <p>Your next donation date: <strong>15th August 2024</strong></p>
        </header>

        <div class="dashboard-grid">
            <div class="card health-condition">
                <h2>Health Condition</h2>
                <p>Your last checkup results show excellent health!</p>
                <ul>
                    <li>Blood Pressure: Normal</li>
                    <li>Hemoglobin: 14.5 g/dL</li>
                    <li>Iron: 80 µg/dL</li>
                </ul>
                <a href="#" class="btn">View Full Report</a>
            </div>

            <div class="card diet-plan">
                <h2>Diet Plan</h2>
                <p>Boost your iron levels with these foods:</p>
                <ul>
                    <li>Lean red meat</li>
                    <li>Beans and lentils</li>
                    <li>Spinach and kale</li>
                    <li>Fortified cereals</li>
                </ul>
              <!--  <a href="#" class="btn">Get Personalized Diet</a>-->
            </div>

            <div class="card sri-lanka-map">
                <h2>Blood Banks Near You</h2>
                <img src="images/sri-lanka-map.png" alt="Sri Lanka Map" class="map-image">
                <p>5 blood banks within 10km radius</p>
           <!--     <a href="#" class="btn">Find Nearest Bank</a>-->
            </div>

            <div class="card donation-stats">
                <h2>Your Donation Impact</h2>
                <div class="stat">
                    <span class="stat-number">8</span>
                    <span class="stat-label">Lives Saved</span>
                </div>
                <div class="stat">
                    <span class="stat-number">4</span>
                    <span class="stat-label">Donations Made</span>
                </div>
                <a href="#" class="btn">View Donation History</a>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>