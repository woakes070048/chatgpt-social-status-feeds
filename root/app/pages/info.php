<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/pages/info.php
 * Description: ChatGPT API Status Generator
*/
?>

<div class="edit-info-box">
    <div class="change-info-form">
        <h3>Change Password</h3>
        <form action="/info" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="password2">Confirm New Password:</label>
            <input type="password" name="password2" id="password2" required>
            <button type="submit" class="green-button" name="change_password">Change Password</button>
        </form>
        <?php echo display_and_clear_messages(); ?>
    </div>

</div>
