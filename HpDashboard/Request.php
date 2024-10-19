<?php
require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';

$db = new Database();
$bloodRequest = new BloodRequest($db->getConnection());
$hospitals = $bloodRequest->getHospitals();

if (!empty($_SESSION['error_msg'])) {
    $error_msg = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Assets/css/header.css">
    <link rel="stylesheet" href="../Assets/css/footer.css">
    <title>Blood Bank Management System</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .page-title {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 16px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }

        select.form-control {
            height: auto;
            padding: 10px 15px;
        }

        .btn-primary {
            background-color: #4a90e2;
            border-color: #4a90e2;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background-color 0.15s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #3a7bc8;
            border-color: #3a7bc8;
        }

        .alert {
            border-radius: 4px;
            margin-bottom: 30px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .loader {
            background: rgba(255, 255, 255, 0.8);
        }

        .loader img {
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body>
    <main role="main" class="container">
        <h1 class="page-title">Blood Request Form</h1>
        <?php if (!empty($error_msg)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_msg; ?>
            </div>
        <?php } ?>
        <form action="ProcessBloodReq.php" method="POST">
            <div class="form-group">
                <label for="hospital">Hospital</label>
                <select id="hospital" name="hospital" class="form-control" required>
                    <option value="">-Select Hospital-</option>
                    <?php
                    foreach ($hospitals as $hospitalID => $hospitalName) {
                        echo "<option value=\"$hospitalID\">$hospitalName</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="blood">Blood Group</label>
                <select id="blood" name="blood" class="form-control" required>
                    <option value="">-Select Blood Group-</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Blood quantity (pints)</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Required blood quantity" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter the reason" required>
            </div>
            <button class="btn btn-primary btn-lg btn-block w-100" type="submit" name="submit">Send Request</button>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#hospital').change(function() {
                var hospitalID = $(this).val();
                if (hospitalID) {
                    $.ajax({
                        url: 'getBloodGroups.php',
                        type: 'POST',
                        data: { hospitalID: hospitalID },
                        success: function(response) {
                            $('#blood').html(response);
                        }
                    });
                } else {
                    $('#blood').html('<option value="">-Select Blood Group-</option>');
                }
            });
        });

        
    </script>

</body>
</html>