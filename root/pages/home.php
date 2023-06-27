<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /pages/home.php
 * Description: ChatGPT API Status Generator
*/
?>
<div class="account-box">
    <?php
    $accounts = [];  // Initialize accounts array
    $accountOwner = $_SESSION['username'];

    // The user will only see their accounts
    foreach (glob("../storage/accounts/{$accountOwner}/*") as $accountFile) {
        // Parse the account name from the file structure
        $accountName = basename($accountFile);
        $accounts[] = $accountName;
    }

    if (empty($accounts)) {
        echo 'Please set up an account!';
        return;
    }

    foreach ($accounts as $accountName) :
        $accountFile = "../storage/accounts/{$accountOwner}/{$accountName}";
        if (!file_exists($accountFile)) {
            // Skip this account if the file does not exist
            continue;
        }

        $account = json_decode(file_get_contents($accountFile), true);

        $statusFile = "../storage/statuses/{$accountOwner}/{$accountName}";
        if (!file_exists($statusFile)) {
            // Status file does not exist, initialize statuses as empty array
            $statuses = [];
        } else {
            $statusesJson = file_get_contents($statusFile);
            $statuses = !empty($statusesJson) ? json_decode($statusesJson, true) : [];
        }

        $imageFile = "images/{$accountName}/img";
        $images = file_exists($imageFile) ? file($imageFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

        $cronUrl = htmlspecialchars(getCronUrl($accountName, $account['key']));
        $feedUrl = htmlspecialchars(getFeedUrl($accountName, $account['key']));
    ?>
        <div class="statuses">
            <h3><?php echo htmlspecialchars($accountName); ?> Statuses</h3>
            <?php if (!empty($statuses)) : ?>
                <ul>
                    <?php foreach ($statuses as $index => $status) : ?>
                        <?php if (!empty($status['text'])) : ?>
                            <?php
                            $image = "";
                            if (!empty($images[$index])) {
                                if ($images[$index] !== '_NOIMAGE_') {
                                    $image = '<img src="' . htmlspecialchars($images[$index]) . '" class="status-image">';
                                }
                            }
                            ?>
                            <li>
                                <?php echo $image; ?>
                                <p class="status-text"><?php echo htmlspecialchars($status['text']); ?></p>

                                <form class="delete-status-form" action="/home" method="POST">
                                    <input type="hidden" name="account" value="<?php echo htmlspecialchars($accountName); ?>">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($accountOwner); ?>">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" class="delete-status-button" name="delete_status">Delete</button>
                                </form>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No statuses available.</p>
            <?php endif; ?>

            <div class="cron-feed-addresses">
                <p>Cron Job: <a href="<?php echo $cronUrl; ?>">Right Click Here</a></p>
                <p>Feed: <a href="<?php echo $feedUrl; ?>">Right Click Here</a></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>