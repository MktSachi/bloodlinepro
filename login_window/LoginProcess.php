<?php
session_start();
require '../DonorRegistration/Database.php';
require '../DonorRegistration/Donor.php';

$db = new Database(); // Create an instance of Database
$donor = new Donor($db); // Pass $db to the Donor class constructor

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($donor->CheckUserName($username)) { // Ensure method name is correct (CheckUserName instead of isCheckUserName)
        $user_data = $donor->getUserByUsername($username);

        if (password_verify($password, $user_data['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['roleID'] = $user_data['roleID'];
            $_SESSION['active'] = $user_data['active'];

            if (isset($user_data['hospitalID'])) {
                $_SESSION['hospitalID'] = $user_data['hospitalID'];
            }

            switch ($user_data['roleID']) {
                case 'donor':
                    if ($user_data['active'] == 1) {
                        header("Location: ../DonorProfile/index.php");
                    } else {
                        $error_msg = "Account not active. Please contact support.";
                    }
                    break;
                case 'hp':
                    if ($user_data['active'] == 2) {
                        header("Location: ../HpDashboard/Profile.php");
                    } else {
                        $error_msg = "Account not active. Please contact support.";
                    }
                    break;
                case 'admin':
                    if ($user_data['active'] == 3) {
                        header("Location: ../AdminDashboard/index.php");
                    } else {
                        $error_msg = "Account not active. Please contact support.";
                    }
                    break;
                default:
                    $error_msg = "Invalid role. Please contact support.";
            }
            exit();
        } else {
            $error_msg = "Incorrect password. Please try again.";
        }
    } else {
        $error_msg = "Username not found. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>

</html>
