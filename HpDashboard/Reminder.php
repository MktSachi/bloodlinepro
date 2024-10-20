<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reminder For Next Donation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Assets/css/header.css">
    <link rel="stylesheet" href="../Assets/css/footer.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background-color: #ffffff;
            border: 1px solid #ced4da;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            font-weight: 600;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .table {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 16px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            position: fixed;
            width: 100%;
            bottom: 0;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <main role="main" class="container dashboard-container">
        <div class="card" id="reminderForm">
            <div class="card-header">
                <h4 class="page-title">Reminder For Next Donation</h4>
            </div>
            <div class="card-body">
                <form action="DonationEmailSender.php" method="post">
                    <div class="form-group">
                        <label for="blood">Blood Group</label>
                        <select id="blood" name="blood" class="form-control" required>
                            <option value="">-Select Blood Group-</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB-">AB-</option>
                            <option value="AB+">AB+</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address2">Address</label>
                        <input type="text" class="form-control" id="address2" name="address2" placeholder="Enter the address" required>
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>

                    <div class="form-group">
                        <label for="venue">Venue</label>
                        <input type="text" class="form-control" id="venue" name="venue" placeholder="Enter the venue" required>
                    </div>

                    <button class="btn btn-primary btn-lg btn-block w-100" type="submit">
                        <i class="fa-solid fa-paper-plane"></i>
                        Send Reminder
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Set the minimum date for the date input to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
            const yyyy = today.getFullYear();
            const minDate = yyyy + '-' + mm + '-' + dd;
            document.getElementById('date').setAttribute('min', minDate);
        });
    </script>
</body>
</html>
