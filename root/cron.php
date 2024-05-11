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
    resetApiUsage(); // Reset used API calls for all users
} elseif ($jobType == 'run_status') {
    runStatusUpdateJobs(); // Run status update jobs
} elseif ($jobType == 'clear_list') {
    clearIpBlacklist(); // Clear the IP blacklist
}

// Function to run status update jobs
function runStatusUpdateJobs()
{
    // Fetch all accounts from the database
    $accounts = getAllAccounts();
    $currentDate = date('Y-m-d'); // Gets the current date

    foreach ($accounts as $account) {
        // Account details
        $accountOwner = $account->username;
        $accountName = $account->account;

        // Retrieve account and user information
        $acctInfo = getAcctInfo($accountOwner, $accountName);
        $userInfo = getUserInfo($accountOwner);

        // Count the number of statuses posted today for this account
        $statusCountToday = countStatusesPostedToday($accountName, $accountOwner, $currentDate);

        // Check if the number of statuses posted is less than the cron setting
        if ($statusCountToday < $acctInfo->cron) {
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

// Function to count the number of statuses posted today
function countStatusesPostedToday($accountName, $accountOwner, $currentDate)
{
    $db = new Database();
    $db->query("SELECT COUNT(*) as count FROM status_updates WHERE username = :username AND account = :account AND DATE(created_at) = :currentDate");
    $db->bind(':username', $accountOwner);
    $db->bind(':account', $accountName);
    $db->bind(':currentDate', $currentDate);
    $result = $db->single();
    return $result->count;
}


// Function to reset API usage for all users
function resetApiUsage()
{
    $db = new Database();
    $db->query("UPDATE users SET used_api_calls = 0"); // Reset used API calls to 0
    $db->execute();
}

// Function to fetch all accounts from the database
function getAllAccounts()
{
    $db = new Database();
    $db->query("SELECT * FROM accounts"); // Select all accounts
    return $db->resultSet(); // Return the result set
}

// Function to check if a status has been posted within a specific time window
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
