<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/accounts-forms.php
 * Description: ChatGPT API Status Generator
 */

require_once '../lib/db.php'; // Make sure this points to your database access class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["edit_account"])) {
        $accountOwner = $_SESSION["username"];
        $accountName = trim($_POST["account_name"]);
        $key = trim($_POST["key"]);
        $prompt = trim($_POST["prompt"]);
        $hashtags = isset($_POST["hashtags"]) ? 1 : 0;
        $link = trim($_POST["link"]);

        if (!empty($accountName) && !empty($key) && !empty($prompt) && !empty($link)) {
            $db = new Database();

            // Update or insert account data
            $db->query("REPLACE INTO accounts (account_owner, account_name, key, prompt, hashtags, link) VALUES (:accountOwner, :accountName, :key, :prompt, :hashtags, :link)");
            $db->bind(':accountOwner', $accountOwner);
            $db->bind(':accountName', $accountName);
            $db->bind(':key', $key);
            $db->bind(':prompt', $prompt);
            $db->bind(':hashtags', $hashtags);
            $db->bind(':link', $link);
            $db->execute();

            echo '<script type="text/javascript">alert("Account has been created or modified"); window.location.href = window.location.href;</script>';
            exit;
        } else {
            echo '<script type="text/javascript">alert("A field is missing or has incorrect data. Please try again."); window.location.href = window.location.href;</script>';
            exit;
        }
    } elseif (isset($_POST["delete_account"])) {
        $accountName = trim($_POST["account_name"]);
        $accountOwner = $_SESSION["username"];

        $db = new Database();

        // Delete the account from the database
        $db->query("DELETE FROM accounts WHERE account_owner = :accountOwner AND account_name = :accountName");
        $db->bind(':accountOwner', $accountOwner);
        $db->bind(':accountName', $accountName);
        $db->execute();

        echo '<script type="text/javascript">alert("Account Deleted"); window.location.href = window.location.href;</script>';
        exit;
    }
}
?>
