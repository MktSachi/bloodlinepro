<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blood Bank Management System - Donor Registration</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="Css/header.css">
    <link rel="stylesheet" href="Css/footer.css">
    <!-- Custom JavaScript -->
    <script type="text/javascript" src="Js/slide.js"></script>
</head>
<body class="p-1 m-0 border-0 bd-example">
    <?php include './Report/nav.php' ?>
    <main role="main" class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 blog-main">
                <h4 class="mb-3">Donor Registration</h4>
                <form class="needs-validation" novalidate method="POST" action="">
                    <div class="mb-3">
                        <label for="donorNIC">NIC Num</label>
                        <input type="text" class="form-control" id="donorNIC" name="donorNIC" placeholder="National Identity Card Number" required>
                        <div class="invalid-feedback">
                            Please enter NIC Number.
                        </div>
                    </div>

                    <!-- Autofill fields for donor name and blood type -->
                    <div class="mb-3">
                        <label for="donorName">Donor Name</label>
                        <input type="text" class="form-control" id="donorName" name="donorName" placeholder="Donor Name" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="bloodType">Blood Type</label>
                        <input type="text" class="form-control" id="bloodType" name="bloodType" placeholder="Blood Type" readonly>
                    </div>

                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Register</button>
                </form>
                <form action="show_donors.php" method="POST">
                    <button class="btn btn-secondary btn-lg btn-block mt-3" id="showDataBtn">Show Data</button>
                </form>
            </div>
            <div class="col-md-1"></div>
        </div>
    </main>

    <!-- Bootstrap and Custom JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for autofill based on NIC number -->
    <script>
        $(document).ready(function() {
            $('#donorNIC').on('change', function() {
                var donorNIC = $(this).val();

                // AJAX call to fetch donor name and blood type
                $.ajax({
                    url: 'fetch_donor_data.php', // PHP script to fetch data based on NIC
                    type: 'POST',
                    data: { donorNIC: donorNIC },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#donorName').val(data.donorName);
                            $('#bloodType').val(data.bloodType);
                        } else {
                            alert('Error fetching donor data.');
                        }
                    },
                    error: function() {
                        alert('Error fetching donor data.');
                    }
                });
            });
        });
    </script>
</body>
</html>
