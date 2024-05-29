<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        h3 {
            font-family: 'Times New Roman';
            color: #CD5C5C;
            font-weight: bold;
            font-size: 30px;
        }

        .bg-image {
            background-image: url('pic-21.jpg');
            height: 100%;
            background-position: center;
            background-size: cover;
        }

        .card {
            background-color: rgba(234, 242, 242, 0.484);
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(227, 5, 5, 0.2);
            border-color: rgb(131, 26, 26);
            border-width: 0.4px; 
            min-height: 80%;
        }

        .logo {
            width: 75%;
            height: 60px;
        }

        .card-title {
            margin-bottom: 40px;
            font-family: 'Times New Roman', Times, serif;
            color: rgb(131, 26, 26);
            text-transform: capitalize;
            text-shadow: 1px 1px 1px #d02c50;
            font-size: 30px;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-click {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
            border-radius: 20px;
            transition: background-color 0.3s, border-color 0.3s;
            font-weight: bold;
        }

        .btn-click:hover {
            background-color: rgb(131, 26, 26);
            border-color: #ff4757;
        }

        a {
            color: #ff6b6b;
            transition: color 0.3s;
        }

        a:hover {
            color: rgb(131, 26, 26);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="bg-image">
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card p-4 shadow-lg" style="width: 22rem;">
                <div class="text-center">
                    <img src="logo_1.png" alt="Company Logo" class="logo mb-3">
                </div>
                <h3 class="card-title text-center">Login</h3>
                <form>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                    </div>
                    <div class="form-group">
                        <label>Select a Role</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="roleDonor" value="donor" required>
                            <label class="form-check-label" for="roleDonor">
                                Donor
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="roleHealthcare" value="healthcare" required>
                            <label class="form-check-label" for="roleHealthcare">
                                HealthCare Professional
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="admin" required>
                            <label class="form-check-label" for="roleAdmin">
                                Admin
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="#" class="float-right">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-click btn-block">Sign In</button>
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="#">Create Account</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
