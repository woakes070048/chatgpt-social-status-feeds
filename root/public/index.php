<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: index.php
 * Description: ChatGPT API Status Generator
*/
session_start();
require_once "../app/auth-helper.php";
require_once "../app/admin-helper.php";
$accounts = getAccounts();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles.css">
    <script src="/assets/script.js"></script>
    <title>Dashboard</title>
</head>

<body>
    <header>
        <div class="logo"><img src="/assets/logo.png"></div>
        <div class="logout-button">
            <form action="<?php echo htmlspecialchars(
                                $_SERVER["PHP_SELF"]
                            ); ?>" method="POST">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>
    <div class="container">
        <?php foreach ($accounts as $account) :
            if ($account !== null) : ?>
                <div class="account-box">
                    <div class="statuses">
                        <h3><?php echo htmlspecialchars($account["account"]); ?> Statuses</h3>
                        <?php
                        $statusFile = "../storage/statuses/{$account["name"]}";
                        $statuses = file_exists($statusFile)
                            ? unserialize(file_get_contents($statusFile))
                            : [];
                        ?>
                        <ul>
                            <?php if (!empty($statuses)) :
                                foreach ($statuses as $index => $status) {
                                    if (!empty($status)) { ?>
                                        <li>
                                            <?php echo htmlspecialchars($status ?? "", ENT_QUOTES, "UTF-8"); ?>
                                            <form class="delete-status-form" action="<?php echo htmlspecialchars(
                                                                                            $_SERVER["PHP_SELF"]
                                                                                        ); ?>" method="POST">
                                                <?php if (!empty($account)) : ?>
                                                    <input type="hidden" name="account" value="<?php echo htmlspecialchars(
                                                                                                    $account["name"]
                                                                                                ); ?>">
                                                <?php endif; ?>
                                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                                <button type="submit" class="delete-status-button" name="delete_status">Delete</button>
                                            </form>
                                        </li>
                            <?php }
                                }
                            endif; ?>
                        </ul>
                    </div>
                    <div class="cron-feed-addresses">
                        <p>Cron Job: <a href="<?php echo htmlspecialchars(getCronUrl($account['name'], $account['key'])); ?>"><?php echo htmlspecialchars(getCronUrl($account['name'], $account['key'])); ?></a></p>
                        <p>Feed: <a href="<?php echo htmlspecialchars(getFeedUrl($account['name'], $account['key'])); ?>"><?php echo htmlspecialchars(getFeedUrl($account['name'], $account['key'])); ?></a></p>
                    </div>
                    <div class="account-options">
                        <form action="/cron.php" method="GET">
                            <input type="hidden" name="acct" value="<?php echo htmlspecialchars($account['name']); ?>">
                            <input type="hidden" name="key" value="<?php echo htmlspecialchars($account['key']); ?>">
                            <button class="trigger-cron" type="submit">Trigger Cron Job</button>
                        </form>
                        <button class="update-account-btn" id="update-account-btn" data-account-name="<?php echo htmlspecialchars($account['name']); ?>" data-key="<?php echo htmlspecialchars($account['key']); ?>" data-prompt="<?php echo htmlspecialchars($account['prompt']); ?>" data-link="<?php echo htmlspecialchars($account['link']); ?>" data-hashtags="<?php echo htmlspecialchars($account['hashtags'] ? 'true' : 'false'); ?>">Update Account</button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <button id="add-account-btn">Add Account</button>

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
    <footer>
        <p>&copy;
            <?php echo date("Y"); ?> Vontainment. All Rights Reserved.
        </p>
    </footer>
</body>

</html>