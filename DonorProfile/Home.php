<?php include 'DonorProfile.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Blood Donor Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f8f8f8;
            color: #333;
        }

        .main-content {
            margin-left: 20px;
            padding: 30px;
            width: calc(100% - 20px);
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background: linear-gradient(135deg, #8b0000, #ff0000);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(139,0,0,0.2);
            color: white;
        }

        header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 600;
        }

        header p {
            margin-top: 10px;
            font-size: 18px;
            opacity: 0.9;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(139,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            min-width: 0;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(139,0,0,0.2);
        }

        .card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(139,0,0,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s;
        }

        .card:hover::before {
            left: 100%;
        }

        .card h2 {
            color: #8b0000;
            margin-top: 0;
            font-size: 24px;
            font-weight: 600;
            border-bottom: 3px solid #8b0000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #8b0000, #ff0000);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 30px;
            transition: all 0.3s;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(139,0,0,0.4);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(139,0,0,0.6);
        }

        .map-container {
            height: 250px;
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(139,0,0,0.1);
        }

        .stat {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f8f8, #e0e0e0);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .stat:hover {
            transform: scale(1.05);
        }

        .stat-number {
            font-size: 3.5em;
            font-weight: 700;
            background: linear-gradient(135deg, #8b0000, #ff0000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        .stat-label {
            display: block;
            color: #555;
            font-size: 16px;
            text-transform: uppercase;
            margin-top: 10px;
            font-weight: 500;
        }

        ul {
            padding-left: 20px;
            list-style-type: none;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        li {
            margin-bottom: 15px;
            position: relative;
            padding-left: 30px;
            font-size: 16px;
            flex-basis: calc(50% - 10px);
        }

        li:before {
            content: "\f058";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: #8b0000;
            font-size: 20px;
        }

        .health-icon, .diet-icon {
            font-size: 54px;
            background: linear-gradient(135deg, #8b0000, #ff0000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            header {
                padding: 20px;
            }

            header h1 {
                font-size: 24px;
            }

            header p {
                font-size: 16px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 20px;
            }

            .card h2 {
                font-size: 20px;
            }

            .stat-number {
                font-size: 2.5em;
            }

            .stat-label {
                font-size: 14px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 20px;
            }

            header p {
                font-size: 14px;
            }

            .card h2 {
                font-size: 18px;
            }

            .stat-number {
                font-size: 2em;
            }

            .stat-label {
                font-size: 12px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 11px;
            }

            li {
                flex-basis: 100%;
            }
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
            <p>Your next donation date: <strong>15th August 2024</strong></p>
        </header>

        <div class="dashboard-grid">
            <div class="card health-condition">
                <i class="fas fa-heartbeat health-icon"></i>
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
                <h2>Recommended Diet Plan</h2>
                <p>Boost your iron levels with these foods:</p>
                <ul>
                    <li>Lean red meat</li>
                    <li>Beans and lentils</li>
                    <li>Spinach and kale</li>
                    <li>Fortified cereals</li>
                </ul>
            </div>

            <div class="card blood-banks-map">
                <h2>Blood Banks Near You</h2>
                <div id="map" class="map-container"></div>
                <p>5 blood banks within 10km radius</p>
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
    <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>
<script src="DonorScript.js"></script>
</body>
</html>