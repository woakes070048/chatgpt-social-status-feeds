<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../lib/rss-lib.php
 * Description: ChatGPT API Status Generator
 */

function outputRssFeed($accountName, $accountOwner, $acctInfo, $key)
{
    // Paths according to new storage structure
    $statusPath = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/statuses";
    $imageFolderPath = "images/{$accountOwner}/{$accountName}/";

    // Read the status file and decode it into an associative array
    $statusInfo = file_exists($statusPath) ? json_decode(file_get_contents($statusPath), true) : [];
 
    header('Content-Type: application/rss+xml; charset=utf-8');
    $rssUrl = DOMAIN . '/feeds.php?acct=' . $accountName . '&key=' . $key;
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . PHP_EOL;
    echo '<channel>' . PHP_EOL;
    echo '<title>Facebook Statuses - ' . htmlspecialchars($accountName) . '</title>' . PHP_EOL;
    echo '<link>' . htmlspecialchars($rssUrl) . '</link>' . PHP_EOL;
    echo '<description>Facebook status updates generated by GPT API for ' . htmlspecialchars($accountName) . '</description>' . PHP_EOL;
    echo '<language>en-us</language>' . PHP_EOL;

    foreach ($statusInfo as $status) {
        if (!empty($status)) {
            $rssImageTag = '';
            if (isset($status['status-image']) && !empty($status['status-image'])) {
                $imageName = trim($status['status-image']);
                $imageUrl = DOMAIN . '/' . $imageFolderPath . $imageName;
                $rssImageTag = '<img src="' . htmlspecialchars($imageUrl) . '"/>' . PHP_EOL;
            }

            $description = htmlspecialchars($status['text']);
            echo '<item>' . PHP_EOL;
            echo '<guid isPermaLink="false">' . md5($status['text']) . '</guid>' . PHP_EOL;
            echo '<pubDate>' . date('r', strtotime($status['created_at'])) . '</pubDate>' . PHP_EOL;
            echo '<link>' . htmlspecialchars($acctInfo['link']) . '</link>' . PHP_EOL;
            echo '<title>' . htmlspecialchars($accountName) . ' | Status ' . (count($statusInfo) - $index) . '</title>' . PHP_EOL;
            echo '<description><![CDATA[' . $description . ']]></description>' . PHP_EOL;
            echo '<content:encoded><![CDATA[' . $rssImageTag . ']]></content:encoded>' . PHP_EOL;
            echo '<category>' . htmlspecialchars($accountName) . '</category>' . PHP_EOL;
            echo '<author><name>' . htmlspecialchars($accountOwner) . '</name></author>' . PHP_EOL;
            echo '</item>' . PHP_EOL;
        }
    }

    echo '</channel>' . PHP_EOL;
    echo '</rss>';
}
?>
