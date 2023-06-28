<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/helpers/accounts-helper.php
 * Description: ChatGPT API Status Generator
 */

function generateAccountDetails() {
        $accountOwner = $_SESSION['username'];

        $data = getUserInfo($accountOwner);

        if ($data !== null) {
            // Extract the required fields from the data
            $totalAccounts = isset($data['total-accounts']) ? $data['total-accounts'] : '';
            $maxApiCalls = isset($data['max-api-calls']) ? $data['max-api-calls'] : '';
            $usedApiCalls = isset($data['used-api-calls']) ? $data['used-api-calls'] : '';

            // Format the data into a nice box
            $output = "<div class=\"account-details\">";
            $output .= "<p>Max Accounts: " . htmlentities($totalAccounts) . "</p>";
            $output .= "<p>Max API Calls: " . htmlentities($maxApiCalls) . "</p>";
            $output .= "<p>Used API Calls: " . htmlentities($usedApiCalls) . "</p>";
            $output .= "</div>";
        }
    return $output;
}
