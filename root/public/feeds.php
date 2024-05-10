<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: feeds.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';
require_once '../lib/common-lib.php';
require_once '../lib/rss-lib.php';

// Check if the required query parameters are present in the URL
if (!isset($_GET['user']) || !isset($_GET['acct'])) {
    echo 'Error: Missing required parameters';
    exit();
} elseif (isset($_GET['user']) && isset($_GET['acct'])) {
    $accountOwner = $_GET['user'];
    $accountName = $_GET['acct'];

    // Fetch account information using the provided username and account name
    $acctInfo = getAcctInfo($accountOwner, $accountName);
    if ($acctInfo) {
        // Call the function to output RSS feed
        outputRssFeed($accountName, $accountOwner, $acctInfo);
    } else {
        echo 'Error: Account information could not be retrieved';
    }
}
