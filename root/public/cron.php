<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: cron.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';
require_once '../lib/common-lib.php';
require_once '../lib/status-lib.php';


if (!isset($_GET['user']) || !isset($_GET['acct']) || !isset($_GET['key'])) {
    echo 'Error: Missing required parameters';
    exit();
} elseif (isset($_GET['user']) && isset($_GET['acct']) && isset($_GET['key'])) {
    $accountOwner = $_GET['user'];
    $accountName = $_GET['acct'];
    $key = $_GET['key'];

    $acctInfo = getAcctInfo($accountOwner, $accountName);
    $userInfo = getUserInfo($accountOwner);

    if ($acctInfo && $userInfo) {
        if ($userInfo['used-api-calls'] < $userInfo['max-api-calls']) {
            $userInfo['used-api-calls'] += 1;
            // Save updated userInfo back to the user's file.

            $prompt = $acctInfo['prompt'];
            $link = $acctInfo['link'];
            $hashtags = $acctInfo['hashtags'];
            generateStatus($accountName, $accountOwner, $key, $prompt, $link, $hashtags);
            echo '<script type="text/javascript">
            alert("Status Created!.");
            window.location.href = window.location.href;
            </script>';
            exit;
        }
    }
}

