<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
        }

        .container {
            max-width: 800px;
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, .25);
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
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0"><i class="fas fa-hospital-alt me-2"></i>Add Hospital</h2>
                </div>
                <div class="card-body">
                    <form action="process_hospital.php" method="post" id="addHospitalForm">
                        <div class="mb-3">
                            <label for="hospitalName" class="form-label">Hospital Name</label>
                            <input type="text" class="form-control" id="hospitalName" name="hospitalName" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Add Hospital</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
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
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>