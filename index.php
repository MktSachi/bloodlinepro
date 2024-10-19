<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloodlinepro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e81a35;
            --secondary-color: #5f0606;
            --text-color: #333;
            --bg-color: #f8f9fa;
            --white-color: #fff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
        }

     
        .hero {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .slideshow-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .mySlides {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .mySlides img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: var(--white-color);
            z-index: 10;
            width: 100%;
            padding: 0 15px;
        }

        .hero h1 {
            padding-top: 80px;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: darkred;
        }

        .hero h2 {
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 2rem;
        }
        .btn-primary {
            background: linear-gradient(45deg, #e81a35,);
            padding:2rem ;
            font-weight: 500;
            color: #fff; 
            
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #c2185b, #e81a35); 
            color: #fff; 
        }

        section {
            padding: 5rem 0;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 3rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: var(--primary-color);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: -6px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: var(--primary-color);
        }

        .contact-form {
            background-color: var(--white-color);
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }


        @keyframes fade {
            from {opacity: .4} 
            to {opacity: 1}
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero h2 {
                font-size: 1.5rem;
            }

            section {
                padding: 3rem 0;
            }

            h2 {
                font-size: 2rem;
            }

            .contact-form {
                padding: 2rem;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero h2 {
                font-size: 1.2rem;
            }

            .btn-primary {
                padding: 0.5rem 1.5rem;
            }

            .section-subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<?php 
   include 'part_of_home_page/header.php';
?>
    <section class="hero">
        <div class="slideshow-container">
            <div class="mySlides fade">
                <img src="part_of_home_page/Image/25.jpg" alt="Slide 1">
            </div>
            <div class="mySlides fade">
                <img src="part_of_home_page/Image/24.jpg" alt="Slide 2">
            </div>
            <div class="mySlides fade">
                <img src="part_of_home_page/Image/9.jpg" alt="Slide 3">
            </div>
        </div>
        <div class="hero-content">
            <h1 data-aos="fade-up">BloodLinePro</h1>
            <h2 data-aos="fade-up" data-aos-delay="200">Your Blood, Their Hope</h2>
            <p data-aos="fade-up" data-aos-delay="300">Explore with us</p>
            <a href="login_window/login.php" class="btn btn-primary "  data-aos="fade-up" data-aos-delay="400">Explore Our Services</a>
        </div>
    </section>

    <section id="services">
        <div class="container">
            <h2 class="text-center" data-aos="fade-up">Our Services</h2>
            <p class="text-center section-subtitle" data-aos="fade-up" data-aos-delay="200">Ensuring efficient blood management and supply</p>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fa fa-medkit fa-3x mb-3" style="color: var(--primary-color);"></i>
                            <h5 class="card-title">Blood Donation Camps</h5>
                            <p class="card-text">Managing blood banks to accurately record and maintain information on blood donation camps</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fa fa-hospital fa-3x mb-3" style="color: var(--primary-color);"></i>
                            <h5 class="card-title">Blood Bank Management</h5>
                            <p class="card-text">Efficiently managing blood banks to maintain a safe and reliable blood inventory.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x mb-3 " style="color: var(--primary-color);"></i>
                            <h5 class="card-title">Our Operations</h5>
                            <p class="card-text">We significantly reduced our donor registartion time, a critical improvement for our operations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="Achievements" class="bg-light">
        <div class="container">
            <h2 class="text-center" data-aos="fade-up">Our Achievements</h2>
            <p class="text-center section-subtitle" data-aos="fade-up" data-aos-delay="200">Our accomplishments and milestones</p>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card">
                        <img src="part_of_home_page/Image/22.jpg" class="card-img-top" alt="Project 1">
                        <div class="card-body">
                            <h5 class="card-title">Enhanced Real-Time Monitoring</h5>
                            <p class="card-text">BloodLinePro updates blood stocks in real-time for better management.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card">
                        <img src="part_of_home_page/Image/26.jpg" class="card-img-top" alt="Project 2">
                        <div class="card-body">
                            <h5 class="card-title">Improved Donor Engagement</h5>
                            <p class="card-text"> Simplifies donations with online forms, badges, and reminders.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card">
                        <img src="part_of_home_page/Image/23.jpg" class="card-img-top" alt="Project 3">
                        <div class="card-body">
                            <h5 class="card-title"> Administrative Processes</h5>
                            <p class="card-text">Automates paperwork, reducing errors and improving efficiency.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <h2 class="text-center" data-aos="fade-up">About Us</h2>
            <p class="text-center section-subtitle" data-aos="fade-up" data-aos-delay="200">Learn more about my journey in tech</p>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <img src="part_of_home_page/Image/19.png" alt="James Anderson" class="img-fluid rounded-circle">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="timeline-item">
                        <h4>2024 - Uva Provice</h4>
                        <p> Implemented  across Uva Province, enhancing blood donation and management efficiency</p>
                    </div>
                    <div class="timeline-item">
                        <h4>2024-2025 - Island Wide</h4>
                        <p>Expanded Island Wide, streamlining blood bank operations and improving accessibility for donors and recipients</p>
                    </div>
                    <div class="timeline-item">
                        <h4>2026 - World Wide</h4>
                        <p>Achieved global reach, revolutionizing blood bank management systems and setting new standards for efficiency and connectivity worldwid</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="bg-light">
        <div class="container">
            <h2 class="text-center" data-aos="fade-up">Contact Us</h2>
            <p class="text-center section-subtitle" data-aos="fade-up" data-aos-delay="200">Get in touch with us for more information</p>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form class="contact-form" data-aos="fade-up" data-aos-delay="300">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });

    // Slideshow
    let slideIndex = 0;
    const slides = document.querySelectorAll('.mySlides');

    function showSlides() {
        
        slides[slideIndex].style.opacity = 0;
        
       
        slideIndex = (slideIndex + 1) % slides.length;
        
        
        slides[slideIndex].style.opacity = 0;
        slides[slideIndex].style.display = "block";
        
        
        setTimeout(() => {
            slides[slideIndex].style.opacity = 1;
        }, 50);

        setTimeout(showSlides, 3000); 
    }

    
    slides.forEach((slide, index) => {
        slide.style.display = index === 0 ? "block" : "none";
        slide.style.opacity = index === 0 ? 1 : 0;
        slide.style.transition = "opacity 0.5s ease-in-out";
    });

    showSlides();
        
    </script>
    <?php 
   include 'part_of_home_page/footer.php';
?>
</body>
</html>