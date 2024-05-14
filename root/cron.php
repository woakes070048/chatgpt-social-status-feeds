<?php

/**
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: cron.php
 * Description: Handles scheduled tasks such as resetting API usage, running status updates, and clearing the IP blacklist.
 */

// Including necessary configuration and library files
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/lib/common-lib.php';
require_once __DIR__ . '/lib/status-lib.php';

// Checking for command line arguments to determine the job type
$jobType = $argv[1] ?? 'run_status'; // Default to 'run_status' if no argument provided

// Execute the appropriate function based on the job type
if ($jobType == 'reset_usage') {
    resetApiUsage();
} elseif ($jobType == 'run_status') {
    runStatusUpdateJobs();
} elseif ($jobType == 'clear_list') {
    clearIpBlacklist();
} elseif ($jobType == 'cleanup') {
    cleanupStatuses();
}

// Function to run status update jobs
function runStatusUpdateJobs()
{
    // Fetch all accounts from the database
    $accounts = getAllAccounts();
    $currentHour = date('H'); // Gets the current hour in 24-hour format
    $currentMinute = date('i'); // Gets the current minute
    $currentTimeSlot = sprintf("%02d", $currentHour) . ':' . $currentMinute;

    foreach ($accounts as $account) {
        // Account details
        $accountOwner = $account->username;
        $accountName = $account->account;

        // Check if cron schedule is set or not
        if ($account->cron === null || $account->cron === '') {
            continue; // Skip this account if cron is 'null' or empty (Off state)
        }

        $cron = explode(',', $account->cron); // Split cron schedule into an array

        // Check if the current time slot matches any cron schedule
        foreach ($cron as $scheduledHour) {
            if ($currentHour == $scheduledHour) {
                // Only proceed if a status hasn't been generated for this time slot
                if (!hasStatusBeenPosted($accountName, $accountOwner, $scheduledHour)) {
                    // Retrieve account and user information
                    $acctInfo = getAcctInfo($accountOwner, $accountName);
                    $userInfo = getUserInfo($accountOwner);

                    // Only proceed if the user has remaining API calls
                    if ($userInfo && $userInfo->used_api_calls < $userInfo->max_api_calls) {
                        $userInfo->used_api_calls += 1; // Increment used API calls

                        // Update user's used API calls in the database
                        $db = new Database();
                        $db->query("UPDATE users SET used_api_calls = :used_api_calls WHERE username = :username");
                        $db->bind(':used_api_calls', $userInfo->used_api_calls);
                        $db->bind(':username', $accountOwner);
                        $db->execute();

                        generateStatus($accountName, $accountOwner); // Generate the status update
                    }
                }
            }
        }
    }
}

// Function to reset API usage for all users
function resetApiUsage()
{
    $db = new Database();
    $db->query("UPDATE users SET used_api_calls = 0"); // Reset used API calls to 0
    $db->execute();
}

// New function to cleanup old statuses
function cleanupStatuses()
{
    $db = new Database();
    $accounts = getAllAccounts();
    foreach ($accounts as $account) {
        $accountName = $account->account;
        $accountOwner = $account->username;

        // Count the current number of statuses
        $db->query("SELECT COUNT(*) as count FROM status_updates WHERE account = :account");
        $db->bind(':account', $accountName);
        $result = $db->single();
        $statusCount = $result->count;

        // Check if the number of statuses exceeds the maximum allowed
        if ($statusCount > MAX_STATUSES) {
            // Calculate how many statuses to delete
            $deleteCount = $statusCount - MAX_STATUSES;

            // Delete the oldest statuses
            $db->query("DELETE FROM status_updates WHERE account = :account ORDER BY created_at ASC LIMIT :deleteCount");
            $db->bind(':account', $accountName);
            $db->bind(':deleteCount', $deleteCount);
            $db->execute();
        }
    }
}

// Function to fetch all accounts from the database
function getAllAccounts()
{
    $db = new Database();
    $db->query("SELECT * FROM accounts"); // Select all accounts
    return $db->resultSet(); // Return the result set
}

// Function to check if a status has been posted within a specific time slot
function hasStatusBeenPosted($accountName, $accountOwner, $currentTimeSlot)
{
    $db = new Database();
    // Calculate time window +/- 15 minutes
    $startTime = date('Y-m-d H:i:s', strtotime($currentTimeSlot . ' -15 minutes'));
    $endTime = date('Y-m-d H:i:s', strtotime($currentTimeSlot . ' +15 minutes'));

    // Query to check for existing status updates within the time window
    $db->query("SELECT COUNT(*) as count FROM status_updates WHERE username = :username AND account = :account AND created_at BETWEEN :startTime AND :endTime");
    $db->bind(':username', $accountOwner);
    $db->bind(':account', $accountName);
    $db->bind(':startTime', $startTime);
    $db->bind(':endTime', $endTime);
    $result = $db->single();

    return $result->count > 0; // Return true if a status has been posted, false otherwise
}

// Function to clear the IP blacklist
function clearIpBlacklist()
{
    $db = new Database();
    $db->query("DELETE FROM ip_blacklist"); // Delete all entries from the IP blacklist
    $db->execute();
}
