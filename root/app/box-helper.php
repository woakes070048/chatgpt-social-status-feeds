<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: box-helper.php
 * Description: ChatGPT API Status Generator
*/

require_once "../app/admin-helper.php";

foreach ($accounts as $account) :
    if ($account !== null) :
?>
        <div class="account-box">
            <div class="statuses">
                <h3><?php echo htmlspecialchars($account["account"]); ?> Statuses</h3>
                <?php
                $statusFile = "../storage/statuses/{$account["name"]}";
                $statuses = file_exists($statusFile) ? unserialize(file_get_contents($statusFile)) : [];
                $imageFile = "images/{$account["name"]}/img";
                $images = file_exists($imageFile) ? file($imageFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
                ?>
                <ul>
                    <?php if (!empty($statuses)) :
                        $count = count($statuses);
                        foreach ($statuses as $index => $status) :
                            if (!empty($status)) :
                                $image = "";
                                if (!empty($images[$index])) {
                                    if ($images[$index] !== '_NOIMAGE_') {
                                        $image = '<img src="' . htmlspecialchars($images[$index]) . '" class="status-image">';
                                    }
                                }
                    ?>
                                <li>
                                    <?php echo $image . htmlspecialchars($status['text'], ENT_QUOTES, "UTF-8"); ?>
                                    <form class="delete-status-form" action="<?php echo htmlspecialchars(
                                                                                    $_SERVER["PHP_SELF"]
                                                                                ); ?>" method="POST">
                                        <?php if (!empty($account)) : ?>
                                            <input type="hidden" name="account" value="<?php echo htmlspecialchars($account["name"]); ?>">
                                        <?php endif; ?>
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" class="delete-status-button" name="delete_status">Delete
                                        </button>
                                    </form>
                                </li>
                    <?php endif;
                        endforeach;
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
                <button class="images-btn" id="images-btn">Images</button>
            </div>
        </div>
        <div class="images-popup" id="images-popup">
            <div class="images-box">
                <div class="images-form">
                    <h3>Images</h3>
                    <div class="images-list">
                        <?php
                        $image_folder = "images/" . $account['name'] . "/";
                        $images = glob($image_folder . "*.{jpg,jpeg,png}", GLOB_BRACE);
                        $count = count($images);
                        for ($i = 0; $i < $count; $i++) {
                            $image_name = basename($images[$i]);
                            echo '<div class="image-item">';
                            echo '<img src="' . htmlspecialchars($images[$i]) . '" alt="' . htmlspecialchars($image_name) . '" width="150" height="150">';
                            echo '<form class="delete-form" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">';
                            echo '<input type="hidden" name="account_name" value="' . htmlspecialchars($account['name']) . '">';
                            echo '<input type="hidden" name="image_name" value="' . htmlspecialchars($image_name) . '">';
                            echo '<button class="delete-image" type="submit" name="delete-image">Delete</button>';
                            echo '</form>';
                            echo '</div>';
                            if (($i + 1) % 3 == 0) {
                                echo '<div style="clear:both;"></div>';
                            }
                        }
                        ?>
                    </div>
                    <form class="upload-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                        <label for="image-file">Upload Image:</label>
                        <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($account['name']); ?>">
                        <input type="file" name="image-file">
                        <button class="upload-image" type="submit" name="upload-image">Upload Image</button>
                        <button type="button" class="close-btn" id="close-btn">Close</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>