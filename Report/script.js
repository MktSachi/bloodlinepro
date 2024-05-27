 // Function to fetch and display data in the table
function fetchDataAndDisplay() {
    fetch('save_donation.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('reportContent');
            tableBody.innerHTML = ''; // Clear existing rows

            data.forEach(rowData => {
                const row = `
                    <tr>
                        <td>${rowData.donorName}</td>
                        <td>${rowData.donorNIC}</td>
                        <td>${rowData.bloodType}</td>
                        <td>${rowData.hospital}</td>
                        <td>${rowData.expireDate}</td>
                        <td>${rowData.quantity}</td>
                        <td>${rowData.donationDate}</td>
                        <td><button class="btn btn-danger btn-sm" onclick="deleteRow(this)">Delete</button></td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Function to set the default expiration date to 40 days from today
function setDefaultExpireDate() {
    const expireDateField = document.getElementById('expireDate');
    const today = new Date();
    const defaultExpireDate = new Date(today);
    defaultExpireDate.setDate(today.getDate() + 40);

    // Format the date to YYYY-MM-DD
    const year = defaultExpireDate.getFullYear();
    const month = String(defaultExpireDate.getMonth() + 1).padStart(2, '0');
    const day = String(defaultExpireDate.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;

    expireDateField.value = formattedDate;
}

// Set the default expiration date and fetch data on page load
window.onload = function() {
    setDefaultExpireDate(); // Set default expiration date
    fetchDataAndDisplay(); // Fetch and display data in the table

    // Event listeners for buttons
    document.getElementById('saveData').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent form submission
        saveData(); // Save data function
    });

    document.getElementById('showTable').addEventListener('click', showTable);
    document.getElementById('generateReport').addEventListener('click', generateReport);
};

// Function to save data
function saveData() {
    const donorName = document.getElementById('donorName').value.trim();
    const donorNIC = document.getElementById('donorNIC').value.trim();
    const bloodType = document.getElementById('bloodType').value.trim();
    const hospital = document.getElementById('hospital').value.trim();
    const expireDate = document.getElementById('expireDate').value.trim();
    const quantity = document.getElementById('quantity').value.trim();

    if (!donorName || !donorNIC || !bloodType || !hospital || !expireDate || !quantity) {
        alert('Please fill in all fields before saving.');
        return;
    }

    // Prepare data object
    const data = { donorName, donorNIC, bloodType, hospital, expireDate, quantity };

    // AJAX request to save data
    fetch('save_donation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(data)
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        if (result.includes('New record created successfully')) {
            // If save successful, update table and reset form
            fetchDataAndDisplay(); // Refresh table with new data
            document.getElementById('reportForm').reset(); // Reset form fields
            setDefaultExpireDate(); // Reset expiration date
        } else {
            alert('Error saving data.');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to delete a row
// Function to delete a row
// Function to delete a row
function deleteRow(button) {
    const row = button.closest('tr');
    const cells = row.getElementsByTagName('td');
    const donorNIC = cells[1].innerText; // Assuming donorNIC is the second column

    // Ask for confirmation
    if (confirm(`Are you sure you want to delete the donation record for ${cells[0].innerText}?`)) {
        fetch('delete_donation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ donorNIC: donorNIC })
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            if (result.includes('Record deleted successfully')) {
                row.remove(); // Remove row from HTML table if deletion was successful
            } else {
                alert('Error deleting record.');
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        // If user cancels deletion
        return;
    }
}


// Function to show the report table
function showTable() {
    document.getElementById('reportTable').classList.remove('d-none');
}

// Function to generate a report
function generateReport(event) {
    event.preventDefault(); // Prevent form submission

    const tableContent = document.getElementById('reportContent').innerHTML;
    const html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Blood Donation Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                }

                h1 {
                    text-align: center;
                    margin-bottom: 20px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                    background-color: white;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }

                th {
                    background-color: #007BFF;
                    color: white;
                }
            </style>
        </head>
        <body>
            <h1>Blood Donation Report</h1>
            <table>
                <thead>
                    <tr>
                        <th>Donor Name</th>
                        <th>Donor NIC</th>
                        <th>Blood Type</th>
                        <th>Hospital</th>
                        <th>Expire Date</th>
                        <th>Quantity (ml)</th>
                        <th>Donation Date</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableContent}
                </tbody>
            </table>
        </body>
        </html>
    `;

    // Create a blob from the HTML content
    const blob = new Blob([html], { type: 'text/html' });

    // Create a temporary link element
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'blood_report.html';

    // Append the link to the body and trigger the download
    document.body.appendChild(link);
    link.click();

    // Clean up: remove the link
    document.body.removeChild(link);
}
