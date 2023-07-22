<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /lib/common.php
 * Description: ChatGPT API Status Generator
 */

 //This function retrieves user information from the user file.
function getUserInfo($username) {
    $filePath = USERS_DIR . "/{$username}";
    if (file_exists($filePath)) {
        $userData = file_get_contents($filePath);
        $userInfo = json_decode($userData, true);
        return $userInfo;
    } else {
        return null; // File not found
    }
}

//This function retrieves account information from the account file.
function getAcctInfo($username, $account) {
    $filePath = ACCOUNTS_DIR . "/{$username}/{$account}/acct";
    if (file_exists($filePath)) {
        $acctData = file_get_contents($filePath);
        $acctInfo = json_decode($acctData, true);
        return $acctInfo;
    } else {
        return null; // File not found
    }
}

//This function retrieves status information from the statuses file.
function getStatusInfo($username, $account) {
    $filePath = ACCOUNTS_DIR . "/{$username}/{$account}/statuses";
    if (file_exists($filePath)) {
        $statusData = file_get_contents($filePath);
        $statusInfo = json_decode($statusData, true);
        return $statusInfo;
    } else {
        return null; // File not found
    }
}
