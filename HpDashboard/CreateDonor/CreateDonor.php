<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blood Bank Management System - Donor Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 40px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        h4 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-label {
            font-weight: 500;
            color: #34495e;
        }
        .form-control, .form-select {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            padding: 10px 20px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .form-check-input:checked {
            background-color: #3498db;
            border-color: #3498db;
        }
        .loader {
            position: fixed;
            z-index: 9999;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
        }
        .loader img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .input-group-text {
            background-color: #ecf0f1;
            border: 1px solid #ced4da;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="loader" id="loader">
        <img src="../../AdminDashboard/Animation - 1720851760552.gif" alt="Loading...">
    </div>

    <div class="container">
        <h4 class="text-center">Donor Registration</h4>
        
        <div id="errorMsg" class="alert alert-danger" role="alert" style="display: none;"></div>
        
        <form class="needs-validation" novalidate method="POST" action="" enctype="multipart/form-data" name="Donor_Creation">
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                    <div class="invalid-feedback">Please enter your first name.</div>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                    <div class="invalid-feedback">Please enter your last name.</div>
                </div>
                <div class="col-md-6">
                    <label for="donorNIC" class="form-label">NIC Number</label>
                    <input type="text" class="form-control" id="donorNIC" name="donorNIC" required>
                    <div class="invalid-feedback">Please enter a valid NIC number.</div>
                </div>
                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="invalid-feedback">Please choose a username.</div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="col-md-6">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" required>
                    <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                    <div class="invalid-feedback">Please enter your address.</div>
                </div>
                <div class="col-12">
                    <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control" id="address2" name="address2">
                </div>
                <div class="col-md-4">
                    <label class="form-label d-block">Gender</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" checked>
                        <label class="form-check-label" for="genderMale">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female">
                        <label class="form-check-label" for="genderFemale">Female</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="bloodType" class="form-label">Blood Type</label>
                    <select id="bloodType" name="bloodType" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <div class="invalid-feedback">Please select your blood type.</div>
                </div>
                <div class="col-md-4">
                    <label for="otherHealthConditions" class="form-label">Other Health Conditions</label>
                    <input type="text" class="form-control" id="otherHealthConditions" name="otherHealthConditions" placeholder="If applicable">
                </div>
            </div>

            <div class="mt-5">
                <button class="btn btn-primary w-100" type="submit" name="submit">Register as Donor</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form[name="Donor_Creation"]');
            var loader = document.getElementById('loader');
            var errorMsg = document.getElementById('errorMsg');

            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    errorMsg.textContent = 'Please fill out all required fields correctly.';
                    errorMsg.style.display = 'block';
                } else {
                    loader.style.display = 'block';
                    errorMsg.style.display = 'none';
                }
                form.classList.add('was-validated');
            }, false);

            // Custom validation for NIC number
            var nicInput = document.getElementById('donorNIC');
            nicInput.addEventListener('input', function() {
                if (!/^[0-9]{9}[vVxX]$/.test(this.value)) {
                    this.setCustomValidity('NIC should be 9 digits followed by V, v, X, or x');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Phone number formatting
            var phoneInput = document.getElementById('phoneNumber');
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 10);
            });
        });
    </script>
</body>
</html>