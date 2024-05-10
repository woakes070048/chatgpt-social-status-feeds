<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: cron.php
 * Description: ChatGPT API Status Generator
 */

require_once './config.php';
require_once '/lib/common-lib.php';
require_once '/lib/status-lib.php';

// Checking for command line arguments for job type
$jobType = $argv[1] ?? 'run_status'; // Default to 'run_status' if no argument provided

if ($jobType == 'reset_usage') {
    // Reset used API calls for all users
    resetApiUsage();
} elseif ($jobType == 'run_status') {
    // Run status update jobs
    runStatusUpdateJobs();
} elseif ($jobType == 'clear_list') {
    // Clear the IP blacklist
    clearIpBlacklist();
}

function runStatusUpdateJobs()
{
    // Define times for cron jobs
    $cronTimes = [
        1 => ['12:00'],
        2 => ['09:00', '12:00'],
        3 => ['09:00', '12:00', '18:00']
    ];

    // Current time
    $currentHour = date('H:00'); // Gets the current hour in HH:00 format
    $currentTimeSlot = date('Y-m-d H:i:s');

    // Fetch all accounts
    $accounts = getAllAccounts();

    foreach ($accounts as $account) {
        $accountOwner = $account->username;
        $accountName = $account->account;

        $acctInfo = getAcctInfo($accountOwner, $accountName);
        $userInfo = getUserInfo($accountOwner);

        if (in_array($currentHour, $cronTimes[$acctInfo->cron])) {
            if (!hasStatusBeenPosted($accountName, $accountOwner, $currentTimeSlot)) {
                if ($userInfo && $userInfo->used_api_calls < $userInfo->max_api_calls) {
                    $userInfo->used_api_calls += 1;
                    // Update user info in the database

                    $prompt = $acctInfo->prompt;
                    $link = $acctInfo->link;
                    $hashtags = $acctInfo->hashtags;
                    generateStatus($accountName, $accountOwner, $prompt, $link, $hashtags);
                }
            }
        }
    }
}

function resetApiUsage()
{
    $db = new Database();
    $db->query("UPDATE users SET used_api_calls = 0");
    $db->execute();
}

function getAllAccounts()
{
    $db = new Database();
    $db->query("SELECT * FROM accounts");
    return $db->resultSet();
}

function hasStatusBeenPosted($accountName, $accountOwner, $currentTimeSlot)
{
    $db = new Database();
    // Calculate time window
    $startTime = date('Y-m-d H:i:s', strtotime($currentTimeSlot . ' -15 minutes'));
    $endTime = date('Y-m-d H:i:s', strtotime($currentTimeSlot . ' +15 minutes'));

    // Query to check for status within the +/- 15-minute window of the current time slot
    $db->query("SELECT COUNT(*) as count FROM status_updates WHERE username = :username AND account = :account AND created_at BETWEEN :startTime AND :endTime");
    $db->bind(':username', $accountOwner);
    $db->bind(':account', $accountName);
    $db->bind(':startTime', $startTime);
    $db->bind(':endTime', $endTime);
    $result = $db->single();

    // Return true if a status has been posted in the time window, false otherwise
    return $result->count > 0;
}

function clearIpBlacklist()
{
    $db = new Database();
    $db->query("DELETE FROM ip_blacklist");
    $db->execute();
}
