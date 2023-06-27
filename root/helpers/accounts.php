<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /helpers/accounts.php
 * Description: ChatGPT API Status Generator
 */

 function getAccountDetails($accountOwner) {
     // Path to the JSON file
     $filePath = "../storage/users/{$accountOwner}";

     // Read the JSON file
     $jsonData = file_get_contents($filePath);

     if ($jsonData === false) {
         // File not found or error reading the file
         return null;
     }

     // Decode the JSON data into an associative array
     $data = json_decode($jsonData, true);

     if ($data === null) {
         // Error decoding JSON data
         return null;
     }

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

     // Return the formatted data
     return $output;
 }

 // Example usage:
 $accountOwner = $_SESSION['username']; // Assuming the session variable contains the username
 $accountDetails = getAccountDetails($accountOwner);
