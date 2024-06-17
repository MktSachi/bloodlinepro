
<div class="operation" onclick="toggleOperationDetails('read')">
    <i class="fa fa-eye" style="color: rgb(131, 26, 26); margin-right: 5px;"></i><a href="#" style="text-decoration: none; color: black;">View</a>
</div>
<div class="operation-details" id="read-details">
    <form id="read-form" onsubmit="handleSubmit('read')">
        <h4>Read Account</h4>
        <label for="account_id_read">Account ID<span class="required">*</span></label>
        <input type="text" id="account_id_read" name="account_id_read" placeholder="Account ID to read.." required>
        <input type="submit" value="Read Account">
    </form>
    <!-- Result placeholder for read operation -->
    <div id="read-result"></div> 
</div>

<script>
        function toggleOperationDetails(operation) {
            var details = document.getElementById(operation + '-details');
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
        }

        function handleSubmit(operation) {
            event.preventDefault();
            var accountId = document.getElementById('account_id_' + operation).value;
            var resultDiv = document.getElementById(operation + '-result');
            
            if (operation === 'read') {
                readAccount(accountId)
                    .then(response => {
                        if (response.success) {
                            resultDiv.innerHTML = 'Account ID: ' + accountId + '<br>' +
                                                  'Account Name: ' + response.data.name + '<br>' +
                                                  'Account Balance: ' + response.data.balance;
                        } else {
                            resultDiv.innerHTML = 'Failed to retrieve account ' + accountId + '.';
                        }
                    })
                    .catch(error => {
                        resultDiv.innerHTML = 'Error: ' + error.message;
                    });
            }
        }

        function readAccount(accountId) {
            // Simulated API call - replace with actual API call in production
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    if (accountId === "12345") { // Simulate successful read for accountId "12345"
                        resolve({
                            success: true,
                            data: {
                                name: "John Doe",
                                balance: "$1,234.56"
                            }
                        });
                    } else {
                        resolve({ success: false });
                    }
                }, 1000);
            });
        }
    </script>