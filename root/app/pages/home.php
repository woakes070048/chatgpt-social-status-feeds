<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/pages/home.php
 * Description: ChatGPT API Status Generator
*/
?>

<div class="account-box">
    <?php
    $accountOwner = $_SESSION['username'];

    // Get the user's account directories
    $accountDirs = glob(ACCOUNTS_DIR . "/{$accountOwner}/*", GLOB_ONLYDIR);

    if (empty($accountDirs)) {
        echo 'Please set up an account!';
        return;
    }

    foreach ($accountDirs as $accountDir) :
        $accountName = basename($accountDir);
        $acctInfo = getAcctInfo($accountOwner, $accountName);
        $statuses = getStatusInfo($accountOwner, $accountName);
        $cronUrl = htmlspecialchars("/cron.php?user={$accountOwner}&acct={$accountName}&key={$acctInfo['key']}");
        $feedUrl = htmlspecialchars("/feeds.php?user={$accountOwner}&acct={$accountName}&key={$acctInfo['key']}");
    ?>
        <div class="statuses">
            <h3><?php echo htmlspecialchars($accountName); ?> Statuses</h3>
            <?php if (!empty($statuses)) : ?>
                <ul>
                    <?php foreach ($statuses as $index => $status) : ?>
                        <?php if (!empty($status['text'])) : ?>
                            <?php
                            $imagePath = $status['status-image'] !== null
                                ? "images/{$accountOwner}/{$accountName}/{$status['status-image']}"
                                : 'assets/images/default.jpg';
                            $image = '<img src="' . htmlspecialchars($imagePath) . '" class="status-image">';
                            ?>
                            <li>
                                <?php echo $image; ?>
                                <p class="status-text"><?php echo htmlspecialchars($status['text']); ?></p>
                                <?php echo shareButton($status['text'], $imagePath, $acctInfo['link'], $index); ?>
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