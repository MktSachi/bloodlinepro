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
  
  margin-top: 20px;
  text-align: left;
  display: inline-block;
  width: 95%;
  padding: 20px;
  background: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
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

   
    <!-- !PAGE CONTENT! -->
    <div class="w3-main">
        <div class="container">
            <form action="your_form_submission_endpoint.php" method="POST">

                <label for="quantity">Hospital<span class="required"></span></label>
                <select id="hospital" name="hospital name">
                    <option value="">Teaching hospital Badulla</option>
                    <option value="">Monaragala Hospital</option>
                    <option value="">Welimada Hospital</option>
                    <option value="">Diyathalawa Hospital</option>
                    <option value="">Mahiyanganaya Hospital</option>
                    <option value="">Bibila Hospital</option>
                    <option value="">Wellawaya Hospital</option>
                </select>

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
