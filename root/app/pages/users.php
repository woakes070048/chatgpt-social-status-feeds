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
    </div>

    <div class="edit-users-list">
    <?php
    $userFiles = glob('../storage/users/*');
    foreach ($userFiles as $userFile) {
        $username = basename($userFile);
        $userInfo = json_decode(file_get_contents($userFile), true);
        if ($userInfo !== null) {
            $dataAttributes = 'data-username="' . $username . '" ';
            $dataAttributes .= 'data-password="' . urlencode($userInfo['password']) . '" ';
            $dataAttributes .= 'data-admin="' . $userInfo['admin'] . '" ';
            $dataAttributes .= 'data-total-accounts="' . $userInfo['total-accounts'] . '"';
            $dataAttributes .= 'data-max-api-calls="' . $userInfo['max-api-calls'] . '"';
            $dataAttributes .= 'data-used-api-calls="' . $userInfo['used-api-calls'] . '"';
    ?>
            <div class="user-item">
                <h3><?php echo htmlspecialchars($username); ?></h3>
                <button class="green-button" id="update-user-btn" <?php echo $dataAttributes; ?>>Update</button>
                <form action="/users" method="POST">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <button class="red-button" id="delete-user-btn" name="delete_user">Delete</button>
                </form>
            </div>
    <?php
        }
    }
    ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all update buttons
        const updateButtons = document.querySelectorAll('#update-user-btn');
        const usedApiCallsSelect = document.querySelector('#used-api-calls');
        const adminSelect = document.querySelector('#admin');

        // Add event listener to all buttons
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the form fields
                const usernameField = document.querySelector('#username');
                const passwordField = document.querySelector('#password');
                const totalAccountsSelect = document.querySelector('#total-accounts');
                const maxApiCallsSelect = document.querySelector('#max-api-calls');

                // Get data from button
                const username = this.dataset.username;
                const password = this.dataset.password;
                const admin = this.dataset.admin;
                const totalAccounts = this.dataset.totalAccounts;
                const maxApiCalls = this.dataset.maxApiCalls;
                const usedApiCalls = this.dataset.usedApiCalls;

                // Populate form fields with data
                usernameField.value = username || '';
                passwordField.value = decodeURIComponent(password) || '';
                totalAccountsSelect.value = totalAccounts || '';
                maxApiCallsSelect.value = maxApiCalls || '';
                usedApiCallsSelect.innerHTML = `<option value="${usedApiCalls}" selected>${usedApiCalls}</option><option value="0">0</option>`;
                adminSelect.value = admin || '0'; // Set default to '0'
            });
        });
    });
</script>
