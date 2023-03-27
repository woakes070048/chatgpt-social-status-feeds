<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: admin-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';

if (isset($_POST["create_account"])) {
    $accountName = trim($_POST["account_name"]);
    $key = trim($_POST["key"]);
    $prompt = trim($_POST["prompt"]);

    if (!empty($accountName) && !empty($key) && !empty($prompt)) {
        $accountData = [
            "account" => $accountName,
            "key" => $key,
            "prompt" => $prompt,
        ];

        $accountsDir = "../storage/accounts/";
        $accountFile = $accountsDir . $accountName;

        if (!file_exists($accountsDir)) {
            mkdir($accountsDir, 0755, true);
        }

        if (!file_exists($accountFile)) {
            file_put_contents($accountFile, serialize($accountData));
        } else {
            echo '<script>
        alert("Account with this name already exists.");
       </script>';
        }
    } elseif (empty($accountName) || empty($key) || empty($prompt)) {
        echo '<script>
        alert("Please fill in all the required fields.");
    </script>';
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update"])) {
        $accountName = trim($_POST["account"]);
        $key = trim($_POST["key"]);
        $prompt = trim($_POST["prompt"]);

        $accountData = [
            "account" => $accountName,
            "key" => $key,
            "prompt" => $prompt,
        ];

        $accountFile = "../storage/accounts/{$accountName}";

        if (file_exists($accountFile)) {
            file_put_contents($accountFile, serialize($accountData));
            echo '<script>
        alert("Account updated successfully.");
    </script>';
        } else {
            echo '<script>
        alert("Account does not exist.");
    </script>';
        }
    } elseif (isset($_POST["delete"])) {
        $accountName = trim($_POST["account"]);
        $accountFile = "../storage/accounts/{$accountName}";
        $statusFile = "../storage/statuses/{$accountName}";

        if (file_exists($accountFile)) {
            unlink($accountFile);
                       echo '<script>
        alert("Account deleted successfully.");
    </script>';
        } else {
            echo '<script>
        alert("Account does not exist.");
    </script>';
        }

        if (file_exists($statusFile)) {
            unlink($statusFile);
        }
    } elseif (isset($_POST["delete_status"])) {
        $accountName = trim($_POST["account"]);
        $index = (int) $_POST["index"];

        $statusFile = "../storage/statuses/{$accountName}";
        $statuses = file_exists($statusFile)
            ? unserialize(file_get_contents($statusFile))
            : [];

        if (isset($statuses[$index])) {
            unset($statuses[$index]);
            file_put_contents($statusFile, serialize($statuses));
        }
    }
}

function getAccounts()
{
    $accounts = [];
    $accountFiles = glob("../storage/accounts/*");

    foreach ($accountFiles as $accountFile) {
        $accountInfo = unserialize(file_get_contents($accountFile));
        $accountInfo["name"] = basename($accountFile);
        $accounts[] = $accountInfo;
    }

    return $accounts;
}

function getCronUrl($account, $Key)
{
    return "/cron.php?acct={$account}&key={$Key}";
}

function getFeedUrl($account, $Key)
{
    return "/feeds.php?acct={$account}&key={$Key}";
}