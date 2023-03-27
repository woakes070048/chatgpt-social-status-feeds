<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: cron.php
 * Description: ChatGPT API Status Generator
 */

// Check if the required query parameters are present in the URL
if (!isset($_GET['acct']) || !isset($_GET['key'])) {
    // If the parameters are missing, show an error message and exit the script
    echo 'Error: Missing required parameters';
    exit();
}

require_once '../app/status-helper.php';

if (isset($_GET['acct']) && isset($_GET['key'])) {
    $account = $_GET['acct'];
    $key = $_GET['key'];

    $accountFile = "../storage/accounts/{$account}";

    if (file_exists($accountFile)) {
        $accountInfo = unserialize(file_get_contents($accountFile));

        if ($accountInfo['key'] === $key) {
            generateStatus($account, $key, $accountInfo['prompt']);
            echo 'Status generated successfully.';
        } else {
            echo 'Invalid key.';
        }
    } else {
        echo 'Invalid account.';
    }
} else {
    echo 'Missing account or key.';
}
