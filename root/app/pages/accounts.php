<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/pages/accounts.php
 * Description: ChatGPT API Status Generator
*/

// Initialize the Database object
$db = new Database();

?>

<main class="flex-container">
    <section id="left-col">
        <h3>Add/Update New Account</h3>
        <form class="edit-account-form" action="/accounts" method="POST">
            <label for="account">Account Name:</label>
            <input type="text" name="account" id="account" required>
            <label for="platform">Platform:</label>
            <select name="platform" id="platform" required>
                <option value="facebook">Facebook</option>
                <option value="twitter">Twitter</option>
                <option value="instagram">Instagram</option>
            </select>
            <label for="add-prompt">Prompt:</label>
            <textarea name="prompt" id="add-prompt" required></textarea>
            <label for="link">Link:</label>
            <input type="text" name="link" id="link" required>
            <label for="image_prompt">Image Prompt:</label>
            <input type="text" name="image_prompt" id="image_prompt" required>
            <label for="cron">Cron:</label> <!-- Added cron label -->
            <select name="cron" id="cron" required> <!-- Dropdown for cron -->
                <option value="1">1 post per day</option>
                <option value="2">2 posts per day</option>
                <option value="3">3 posts per day</option>
                <option value="4">4 posts per day</option>
                <option value="5">5 posts per day</option>
            </select>
            <div class="hashtags">
                <label for="hashtags">Include Hashtags:</label>
                <input type="checkbox" name="hashtags" id="hashtags">
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="edit-account-button green-button" name="edit_account">Add/Update Account</button>
        </form>
        <div id="error-msg"><?php echo display_and_clear_messages(); ?></div>
        <?php echo generateAccountDetails(); ?>
    </section>

    <section id="right-col">
        <?php
        $username = $_SESSION['username'];
        $db->query("SELECT * FROM accounts WHERE username = :username");
        $db->bind(':username', $username);
        $accounts = $db->resultSet();

        foreach ($accounts as $account) :
            $accountName = $account->account;
            $accountData = getAcctInfo($username, $accountName);

            $dataAttributes = "data-account-name=\"{$accountName}\" ";
            $dataAttributes .= "data-prompt=\"" . htmlspecialchars($accountData->prompt) . "\" ";
            $dataAttributes .= "data-link=\"" . htmlspecialchars($accountData->link) . "\" ";
            $dataAttributes .= "data-image_prompt=\"" . htmlspecialchars($accountData->image_prompt) . "\" ";
            $dataAttributes .= "data-hashtags=\"" . ($accountData->hashtags ? 'true' : 'false') . "\" ";
            $dataAttributes .= "data-cron=\"" . htmlspecialchars($accountData->cron) . "\" ";
            $dataAttributes .= "data-platform=\"" . htmlspecialchars($accountData->platform) . "\""; // Ensure this is correctly added

        ?>

            <div class="item-box">
                <h3><?php echo htmlspecialchars($accountName); ?></h3>
                <button class="update-account-button green-button" id="update-button" <?php echo $dataAttributes; ?>>Update</button>
                <form class=" delete-account-form" action="/accounts" method="POST">
                    <input type="hidden" name="account" value="<?php echo htmlspecialchars($accountName); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button class="delete-account-button red-button" name="delete_account">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('#update-button');
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const accountNameField = document.querySelector('#account');
                const promptField = document.querySelector('#add-prompt');
                const linkField = document.querySelector('#link');
                const imagePromptField = document.querySelector('#image_prompt');
                const hashtagCheckbox = document.querySelector('#hashtags');
                const cronField = document.querySelector('#cron');
                const platformSelect = document.querySelector('#platform');

                accountNameField.value = this.dataset.accountName;
                promptField.value = decodeURIComponent(this.dataset.prompt.replace(/\+/g, ' '));
                linkField.value = decodeURIComponent(this.dataset.link.replace(/\+/g, ' '));
                imagePromptField.value = decodeURIComponent(this.dataset.image_prompt.replace(/\+/g, ' '));
                hashtagCheckbox.checked = this.dataset.hashtags === 'true';
                cronField.value = this.dataset.cron;
                platformSelect.value = this.dataset.platform; // Set the platform dropdown

                // Set the account name field as readonly when updating
                accountNameField.readOnly = true;
            });
        });
    });
</script>
