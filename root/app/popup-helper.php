<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: popup-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once "../app/admin-helper.php";

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