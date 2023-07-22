<?php
 /*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/accounts-forms.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["edit_account"])) {
        $accountOwner = $_SESSION["username"];
        $accountName = trim($_POST["account_name"]);
        $key = trim($_POST["key"]);
        $prompt = trim($_POST["prompt"]);
        $hashtags = isset($_POST["hashtags"]) ? true : false;
        $link = trim($_POST["link"]);

        if (!empty($accountName) && !empty($key) && !empty($prompt) && !empty($link)) {
            $acctInfo = getAcctInfo($accountOwner, $accountName);

            $acctInfo['key'] = $key;
            $acctInfo['prompt'] = $prompt;
            $acctInfo['hashtags'] = $hashtags;
            $acctInfo['link'] = $link;

            // Create the directory for the account if it doesn't exist
            $directoryPath = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/";
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            // Create the directory for the images if it doesn't exist
            $imagesDirectoryPath = "images/{$accountOwner}/{$accountName}/";
            if (!file_exists($imagesDirectoryPath)) {
                mkdir($imagesDirectoryPath, 0777, true);
            }

            // Then write the account file
            file_put_contents("{$directoryPath}acct", json_encode($acctInfo));

            echo '<script type="text/javascript">
                alert("Account has been created or modified");
                window.location.href = window.location.href;
                </script>';
            exit;
        } else {
            echo '<script type="text/javascript">
            alert("A field is missing or has incorrect data. Please try again.");
            window.location.href = window.location.href;
            </script>';
            exit;
        }
    } elseif (isset($_POST["delete_account"])) {
        $accountName = trim($_POST["account_name"]);
        $accountOwner = $_SESSION["username"];

        $accountFile = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/acct";
        $statusFile = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/statuses";

        if (file_exists($accountFile)) {
            unlink($accountFile);

            if (file_exists($statusFile)) {
                unlink($statusFile);
            }

            $accountDirectory = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/";
            if (is_dir($accountDirectory)) {
                $files = glob($accountDirectory . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($accountDirectory);
            }

            $imageFolder = IMAGES_DIR . "/{$accountOwner}/{$accountName}/";
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
}
