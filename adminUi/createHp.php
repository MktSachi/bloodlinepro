<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Healthcare Professional Account Creation</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/createHp.css">
    </head>
    <body>
        <div class="page-title">Healthcare Professional Account</div>
        <?php if (isset($_POST['submit'])): ?>
            <div class="success-message">
                Account created successfully!<br>Here are the details:<br><br>
                <?php
                echo "First Name: " . $_POST['first_name'] . "<br>";
                echo "Second Name: " . $_POST['second_name'] . "<br>";
                echo "User Name: " . $_POST['username'] . "<br>";
                echo "Email: " . $_POST['email'] . "<br>";
                echo "Registration Number: " . $_POST['registration_number'] . "<br>";
                echo "NIC Number: " . $_POST['nic_number'] . "<br>";
                echo "Gender: " . $_POST['gender'] . "<br>";
                echo "Contact Number: " . $_POST['contact_number'] . "<br>";
                ?>
            </div>
        <?php else: ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="second_name" class="form-label">Second Name</label>
                    <input type="text" class="form-control" id="second_name" name="second_name" required>
                </div>
                <div class="form-group">
                    <label for="username" class="form-label">User Name</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="registration_number" class="form-label">Registration Number</label>
                    <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                </div>
                <div class="form-group">
                    <label for="nic_number" class="form-label">NIC Number</label>
                    <input type="text" class="form-control" id="nic_number" name="nic_number" required>
                </div>
                <div class="form-group">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="abcd1234" readonly>
                </div>
                <button type="submit" class="btn btn-custom" name="submit">Create Account</button>
            </form>
        <?php endif; ?>
    </body>
</html>
