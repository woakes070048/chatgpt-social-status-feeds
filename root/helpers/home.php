<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /helpers/home.php
 * Description: ChatGPT API Status Generator
 */

// Declare your URL construction functions outside of the loop
function getCronUrl($account, $key)
{
    return "/cron.php?acct={$account}&key={$key}";
}
function getFeedUrl($account, $key)
{
    return "/feeds.php?acct={$account}&key={$key}";
}