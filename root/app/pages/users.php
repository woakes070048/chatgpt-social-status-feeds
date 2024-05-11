<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /pages/users.php
 * Description: ChatGPT API Status Generator
 */
?>

<main class="flex-container">
    <section id="left-col">
        <h3>Add/Update User</h3>
        <form class="edit-user-form" action="/users" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="total-accounts">Total Accounts:</label>
            <select name="total-accounts" id="total-accounts">
                <?php for ($i = 1; $i <= 10; $i++) : ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <label for="max-api-calls">Max API Calls:</label>
            <select name="max-api-calls" id="max-api-calls">
                <option value="0">Off</option>
                <option value="180">180</option>
                <option value="360">360</option>
                <option value="1080">1,080</option>
                <option value="3240">3,240</option>
                <option value="9999999999">Unlimited</option>
            </select>
            <label for="used-api-calls">Used API Calls:</label>
            <select name="used-api-calls" id="used-api-calls">
                <option value="0">0</option>
            </select>
            <label for="admin">Admin:</label>
            <select name="admin" id="admin">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button class="edit-user-button green-button" type="submit" name="edit_users">Add/Update User</button>
        </form>
        <div id="error-msg"><?php echo display_and_clear_messages(); ?></div>
    </section>

    <section id="right-col">
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
            <div class="item-box">
                <h3><?php echo htmlspecialchars($user->username); ?></h3>
                <button class="update-user-button green-button" id="update-btn" <?php echo $dataAttributes; ?>>Update</button>
                <form class=" delete-user-form" action=" /users" method="POST">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user->username); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button class="delete-user-button red-button" name="delete_user">Delete</button>
                </form>
            </div>
        <?php
        }
        ?>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('#update-btn');
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

                // Set the username field as readonly when updating
                usernameField.readOnly = true;
            });
        });
    });
</script>
