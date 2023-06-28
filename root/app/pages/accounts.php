<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/pages/accounts.php
 * Description: ChatGPT API Status Generator
*/
?>

<div class="edit-accounts-box">
    <div class="edit-accounts-form">
        <h3>Add/Update New Account</h3>
        <form action="/accounts" method="POST">
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
            <button type="submit" class="green-button" id="submit-edit-account" name="edit_account">Add/Update Account</button>
        </form>
        <?php echo generateAccountDetails(); ?>
    </div>

    <div class="edit-accounts-list">
        <?php
        // Assuming you have a session variable 'username' for the logged-in user
        $username = $_SESSION['username'];

        // Get all accounts for the logged-in user
        $accountFiles = glob("../storage/accounts/{$username}/*", GLOB_ONLYDIR);

        foreach ($accountFiles as $accountFolder) :
            $accountName = pathinfo($accountFolder, PATHINFO_FILENAME); // Get the directory name as account name
            $accountData = getAcctInfo($username, $accountName);

            if ($accountData !== null) :
                // Generate data attributes string
                $dataAttributes = "data-account-name=\"$accountName\" ";
                $dataAttributes .= str_replace(['&', '='], ['" data-', '="'], http_build_query(array_diff_key($accountData, ['hashtags' => '']), 'data-'));
                $dataAttributes .= "\" data-hashtags=\"" . ($accountData['hashtags'] ? 'true' : 'false') . "\"";
        ?>

                <div class="account-item">
                    <h3><?php echo htmlspecialchars($accountName); ?></h3>
                    <button class="green-button" id="update-account-btn" <?php echo $dataAttributes; ?>>Update</button>
                    <form action="/accounts" method="POST">
                        <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($accountName); ?>">
                        <button class="red-button" id="delete-account-btn" name="delete_account">Delete</button>
                    </form>
                </div>
        <?php
            endif;
        endforeach;
        ?>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all update buttons
        const updateButtons = document.querySelectorAll('#update-account-btn');

        // Add event listener to all buttons
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the form fields
                const accountNameField = document.querySelector('#account_name');
                const keyField = document.querySelector('#key');
                const promptField = document.querySelector('#add-prompt');
                const linkField = document.querySelector('#link');
                const hashtagCheckbox = document.querySelector('#hashtags');

                // Get data from button
                const accountName = this.dataset.accountName;
                const key = this.dataset.key;
                const prompt = decodeURIComponent(this.dataset.prompt.replace(/\+/g, ' '));
                const link = decodeURIComponent(this.dataset.link.replace(/\+/g, ' '));
                const hashtags = this.dataset.hashtags;

                // Populate form fields with data
                accountNameField.value = accountName || '';
                keyField.value = key || '';
                promptField.value = prompt || '';
                linkField.value = link || '';
                hashtagCheckbox.checked = hashtags === 'true' ? true : false;
            });
        });
    });
</script>
