<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Professional Profile</title>
    <link rel="stylesheet" href="css/Profile.css">
</head>
<body>
<?php include './HP_sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">
    
    <div class="container">
        
        <div class="profile-picture">
            <img id="profilePic" src="path_to_default_profile_picture.jpg" alt="Profile Picture" class="profile-pic">
            <input type="file" id="profilePicInput" accept="image/*" style="display: none;" onchange="loadProfilePic(event)"></br>
            <button class="change-pic-button" onclick="document.getElementById('profilePicInput').click();">Change</button>
        </div>
        <h1>Dr. Ahinsa</h1>
        <p>Cardiologist</p>
        <div class="about">
            <h2>About Me</h2>
            <p>
                Hello! I'm Dr. Ahinsa, a board-certified cardiologist with over 10 years 
                of experience in diagnosing and treating cardiovascular conditions. I am dedicated 
                to providing comprehensive care and helping patients achieve optimal heart health.
            </p>
        </div>
        <div class="contact">
       
            <label><strong>Registration Number:</strong></label>
            <p><span id="registration" class="editable" onclick="makeEditable(this)">123456</span> <input type="text" id="registrationInput"></p>
            
            <label><strong>First name:</strong></label>
            <p><span id="fname" class="editable" onclick="makeEditable(this)">Dr. Ahinsa</span> <input type="text" id="fnameInput"></p>
           
            <label><strong>Last name:</strong></label>
            <p><span id="lname" class="editable" onclick="makeEditable(this)">Arunodi</span> <input type="text" id="lnameInput"></p>

            <label><strong>Address:</strong></label>
            <p><span id="address" class="editable" onclick="makeEditable(this)">123 Heartbeat Lane, Cardiology City, Healthland</span> <textarea id="addressInput"></textarea></p>
            
            <label><strong>Phone Number:</strong></label>
            <p><span id="phone" class="editable" onclick="makeEditable(this)">(123) 456-7890</span> <input type="text" id="phoneInput"></p>
            
            <label><strong>Email:</strong></label>
            <p><span id="email" class="editable" onclick="makeEditable(this)">dr.ahinsa.modi@example.com</span> <input type="email" id="emailInput"></p>
            
            <label><strong>Password:</strong></label>
            <p><span id="password" class="editable" onclick="makeEditable(this)">password123</span> <input type="password" id="passwordInput"></p>
        </div>
        <button onclick="saveInfo()">Save</button>
    </div>

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
            const fields = ['registration', 'fname', 'lname','address', 'phone', 'email', 'password'];
            
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
                URL.revokeObjectURL(output.src) // Free memory
            }
        }
    </script>
</body>
</html>
