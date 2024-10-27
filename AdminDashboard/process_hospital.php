<?php
// Include database connection
include '../Classes/Database.php'; // Adjust the path as necessary

// Include the Hospital class
include '../Classes/Hospital.php'; // Adjust the path as necessary

// Initialize variables
$resultMessage = "";
$isSuccess = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a new database connection
    $database = new Database();
    $db = $database->getConnection();

    // Create a new Hospital object
    $hospital = new Hospital($db);

    // Get form data
    $hospital->hospitalName = $_POST['hospitalName'];
    $hospital->address = $_POST['address'];
    $hospital->phoneNumber = $_POST['phoneNumber'];
    $hospital->email = $_POST['email'];

    // Add the hospital to the database
    if ($hospital->addHospital()) {
        $resultMessage = "Hospital added successfully!";
        $isSuccess = true;
    } else {
        $resultMessage = "Failed to add hospital.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #dc3545;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            background-color: #dc3545;
            border: none;
        }
        .btn-primary:hover {
            background-color: #c82333;
        }
        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
        }
        .alert {
            border-radius: 10px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .form-label {
            font-weight: 600;
        }
        .error {
            color: red;
        }
        .success-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50vh; /* Adjusted for smaller vertical space */
}
    .success-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Ensures the container takes the full viewport height */
    }

    .success-card {
        text-align: center;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        padding: 1.5rem;
        width: 100%;
        max-width: 300px;
    }

    .success-card .icon {
        font-size: 2.5rem;
        color: #28a745;
        margin-bottom: 0.8rem;
    }

    .success-card .message {
        font-size: 1.3rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 0.4rem;
    }

    .success-card .sub-message {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .success-card .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        padding: 8px 16px;
        font-size: 1rem;
    }

    .success-card .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
</style>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <?php if ($isSuccess): ?>
        <div class="success-container">
            <div class="success-card">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="message">Success!</div>
                <div class="sub-message"><?php echo $resultMessage; ?></div>
                <a href="AdminDashboard.php" class="btn btn-success">Go to Dashboard</a>
            </div>
        </div>
    <?php endif; ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#addHospitalForm").validate({
                rules: {
                    hospitalName: "required",
                    address: "required",
                    phoneNumber: {
                        required: true,
                        phoneUS: true
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    hospitalName: "Please enter the hospital name",
                    address: "Please enter the address",
                    phoneNumber: {
                        required: "Please enter a phone number",
                        phoneUS: "Please enter a valid US phone number"
                    },
                    email: "Please enter a valid email address"
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>

