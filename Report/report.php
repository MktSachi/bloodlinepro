<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Report Generator</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="header.css">
    <!-- Bootstrap  5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <?php include './nav.php'; ?>
    <div class="container">
        <h1>Blood Report Generator</h1>
        <form id="reportForm" method="POST" action="save_donation.php">
            <fieldset>
                <legend>Enter Blood Donation Information:</legend>
                <div class="form-group">
                    <label for="donorName">Donor Name:</label>
                    <input type="text" id="donorName" name="donorName" required>
                </div>

                <div class="form-group">
                    <label for="donorNIC">Donor NIC:</label>
                    <input type="text" id="donorNIC" name="donorNIC" required>
                </div>



                <div class="form-group">
                    <label for="bloodType">Blood Type:</label>
                    <select id="bloodType" name="bloodType" required>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="hospital">Hospital:</label>
                    <select id="hospital" name="hospital" required>
                        <option value="Badulla Blood Bank">Badulla Blood Bank</option>
                        <option value="Badull Genaral Hospital">Badull Genaral Hospital</option>
                        <option value="Badull Genaral Hospital">Badull Genaral Hospital</option>
                    </select>
                </div>
                

                <div class="form-group">
                    <label for="expireDate">Expire Date:</label>
                    <input type="date" id="expireDate" name="expireDate" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity (in ml):</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
            </fieldset>

            <button class="btn btn-primary btn-danger" type="button" id="saveData">Save</button><br>
            <button class="btn btn-success btn-danger" type="button" id="showTable">Show Table</button>
        </form>

        <div id="reportTable" class="mt-4 d-none">
            <h2>Donation Report</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Donor Name</th>
                        <th>Donor NIC</th>
                        <th>Blood Type</th>
                        <th>Hospital</th>
                        <th>Expire Date</th>
                        <th>Quantity (ml)</th>
                        <th>Donation Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="reportContent">
                    <!-- Table content will be dynamically added here -->
                </tbody>
            </table>
            <button class="btn btn-danger" type="button" id="generateReport">Generate Report</button>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: jQuery and Popper.js for Bootstrap (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="script.js"></script>
</body>
</html>
