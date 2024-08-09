<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodLinePro</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
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
        }

        footer {
            color: var(--text-color);
            background-color: var(--bg-color);
            box-shadow: 0 -5px 5px -5px rgba(0, 0, 0, 0.1);
            border-top: 1px solid #ddd;
        }

        footer h5 {
            font-weight: bold;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        footer .social-icons {
            display: flex;
            justify-content: center;
            
        }

        footer .social-icons a {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin: 0 1rem;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        footer .social-icons a:hover {
            color: var(--secondary-color);
            transform: scale(1.1);
        }

        footer p, footer a {
            transition: color 0.3s ease;
        }

        footer p:hover, footer a:hover {
            color: var(--primary-color);
        }

        .copyright {
            background-color: rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
          
        }

        @media (max-width: 768px) {
            footer .col-md-4 {
                text-align: center;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Your existing content goes here -->

    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-envelope"></i> bloodlinepro.lk@gmail.com</p>
                    <p><i class="fas fa-phone"></i> +94 713245868</p>
                    <p><i class="fas fa-map-marker-alt"></i> Blood Line Pro<br>
                    No: 555/5D, Narahenpita<br>
                    Colombo 05, Sri Lanka</p>
                </div>
                <div class="col-md-4">
                    <h5>Our Mission</h5>
                    <p class="small">To ensure the quality, safety, adequacy, and cost effectiveness of the blood supply and related laboratory, clinical, academic, and research services in accordance with national requirements and WHO recommendations.</p>
                </div>
                <div class="col-md-4">
                <h5 style="padding-left: 7rem;">Contact Us</h5>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container text-center">
                <p class="mb-0">&copy; 2024 Blood Line Pro. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>