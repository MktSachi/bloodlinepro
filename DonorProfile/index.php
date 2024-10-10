<?php include 'DonorProfile.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Profile</title>
    <link rel="stylesheet" href="css/Profile.css">
    <style>
        
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="w3-main">
        <div class="container">
         
           

            <script>
                function makeEditable(element) {
                    const inputId = element.id + 'Input';
                    const inputElement = document.getElementById(inputId);

                    inputElement.value = element.innerText;
                    element.style.display = 'none';
                    inputElement.style.display = 'block';
                    inputElement.focus();
                }

                function saveInfo() {
                    const fields = ['registration', 'fname', 'lname', 'address', 'phone', 'email', 'password'];
                    
                    fields.forEach(field => {
                        const spanElement = document.getElementById(field);
                        const inputElement = document.getElementById(field + 'Input');

                        if (inputElement.style.display === 'block') {
                            spanElement.innerText = inputElement.value;
                            spanElement.style.display = 'block';
                            inputElement.style.display = 'none';
                        }
                    });
                }

                function loadProfilePic(event) {
                    const output = document.getElementById('profilePic');
                    output.src = URL.createObjectURL(event.target.files[0]);
                    output.onload = function() {
                        URL.revokeObjectURL(output.src) 
                    }
                }

                function showSection(sectionId) {
                    var sections = document.querySelectorAll('.profile-section');
                    sections.forEach(function(section) {
                        section.style.display = 'none';
                    });

                    document.getElementById(sectionId).style.display = 'block';
                }

                function logout() {
                    window.location.href = 'logout.php'; 
                }

                
                function w3_open() {
                    document.getElementById("mySidebar").style.display = "block";
                    document.getElementById("myOverlay").style.display = "block";
                }

                function w3_close() {
                    document.getElementById("mySidebar").style.display = "none";
                    document.getElementById("myOverlay").style.display = "none";
                }
            </script>
        </div>
    </div>
</body>
</html>