<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/accounts-forms.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'], $_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    if (isset($_POST["edit_account"])) {
        $accountOwner = $_SESSION["username"];
        $accountName = strtolower(str_replace(' ', '-', trim($_POST["account"])));
        $prompt = trim($_POST["prompt"]);
        $platform = trim($_POST["platform"]);
        $hashtags = isset($_POST["hashtags"]) ? 1 : 0;
        $link = trim($_POST["link"]);
        $imagePrompt = trim($_POST["image_prompt"]);
        $cron = trim($_POST["cron"]);

        // Validate account name and link
        if (!preg_match('/^[a-z0-9-]{8,18}$/', $accountName)) {
            $_SESSION['messages'][] = "Account name must be 8-18 characters long, alphanumeric and hyphens only.";
        } elseif (!preg_match('/^https:\/\/[\w.-]+(\/[\w.-]*)*\/?$/', $link)) {
            $_SESSION['messages'][] = "Link must be a valid URL starting with https://";
        } elseif (!empty($accountName) && !empty($prompt) && !empty($platform) && !empty($link) && !empty($imagePrompt) && !empty($cron)) {
            $db = new Database();

            // Check if the account exists
            $db->query("SELECT * FROM accounts WHERE username = :accountOwner AND account = :accountName");
            $db->bind(':accountOwner', $accountOwner);
            $db->bind(':accountName', $accountName);
            $accountExists = $db->single();

            if ($accountExists) {
                // Update account data
                $db->query("UPDATE accounts SET prompt = :prompt, platform = :platform, hashtags = :hashtags, link = :link, image_prompt = :imagePrompt, cron = :cron WHERE username = :accountOwner AND account = :accountName");
            } else {
                // Insert new account data
                $db->query("INSERT INTO accounts (username, account, prompt, platform, hashtags, link, image_prompt, cron) VALUES (:accountOwner, :accountName, :prompt, :platform, :hashtags, :link, :imagePrompt, :cron)");
                // Create directory for images if the account is being created
                $acctImagePath = BASE_DIR . '/public/images/' . $accountOwner . '/' . $accountName;
                if (!file_exists($acctImagePath)) {
                    mkdir($acctImagePath, 0777, true); // Create the directory recursively
                }
            }
            $db->bind(':accountOwner', $accountOwner);
            $db->bind(':accountName', $accountName);
            $db->bind(':prompt', $prompt);
            $db->bind(':platform', $platform);
            $db->bind(':hashtags', $hashtags);
            $db->bind(':link', $link);
            $db->bind(':imagePrompt', $imagePrompt);
            $db->bind(':cron', $cron);
            $db->execute();

            $_SESSION['messages'][] = "Account has been created or modified";
            header("Location: /accounts"); // Redirect to avoid form resubmission
            exit;
        } else {
            $_SESSION['messages'][] = "A field is missing or has incorrect data. Please try again.";
            header("Location: /accounts");
            exit;
        }
    } elseif (isset($_POST["delete_account"])) {
        $accountName = trim($_POST["account"]);
        $accountOwner = $_SESSION["username"];

        $db = new Database();

        // Delete all statuses related to this account
        $db->query("DELETE FROM status_updates WHERE username = :accountOwner AND account = :accountName");
        $db->bind(':accountOwner', $accountOwner);
        $db->bind(':accountName', $accountName);
        $db->execute();

        // Now, delete the account from the accounts table
        $db->query("DELETE FROM accounts WHERE username = :accountOwner AND account = :accountName");
        $db->bind(':accountOwner', $accountOwner);
        $db->bind(':accountName', $accountName);
        $db->execute();

        // Delete the folder and its contents
        $folderPath = __DIR__ . "/../../public/images/{$accountOwner}/{$accountName}";
        if (is_dir($folderPath)) {
            // Remove all files and subdirectories
            $files = glob($folderPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the file
                } elseif (is_dir($file)) {
                    // Delete the subdirectory and its contents recursively
                    array_map('unlink', glob("$file/*.*"));
                    rmdir($file);
                }
            }
            rmdir($folderPath); // Delete the folder
        }

        $_SESSION['messages'][] = "Account Deleted";
        header("Location: /accounts");
        exit;
    }
} else {
    // CSRF validation failed, handle the error
    echo 'CSRF token mismatch.';
    exit;
}
