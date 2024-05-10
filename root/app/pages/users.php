<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /pages/users.php
 * Description: ChatGPT API Status Generator
 */
?>

<div class="edit-users-box">
    <div class="edit-users-form">
        <h3>Add/Update User</h3>
        <form action="/users" method="POST" id="edit-user-form">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <div class="selections">
                <div>
                    <label for="total-accounts">Total Accounts:</label>
                    <select name="total-accounts" id="total-accounts">
                        <?php for ($i = 1; $i <= 10; $i++) : ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="max-api-calls">Max API Calls:</label>
                    <select name="max-api-calls" id="max-api-calls">
                        <option value="0">Off</option>
                        <option value="180">180</option>
                        <option value="360">360</option>
                        <option value="1080">1,080</option>
                        <option value="3240">3,240</option>
                        <option value="9999999999">Unlimited</option>
                    </select>
                </div>
                <div>
                    <label for="used-api-calls">Used API Calls:</label>
                    <select name="used-api-calls" id="used-api-calls">
                        <option value="0">0</option>
                    </select>
                </div>
                <div>
                    <label for="admin">Admin:</label>
                    <select name="admin" id="admin">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
            </div>

            <button type="submit" id="submit-edit-user" name="edit_users">Add/Update User</button>
        </form>
        <?php echo display_and_clear_messages(); ?>
    </div>

    <div class="edit-users-list">
        <?php
        $users = getAllUsers(); // Assuming this function fetches all users from the database
        foreach ($users as $user) {
            $dataAttributes = 'data-username="' . htmlspecialchars($user->username) . '" ';
            $dataAttributes .= 'data-password="' . urlencode($user->password) . '" ';
            $dataAttributes .= 'data-admin="' . $user->admin . '" ';
            $dataAttributes .= 'data-total-accounts="' . $user->total_accounts . '" ';
            $dataAttributes .= 'data-max-api-calls="' . $user->max_api_calls . '" ';
            $dataAttributes .= 'data-used-api-calls="' . $user->used_api_calls . '" ';
        ?>
            <div class="user-item">
                <h3><?php echo htmlspecialchars($user->username); ?></h3>
                <button class="green-button" id="update-user-btn" <?php echo $dataAttributes; ?>>Update</button>
                <form action="/users" method="POST">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user->username); ?>">
                    <button class="red-button" id="delete-user-btn" name="delete_user">Delete</button>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('#update-user-btn');
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const usernameField = document.querySelector('#username');
                const passwordField = document.querySelector('#password');
                const totalAccountsSelect = document.querySelector('#total-accounts');
                const maxApiCallsSelect = document.querySelector('#max-api-calls');
                const usedApiCallsSelect = document.querySelector('#used-api-calls');
                const adminSelect = document.querySelector('#admin');

                // Set form fields from data attributes
                usernameField.value = this.dataset.username;
                passwordField.value = decodeURIComponent(this.dataset.password);
                totalAccountsSelect.value = this.dataset.totalAccounts;
                maxApiCallsSelect.value = this.dataset.maxApiCalls;
                usedApiCallsSelect.innerHTML = `<option value="${this.dataset.usedApiCalls}">${this.dataset.usedApiCalls}</option><option value="0">0</option>`;
                adminSelect.value = this.dataset.admin;
            });
        });
    });
</script>
