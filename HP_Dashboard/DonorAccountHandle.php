<!DOCTYPE html>
<html>
<head>
    <title>Donor Account</title>
    <link rel="stylesheet" href="css/DonorHandle.css">    
</head>
<body>
    <?php include './HP_sidebar.php'; ?>

    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:250px;margin-top:43px;">
        
        <div class="container">
            <h3><strong>Donor Account</strong></h3>
             <!-- Operation icons - create -->
            <?php include './CreateDonor.php'; ?>

            <!-- Operation icons - delete -->
            <?php include './DeleteDonor.php'; ?>
            
            <!-- Operation icons - update -->
            <?php include './UpdateDonor.php'; ?>

            <!-- Operation icons - view -->
            <?php include './ViewDonor.php'; ?>
        </div>
    
    </div>



    <script>

        // Function to handle showing/hiding operation details
        function toggleOperationDetails(operation) {
            var details = document.getElementById(operation + "-details");
            if (details.style.display === "none") {
                details.style.display = "block";
            } else {
                details.style.display = "none";
            }
        }

        // Function to handle form submission
        function handleSubmit(operation) {
            event.preventDefault();

            // Get form data
            var formData = new FormData(document.getElementById(operation + "-form"));

            // Fetch API endpoint (replace with your actual endpoint)
            var url = 'action_page.php';

            // Fetch options
            var options = {
                method: 'POST',
                body: formData
            };

            // Perform fetch request
            fetch(url, options)
                .then(response => response.text())
                .then(data => {
                    // Handle response (e.g., display success message)
                    document.getElementById(operation + "-result").innerHTML = data;
                })
                .catch(error => {
                    // Handle errors
                    console.error('Error:', error);
                });
        }
    </script>
</body>
</html>
