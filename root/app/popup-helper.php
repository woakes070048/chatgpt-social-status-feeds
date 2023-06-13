<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: popup-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once "../app/admin-helper.php";
require_once "../app/auth-helper.php";

?>
<div class="update-account-popup" id="update-account-popup" style="display:none;">
    <div class="update-account-box">
        <div class="update-account-form">
            <h3>Update/Delete Account</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="account" id="update-account-name" value="">
                <label for="update-key">Key:</label>
                <input type="text" name="key" id="update-key" required>
                <label for="update-prompt">Prompt:</label>
                <textarea name="prompt" id="update-prompt" required></textarea>
                <label for="update-link">Link:</label>
                <input type="text" name="link" id="update-link" required>
                <div class="hashtags">
                    <label for="update-hashtags">Include Hashtags:</label>
                    <input type="checkbox" name="hashtags" id="update-hashtags">
                </div>
                <button type="submit" class="update-account-btn" name="update">Update Account</button>
                <button type="submit" class="delete-account-btn" name="delete">Delete Account</button>
                <button type="button" class="close-update-popup-btn" id="close-update-popup-btn">Close</button>
            </form>
        </div>
    </div>
</div>

<div class="add-account-popup" id="add-account-popup">
    <div class="add-account-box">
        <div class="add-account-form">
            <h3>Create New Account</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <label for="account_name">Account Name:</label>
                <input type="text" name="account_name" id="account_name" required>
                <label for="key">Key:</label>
                <input type="text" name="key" id="key" required>
                <label for="add-prompt">Prompt:</label>
                <textarea name="prompt" id="add-prompt" required></textarea>
                <label for="link">Link:</label>
                <input type="text" name="link" id="link" required>
                <div class="hashtags">
                    <label for="hashtags">Include Hashtags:</label>
                    <input type="checkbox" name="hashtags" id="hashtags">
                </div>
                <button type="submit" class="add-account-button" name="create_account">Create Account</button>
                <button type="button" id="close-add-popup-btn">Close</button>
            </form>
        </div>
    </div>
</div>

<div class="manage-users-popup" id="manage-users-popup" style="display:none;">
    <div class="manage-users-box">
        <div class="manage-users-grid">
            <div class="manage-users-form">
                <h3>Add/Update User</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="manage-user-form">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>

                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>

                    <div class="admin-checkbox">
                        <label for="admin">Admin:</label>
                        <input type="checkbox" name="admin" id="admin">
                    </div>

                    <div class="form-ta">
                    <label for="total-accounts">Total Accounts:</label>
                    <select name="total-accounts" id="total-accounts">
                        <?php for ($i = 1; $i <= 10; $i++) : ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                        </div>

                    <div class="form-aa">
                    <label for="account-access">Account Access:</label>
                    <select name="account-access[]" id="account-access" style="font-size: 1.5em; height: 300px;" multiple >
                        <?php foreach ($accounts as $account) : ?>
                            <option value="<?php echo htmlspecialchars($account['name']); ?>">
                                <?php echo htmlspecialchars($account['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                        </div>
                    <button type="submit" class="add-user-button" name="add_update_user">Add/Update User</button>
                    <button type="button" id="close-manage-users-popup-btn">Close</button>
                </form>
            </div>

            <div class="manage-users-list">
    <h3>Users List</h3>
    <?php 
    $userFiles = glob('../storage/users/*'); // Get all .json files in the user directory
    foreach ($userFiles as $userFile) : 
        $username = pathinfo($userFile, PATHINFO_FILENAME); // Get the filename without extension as username
        $user = getUserData($username);
        if ($user !== null) :
    ?>
        <div class="user-item">
            <p><?php echo htmlspecialchars($username); ?></p>
            <button class="update-user-btn" data-username="<?php echo htmlspecialchars($username); ?>" data-user='<?php echo json_encode($user); ?>'>Update</button>
            <button class="delete-user-btn" data-username="<?php echo htmlspecialchars($username); ?>">Delete</button>
        </div>
    <?php 
        endif;
    endforeach; 
    ?>
</div>

        </div>
    </div>
</div>