<!DOCTYPE html>
<html lang="en">
<head>
    

    <style>
    body {
    font-family: Arial;
}

/* Division properties */
div.container {
    padding: 20px;
}

/* Input field properties */
input[type=text], input[type=password], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Label properties */
label {
    color: rgb(131, 26, 26);
}

/* Asterisk mark */
.required::after {
    content: "*";
    color: rgb(131, 26, 26);
}

/* Submit button properties */
input[type=submit] {
    width: 10%;
    background-color: rgb(131, 26, 26);
    color: white;
    padding: 10px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
}
</style>

</head>
<body class="w3-light-grey">

    <?php include './HP_sidebar.php'; ?>
    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:300px;margin-top:43px;">
        <div class="container">
            <form action="your_form_submission_endpoint.php" method="POST">
                <label for="email">E-mail<span class="required"></span></label>
                <input type="text" id="email" name="email" placeholder="Your e-mail.." required>

                <label for="blood">Blood Group<span class="required"></span></label>
                <select id="blood" name="blood">
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>

                <label for="quantity">Blood quantity (pints)<span class="required"></span></label>
                <input type="text" id="quantity" name="quantity" placeholder="Required blood quantity.." required>

                <label for="description">Description<span class="required"></span></label>
                <input type="text" id="description" name="description" placeholder="Your requirements.." required>

                <input type="submit" value="Send">
            </form>
        </div>
    </div>
</body>
</html>
