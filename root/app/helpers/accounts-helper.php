<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/helpers/accounts-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once 'lib/db.php'; // Include your database class

function generateAccountDetails() {
    $accountOwner = $_SESSION['username'];

    $db = new Database();
    $db->query("SELECT total_accounts, max_api_calls, used_api_calls FROM users WHERE username = :username");
    $db->bind(':username', $accountOwner);
    $data = $db->single();

    if ($data) {
        // Extract the required fields from the data
        $totalAccounts = $data->total_accounts;
        $maxApiCalls = $data->max_api_calls;
        $usedApiCalls = $data->used_api_calls;

        // Format the data into a nice box
        $output = "<div class=\"account-details\">";
        $output .= "<p>Max Accounts: " . htmlentities($totalAccounts) . "</p>";
        $output .= "<p>Max API Calls: " . htmlentities($maxApiCalls) . "</p>";
        $output .= "<p>Used API Calls: " . htmlentities($usedApiCalls) . "</p>";
        $output .= "</div>";
    } else {
        $output = "<div class=\"account-details\">No account details available.</div>";
    }
    return $output;
}
?>
