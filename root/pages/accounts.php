<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /pages/accounts.php
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
    </div>

    <div class="edit-accounts-list">
        <h3>Accounts List</h3>
        <?php
        // Assuming you have a session variable 'username' for the logged-in user
        $username = $_SESSION['username'];

        // Get all .json files in the account directory of the logged-in user
        $accountFiles = glob("../storage/accounts/{$username}/*");

        foreach ($accountFiles as $accountFile) :
            $accountName = pathinfo($accountFile, PATHINFO_FILENAME); // Get the filename without extension as account name
            $accountData = getAccountData($username, $accountName);

            if ($accountData !== null) :
                // Decode the URL-encoded prompt value and replace "+" signs with spaces
                $decodedPrompt = urldecode($accountData['prompt']);
                $decodedPrompt = str_replace('+', ' ', $decodedPrompt);
                $accountData['prompt'] = $decodedPrompt;

                // Generate data attributes string
                $dataAttributes = "data-account-name=\"$accountName\" ";
                $dataAttributes .= "data-username=\"$username\" ";
                $dataAttributes .= 'data-prompt="' . urlencode($decodedPrompt) . '" '; // Add the data-prompt attribute
                $dataAttributes .= str_replace(['&', '='], ['" data-', '="'], http_build_query($accountData, 'data-'));
                $dataAttributes .= '"';
        ?>

                <div class="account-item">
                    <p><?php echo htmlspecialchars($accountName); ?></p>
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
    <?php
// Example usage:
$accountOwner = $_SESSION['username']; // Assuming the session variable contains the username
$accountDetails = getAccountDetails($accountOwner);

// Display the account details somewhere on your webpage
echo $accountDetails;
?>
</div>
