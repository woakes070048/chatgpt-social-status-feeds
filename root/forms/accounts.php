<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /forms/accounts.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["edit_account"])) {
        $accountName = trim($_POST["account_name"]);
        $key = trim($_POST["key"]);
        $prompt = trim($_POST["prompt"]);
        $hashtags = isset($_POST["hashtags"]) ? true : false;
        $link = trim($_POST["link"]);
        $accountOwner = $_SESSION["username"];

        if (!empty($accountName) && !empty($key) && !empty($prompt) && !empty($link)) { // Make sure all the required fields are not empty
            $accountData = [
                "account" => $accountName,
                "key" => $key,
                "prompt" => $prompt,
                "hashtags" => $hashtags,
                "link" => $link,
                "owner" => $accountOwner
            ];

            $accountFile = "../storage/accounts/{$accountOwner}/{$accountName}";

            // Existing account is being edited
            if (file_exists($accountFile)) {
                file_put_contents($accountFile, json_encode($accountData));
            } else {
                // New account is being added
                $accountFile = "../storage/accounts/{$accountOwner}/{$accountName}";

                file_put_contents($accountFile, json_encode($accountData));

                echo '<script type="text/javascript">
                alert("Account has been created or modified");
                window.location.href = window.location.href;
                </script>';
                    exit;
                }
        } else { // Check if any required fields are empty
            echo '<script type="text/javascript">
            alert("A field is missing or has incorrect data. Please try again.");
            window.location.href = window.location.href;
            </script>';
            exit;
        }
    }

} elseif (isset($_POST["delete_account"])) {
    $accountName = trim($_POST["account"]);
    $accountOwner = trim($_POST["owner"]);
    $accountFile = "../storage/accounts/{$accountOwner}/{$accountName}";
    $statusFile = "../storage/statuses/{$accountOwner}/{$accountName}";

    if (file_exists($accountFile)) {
        unlink($accountFile);

        if (file_exists($statusFile)) {
            unlink($statusFile);
        }

        $imageFolder = "../storage/images/{$accountName}/"; // Added "../" to the image folder path
        if (file_exists($imageFolder)) {
            $files = glob($imageFolder . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($imageFolder);
        }

        // Account Deleted
        echo '<script type="text/javascript">
    alert("Account Deleted");
    window.location.href = window.location.href;
    </script>';
        exit;
    }
}