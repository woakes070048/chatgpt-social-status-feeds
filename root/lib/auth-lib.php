<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: auth-helper.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["edit_account"])) {
        $accountOwner = $_SESSION["username"];
        $accountName = trim($_POST["account_name"]);
        $prompt = trim($_POST["prompt"]);
        $hashtags = isset($_POST["hashtags"]) ? 1 : 0;
        $link = trim($_POST["link"]);
        $imagePrompt = trim($_POST["image_prompt"]);

        if (!empty($accountName) && !empty($prompt) && !empty($link) && !empty($imagePrompt)) {
            $db = new Database();
            $db->query("REPLACE INTO accounts (account_owner, account_name, prompt, hashtags, link, image_prompt) VALUES (:accountOwner, :accountName, :prompt, :hashtags, :link, :imagePrompt)");
            $db->bind(':accountOwner', $accountOwner);
            $db->bind(':accountName', $accountName);
            $db->bind(':prompt', $prompt);
            $db->bind(':hashtags', $hashtags);
            $db->bind(':link', $link);
            $db->bind(':imagePrompt', $imagePrompt);
            $db->execute();

            $_SESSION['messages'][] = "Account has been created or modified";
            header("Location: {$_SERVER['PHP_SELF']}"); // Redirect to the same page to avoid form resubmission
            exit;
        } else {
            $_SESSION['messages'][] = "A field is missing or has incorrect data. Please try again.";
            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        }
    } elseif (isset($_POST["delete_account"])) {
        $accountName = trim($_POST["account_name"]);
        $accountOwner = $_SESSION["username"];

        $db = new Database();
        $db->query("DELETE FROM accounts WHERE account_owner = :accountOwner AND account_name = :accountName");
        $db->bind(':accountOwner', $accountOwner);
        $db->bind(':accountName', $accountName);
        $db->execute();

        $_SESSION['messages'][] = "Account Deleted";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
}
