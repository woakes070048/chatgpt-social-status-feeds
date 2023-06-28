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
if (!isset($_GET['user']) || !isset($_GET['acct']) || !isset($_GET['key'])) {
    echo 'Error: Missing required parameters';
    exit();
} elseif (isset($_GET['user']) && isset($_GET['acct']) && isset($_GET['key'])) {
    $accountOwner = $_GET['user'];
    $accountName = $_GET['acct'];
    $key = $_GET['key'];

    $acctInfo = getAcctInfo($accountOwner, $accountName); {
            outputRssFeed($accountName, $accountOwner, $acctInfo, $key);

    }
}
