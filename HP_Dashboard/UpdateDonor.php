
<div class="operation" onclick="toggleOperationDetails('update')">
        <i class="fa fa-repeat" style="color: rgb(131, 26, 26); margin-right: 5px;"></i>
        <a href="#" style="text-decoration: none; color: black;">Update</a>
    </div>
    <div class="operation-details" id="update-details" style="display: none;">
        <form id="update-form" onsubmit="handleSubmit('update')">
            <h4>Update Account</h4>
            <label for="account_id_update">Account ID<span class="required">*</span></label>
            <input type="text" id="account_id_update" name="account_id_update" placeholder="Account ID to update.." required onblur="searchAccount(this.value)">
            <label for="account_name_update">Account Name</label>
            <input type="text" id="account_name_update" name="account_name_update" placeholder="Account Name">
            <label for="account_balance_update">Account Balance</label>
            <input type="text" id="account_balance_update" name="account_balance_update" placeholder="Account Balance">
            <input type="submit" value="Update Account">
        </form>
        <!-- Result placeholder for update operation -->
        <div id="update-result"></div>
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

            if (operation === 'update') {
                updateAccount(accountId)
                    .then(response => {
                        if (response.success) {
                            resultDiv.innerHTML = 'Account ' + accountId + ' updated successfully.';
                        } else {
                            resultDiv.innerHTML = 'Failed to update account ' + accountId + '.';
                        }
                    })
                    .catch(error => {
                        resultDiv.innerHTML = 'Error: ' + error.message;
                    });
            } else if (operation === 'read') {
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

        function updateAccount(accountId) {
            // Simulated API call - replace with actual API call in production
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve({ success: true });
                }, 1000);
            });
        }

        function searchAccount(accountId) {
            readAccount(accountId)
                .then(response => {
                    if (response.success) {
                        document.getElementById('account_name_update').value = response.data.name;
                        document.getElementById('account_balance_update').value = response.data.balance;
                    } else {
                        alert('Account not found');
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
        }
    </script>