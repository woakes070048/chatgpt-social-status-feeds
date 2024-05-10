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

    // Initialize database object
    $db = new Database();
    $db->query("SELECT account_name FROM accounts WHERE account_owner = :accountOwner");
    $db->bind(':accountOwner', $accountOwner);
    $accounts = $db->resultSet();

    if (empty($accounts)) {
        echo 'Please set up an account!';
        return;
    }

    foreach ($accounts as $account) :
        $accountName = $account->account_name;
        $acctInfo = getAcctInfo($accountOwner, $accountName);
        $statuses = getStatusInfo($accountOwner, $accountName);
        $feedUrl = htmlspecialchars("/feeds.php?user={$accountOwner}&acct={$accountName}");
    ?>
        <div class="statuses">
            <h3><?php echo htmlspecialchars($accountName); ?> Statuses</h3>
            <?php if (!empty($statuses)) : ?>
                <ul>
                    <?php foreach ($statuses as $index => $status) : ?>
                        <?php if (!empty($status->text)) : ?>
                            <?php
                            $imagePath = $status->status_image !== null
                                ? "images/{$accountOwner}/{$accountName}/{$status->status_image}"
                                : 'assets/images/default.jpg';
                            $image = '<img src="' . htmlspecialchars($imagePath) . '" class="status-image">';
                            ?>
                            <li>
                                <?php echo $image; ?>
                                <p class="status-text"><?php echo htmlspecialchars($status->text); ?></p>
                                <?php echo shareButton($status->text, $imagePath, $acctInfo['link'], $index); ?>
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
                <p>Feed: <a href="<?php echo $feedUrl; ?>">Right Click Here</a></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
