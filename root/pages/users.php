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

            <div class="admin-checkbox">
                <label for="admin">Admin:</label>
                <input type="checkbox" name="admin" id="admin">
            </div>

            <div class="form-ta">
                <label for="total-accounts">Total Accounts:</label>
                <select name="total-accounts" id="total-accounts" required>
                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-aa">
                <label for="max-api-calls">Max API Calls:</label>
                <select name="max-api-calls" id="max-api-calls" required>
                    <option value="0">Off</option>
                    <option value="180">180</option>
                    <option value="360">360</option>
                    <option value="1080">1,080</option>
                    <option value="3240">3,240</option>
                    <option value="9999999999">Unlimited</option>
                </select>
            </div>
            <div class="form-uac">
                <label for="used-api-calls">Used API Calls:</label>
                <input type="text" name="used-api-calls" id="used-api-calls" required>
            </div>
            <button type="submit" id="submit-edit-user" name="edit_users">Add/Update User</button>
        </form>
    </div>

    <div class="edit-users-list">
        <h3>Users List</h3>
        <?php
        $userFiles = glob('../storage/users/*'); // Get all .json files in the user directory
        foreach ($userFiles as $userFile) :
            $username = pathinfo($userFile, PATHINFO_FILENAME); // Get the filename without extension as username
            $userData = getUserData($username);
            if ($userData !== null) :
                // Generate data attributes string
                $dataAttributes = "data-username=\"$username\" ";
                $dataAttributes .= str_replace(['&', '='], ['" data-', '="'], http_build_query($userData, 'data-'));
                $dataAttributes .= '"';
        ?>
                <div class="user-item">
                    <p><?php echo htmlspecialchars($username); ?></p>
                    <button class="green-button" id="update-user-btn" <?php echo $dataAttributes; ?>>Update</button>
                    <form action="/users" method="POST">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                        <button class="red-button" id="delete-user-btn" name="delete_user">Delete</button>
                    </form>
                </div>
        <?php
            endif;
        endforeach;
        ?>
    </div>


</div>