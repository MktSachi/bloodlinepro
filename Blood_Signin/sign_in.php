<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BloodLinePro</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row shadow-lg p-0 mb-5 bg-white rounded w-100">
            <div class="col-md-6 d-none d-md-block p-0">
                <div class="img-box h-100"></div>
            </div>
            <div class="col-md-6 p-5 form-wrap">
                <div class="text-right mb-3">
                    <span>Don't you have an account?</span>
                    <a href="#" class="btn btn-outline-primary btn-sm">Sign In</a>
                </div>
                <div class="text-center mb-4">
                    <h1>Welcome BloodLinePro</h1>
                    <h6>Login to your Account</h6>
                </div>
                <form action="" class="form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter your username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter your password">
                    </div>
                    <div class="form-group text-right">
                        <a href="#" class="btn btn-outline-primary btn-sm">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
