<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodLinePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --text-color: #333;
            --bg-color: #f8f9fa;
            --white-color: #fff;
            --primary-color: #BB0000;
            --secondary-color: darkred;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
            padding-top: 70px; 
        }

        .navbar {
            background-color: var(--white-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            padding: 0.5rem 2rem;
            z-index: 1000; 
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            max-width: 150px;
            height: auto;
        }

        .navbar-nav {
            margin-left: 20px;
        }

        .nav-item {
            margin-right: 15px; 
        }

        .nav-link {
            color: var(--secondary-color) !important;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.3s ease;
            position: relative;
            padding: 5px 10px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .nav-link:hover::before {
            transform: scaleX(1);
        }

        .dropdown-menu {
            background-color: var(--white-color);
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            color: var(--secondary-color);
            transition: color 0.3s ease;
        }

        .dropdown-item:hover {
            color: var(--primary-color);
            background-color: rgba(187, 0, 0, 0.1);
        }

        .navbar-toggler {
            border: none;
            background-color: var(--primary-color);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,1)' stroke-width='2' linecap='round' linejoin='round' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }

        
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem; 
            }

            .navbar-brand {
                font-size: 1.2rem; 
            }

            .navbar-nav {
                margin-left: 0;
            }

            .nav-link {
                font-size: 0.9rem; 
                padding: 0.5rem 0; 
            }
        }

        @media (max-width: 576px) {
            .navbar {
                padding: 0.25rem 0.5rem; 
            }

            .navbar-brand {
                font-size: 1rem; 
            }

            .navbar-brand img {
                max-width: 100px; 
            }

            .nav-link {
                font-size: 0.8rem; 
            }

            .dropdown-menu {
                font-size: 0.8rem; 
            }

            .navbar-toggler {
                padding: 0.2rem 0.4rem; 
                color: white;
                background-color: #6B031A !important; 
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light fixed-top">
        <div class="container-fluid">
            <a href="#" class="navbar-brand navimg">
                <img src="part_of_home_page/Image/1212.png" alt="Logo" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="color: #f8f9fa;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-home"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Donate
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="DonatePage/AboutBlood.php">About Blood</a></li>
                            <li><a class="dropdown-item" href="DonatePage/FunctionBlood.php">Function Of Blood</a></li>
                            <li><a class="dropdown-item" href="DonatePage/BloodGroup.php">Blood Groups</a></li>
                            <li><a class="dropdown-item" href="DonatePage/Donate.php">Who Can Donate Blood</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            WBDD
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="part_of_home_page/world_blood_donor_day/index.php">World Blood Donors Day 2020</a></li>
                            <li><a class="dropdown-item" href="world_blood_donor_day/index.php">World Blood Donors Day 2021</a></li>
                            <li><a class="dropdown-item" href="world_blood_donor_day/index.php">World Blood Donors Day 2022</a></li>
                            <li><a class="dropdown-item" href="world_blood_donor_day/index.php">World Blood Donors Day 2023</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="part_of_home_page/about/about.php">About BloodLinePro</a></li>
                            <li><a class="dropdown-item" href="part_of_home_page/vision&mission/vision&mission.php">Vision - Mission</a></li>
                            <li><a class="dropdown-item" href="#">About Blood</a></li>
                            <li><a class="dropdown-item" href="#">Donor Camps</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="login_window/login.php">Sign-In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <footer>
        
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
