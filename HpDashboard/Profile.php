<?php
session_start();
require_once('../DonorRegistration/Database.php');

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Fetch HP details
$username = $_SESSION['username'];
$sql = "SELECT hp.*, h.hospitalname FROM healthcare_professionals hp
        JOIN users u ON hp.userid = u.userid
        JOIN hospitals h ON hp.hospitalid = h.hospitalid
        WHERE u.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

$hp = $result->fetch_assoc();
if ($hp) {
    $hpRegNo = $hp['hpRegNo'];
    $firstName = $hp['firstname'];
    $lastName = $hp['lastname'];
    $position = $hp['position'];
    $email = $hp['email'];
    $phoneNumber = $hp['phonenumber'];
    $hpnic = $hp['hpnic'];
    $hospitalName = $hp['hospitalname'];
} else {
    $error_msg = "Error fetching HP information.";
    $stmt->close();
    $db->close();
    die($error_msg);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateHP'])) {
    $errors = array();

    // Validate email
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    // Validate phone number
    $phoneNumber = trim($_POST['phoneNumber']);
    if (!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
        $errors['phoneNumber'] = "Invalid phone number format";
    }

    // Validate position
    $position = trim($_POST['position']);
    if (empty($position)) {
        $errors['position'] = "Position is required";
    }

    // Validate username
    $newUsername = trim($_POST['username']);
    if (empty($newUsername)) {
        $errors['username'] = "Username is required";
    }

    // Check for duplicate username
    if ($newUsername !== $_SESSION['username']) {
        $sql_check_username = "SELECT * FROM users WHERE username = ?";
        $stmt_check_username = $conn->prepare($sql_check_username);
        $stmt_check_username->bind_param('s', $newUsername);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();
        if ($result_check_username->num_rows > 0) {
            $errors['username'] = "Username already exists. Please choose a different username.";
        }
        $stmt_check_username->close();
    }

    // Update HP details if no errors
    if (empty($errors)) {
        $conn->begin_transaction();
        try {
            // Update HP details
            $updateHPSql = "UPDATE healthcare_professionals SET email = ?, phonenumber = ?, position = ? WHERE hpRegNo = ?";
            $updateHPStmt = $conn->prepare($updateHPSql);
            $updateHPStmt->bind_param('sssi', $email, $phoneNumber, $position, $hpRegNo);
            $updateHPStmt->execute();

            // Fetch user id
            $fetchUseridSql = "SELECT userid FROM users WHERE username = ?";
            $fetchUseridStmt = $conn->prepare($fetchUseridSql);
            $fetchUseridStmt->bind_param('s', $_SESSION['username']);
            $fetchUseridStmt->execute();
            $fetchUseridResult = $fetchUseridStmt->get_result();

            if ($fetchUseridResult->num_rows > 0) {
                $row = $fetchUseridResult->fetch_assoc();
                $userid = $row['userid'];

                // Update username in users table
                $updateUsersSql = "UPDATE users SET username = ? WHERE userid = ?";
                $updateUsersStmt = $conn->prepare($updateUsersSql);
                $updateUsersStmt->bind_param("si", $newUsername, $userid);

                if ($updateUsersStmt->execute()) {
                    $conn->commit();

                    // Update session username
                    $_SESSION['username'] = $newUsername;

                    // Success message
                    $_SESSION['success_msg'] = "HP details updated successfully";
                    header('Location: view_hp_details.php');
                    exit;
                } else {
                    throw new Exception("Failed to update user details.");
                }

                $updateUsersStmt->close();
            } else {
                throw new Exception("User not found in users table.");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error_msg = "Error updating HP details: " . $e->getMessage();
        }

        $updateHPStmt->close();
    }

    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - BloodLinePro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">   
    <link rel="stylesheet" href="../DonorProfile/style.css"> 

</head>
<body>
    <!-- Sidebar -->
    <?php include 'HpSidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="profile-header">
                <h2>Edit Profile</h2>
            </div>

            <form action="" method="POST">
                <div class="profile-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName">Full Name</label>
                                <div class="profile-text"><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="position">Position</label>
                                <div class="profile-text"><?php echo htmlspecialchars($position); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="hpnic">HP NIC</label>
                                <div class="profile-text"><?php echo htmlspecialchars($hpnic); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="hospitalid">Hospital</label>
                                <div class="profile-text"><?php echo htmlspecialchars($hospitalName); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <div class="profile-text"><?php echo htmlspecialchars($phoneNumber); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="profile-text"><?php echo htmlspecialchars($email); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <div class="profile-text"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" name="updateHP" class="btn btn-save"><i class="fas fa-save me-2"></i>Save Changes</button>
                    <a href="ForgotPassword.php" class="btn btn-save"><i class="fas fa-key me-2"></i>Change Password</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Popper.js (for Bootstrap functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
