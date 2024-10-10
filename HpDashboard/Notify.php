<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

    <title>Send Donation Camp Notification</title>

    <style>
        body {
  font-family: Arial;
}

/* Division properties */


        .btn-block {
            background-color: #1E7CE7;
            color: #fff;
            border-color: #1E7CE7;
        }
        .required::after {
            content: "*";
            color: red;
            margin-left: 3px;
        }
    </style>
</head>
<body class="p-0 m-0 border-0 bd-example">


<main role="main" class="container" style="margin-left:210px;">
    <div class="row">
        <div class="col-md-6 mb-3"></div>
        <div class="col-md-10 blog-main">
            <h3 class="mb-3"><strong>Send Donation Camp Notification</strong></h3>
            
            <form action="SendNotification.php" method="post">
                <div class="mb-3">
                    <label for="address2" class="form-label">City:</label>
                    <input type="text" class="form-control" id="address2" name="address2" required>
                </div>
                
                <div class="mb-3">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                
                <div class="mb-3">
                    <label for="time" class="form-label">Time:</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
                
                <div class="mb-3">
                    <label for="venue" class="form-label">Venue:</label>
                    <input type="text" class="form-control" id="venue" name="venue" required>
                </div>
                
                <button class="btn btn-primary btn-lg btn-block" type="submit">Send Notification</button>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>