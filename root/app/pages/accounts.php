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
            <label for="add-prompt">Prompt:</label>
            <textarea name="prompt" id="add-prompt" required></textarea>
            <label for="link">Link:</label>
            <input type="text" name="link" id="link" required>
            <label for="image_prompt">Image Prompt:</label>
            <input type="text" name="image_prompt" id="image_prompt" required>
            <div class="hashtags">
                <label for="hashtags">Include Hashtags:</label>
                <input type="checkbox" name="hashtags" id="hashtags">
            </div>
            <button type="submit" class="green-button" id="submit-edit-account" name="edit_account">Add/Update Account</button>
        </form>
        <?php echo display_and_clear_messages(); ?>
        <?php echo generateAccountDetails(); ?>
    </div>

    <div class="edit-accounts-list">
        <?php
        $username = $_SESSION['username'];
        $db->query("SELECT * FROM accounts WHERE username = :username");
        $db->bind(':username', $username);
        $accounts = $db->resultSet();

        foreach ($accounts as $account) :
            $accountName = $account->account;
            $accountData = getAcctInfo($username, $accountName);

            $dataAttributes = "data-account-name=\"$accountName\" ";
            $dataAttributes .= "data-prompt=\"" . htmlspecialchars($accountData['prompt']) . "\" ";
            $dataAttributes .= "data-link=\"" . htmlspecialchars($accountData['link']) . "\" ";
            $dataAttributes .= "data-image_prompt=\"" . htmlspecialchars($accountData['image_prompt']) . "\" ";
            $dataAttributes .= "data-hashtags=\"" . ($accountData['hashtags'] ? 'true' : 'false') . "\"";
        ?>

            <div class="account-item">
                <h3><?php echo htmlspecialchars($accountName); ?></h3>
                <button class="green-button" id="update-account-btn" <?php echo $dataAttributes; ?>>Update</button>
                <form action="/accounts" method="POST">
                    <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($accountName); ?>">
                    <button class="red-button" id="delete-account-btn" name="delete_account">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('#update-account-btn');
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const accountNameField = document.querySelector('#account_name');
                const promptField = document.querySelector('#add-prompt');
                const linkField = document.querySelector('#link');
                const imagePromptField = document.querySelector('#image_prompt');
                const hashtagCheckbox = document.querySelector('#hashtags');

                accountNameField.value = this.dataset.accountName;
                promptField.value = decodeURIComponent(this.dataset.prompt.replace(/\+/g, ' '));
                linkField.value = decodeURIComponent(this.dataset.link.replace(/\+/g, ' '));
                imagePromptField.value = decodeURIComponent(this.dataset.image_prompt.replace(/\+/g, ' '));
                hashtagCheckbox.checked = this.dataset.hashtags === 'true';
            });
        });
    });
</script>
