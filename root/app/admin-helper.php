<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: admin-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';

function getAccounts()
{
    $accounts = [];
    $accountFiles = glob("../storage/accounts/*");

    foreach ($accountFiles as $accountFile) {
        $accountInfo = unserialize(file_get_contents($accountFile));
        if ($accountInfo !== false) {
            $accountInfo["name"] = basename($accountFile);
            $accounts[] = $accountInfo;
        }
    }

    return $accounts;
}

$accounts = getAccounts();

function getCronUrl($account, $Key)
{
    $Key = $Key ?? '';
    return "/cron.php?acct={$account}&key={$Key}";
}

function getFeedUrl($account, $Key)
{
    $Key = $Key ?? '';
    return "/feeds.php?acct={$account}&key={$Key}";
}
