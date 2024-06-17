
<div class="operation" onclick="toggleOperationDetails('delete')">
                <i class="fa fa-minus" style="color: rgb(131, 26, 26); margin-right: 5px;"></i><a href="#" style="text-decoration: none; color: black;">Delete</a>
</div>
<div class="operation-details" id="delete-details">
    <form id="delete-form" onsubmit="handleSubmit('delete')">
        <h4>Delete Account</h4>
        <label for="account_id">Account ID<span class="required">*</span></label>
        <input type="text" id="account_id" name="account_id" placeholder="Account ID to delete.." required>
        <input type="submit" value="Delete Account">
    </form>
    <!-- Result placeholder for delete operation -->
    <div id="delete-result"></div> 
</div>

<script>
        function toggleOperationDetails(operation) {
            var details = document.getElementById(operation + '-details');
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
        }

        function handleSubmit(operation) {
            event.preventDefault();
            var accountId = document.getElementById('account_id').value;
            var resultDiv = document.getElementById(operation + '-result');
            
            // Simulate an API call to delete the account
            deleteAccount(accountId)
                .then(response => {
                    if (response.success) {
                        resultDiv.innerHTML = 'Account ' + accountId + ' deleted successfully.';
                    } else {
                        resultDiv.innerHTML = 'Failed to delete account ' + accountId + '.';
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = 'Error: ' + error.message;
                });
        }

        function deleteAccount(accountId) {
            // Simulated API call - replace with actual API call in production
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    if (accountId === "12345") { // Simulate successful deletion for accountId "12345"
                        resolve({ success: true });
                    } else {
                        resolve({ success: false });
                    }
                }, 1000);
            });
        }
    </script>

