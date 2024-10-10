<?php include 'LoginProcess.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sri-Lanka Blood Line Pro - Blood Bank Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            z-index: -1;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: column-reverse;
        }
        .login-form {
            padding: 2rem;
            width: 100%;
        }
        .login-image {
            width: 100%;
            background: url('happy-lady-with-clipboard-looking.jpg') center/cover no-repeat;
            min-height: 200px;
        }
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #8B0000;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .btn-login {
            background-color: #8B0000;
            border-color: #8B0000;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        .btn-login:hover {
            background-color: #600000;
            border-color: #600000;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(139, 0, 0, 0.2);
        }
        .form-control {
            background-color: #f0f0f0;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #8B0000;
            box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.25);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            font-weight: 600;
            color: #8B0000;
            margin-bottom: 0.5rem;
            display: block;
        }
        .quick-action-btn {
            background-color: #8B0000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s;
            margin: 5px;
        }
        .quick-action-btn:hover {
            background-color: #600000;
            color: white;
        }
        .quick-action-btn i {
            margin-right: 10px;
        }
        .text-dark-red {
            color: #8B0000;
        }
        .no-underline {
            text-decoration: none;
        }
        @media (min-width: 768px) {
            .login-card {
                flex-direction: row;
            }
            .login-form, .login-image {
                width: 50%;
            }
            .login-image {
                min-height: 300px;
            }
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-form">
                <div class="logo">
                    <i class="fas fa-tint me-2"></i>BloodLinePro
                </div>
                
                <?php if (!empty($error_msg)) { ?>
                    <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                <?php } ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Email</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="remember_me" id="remember_me" class="form-check-input">
                        <label for="remember_me" class="form-check-label">Remember Me</label>
                    </div>
                    <div class="form-group text-end mt-2">
                        <a href="ForgotPassword.php" class="no-underline">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-login btn-block">Login</button>
                </form>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-facebook-f text-dark-red"></i>
                    </button>
                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-google text-dark-red"></i>
                    </button>
                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-twitter text-dark-red"></i>
                    </button>
                    <p>Don't you have an account? <a href="../DonorRegistration/RegisterForm.php" class="no-underline">Sign up</a></p>
                </div>
            </div>
            <div class="login-image"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: "#8B0000" },
                shape: { type: "circle" },
                opacity: { value: 0.5, random: false },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: "#8B0000", opacity: 0.4, width: 1 },
                move: { enable: true, speed: 2, direction: "none", random: false, straight: false, out_mode: "out", bounce: false }
            },
            interactivity: {
                detect_on: "canvas",
                events: { onhover: { enable: true, mode: "repulse" }, onclick: { enable: true, mode: "push" }, resize: true },
                modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });
    </script>
</body>
</html>