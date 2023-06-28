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

            file_put_contents("../storage/accounts/{$accountOwner}/{$accountName}/acct", json_encode($acctInfo));

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
    }

} elseif (isset($_POST["delete_account"])) {
    $accountName = trim($_POST["account_name"]);
    $accountOwner = $_SESSION["username"];

    $accountFile = "../storage/accounts/{$accountOwner}/{$accountName}/acct";
    $statusFile = "../storage/accounts/{$accountOwner}/{$accountName}/statuses";

    if (file_exists($accountFile)) {
        unlink($accountFile);

        if (file_exists($statusFile)) {
            unlink($statusFile);
        }

        $imageFolder = "images/{$accountOwner}/{$accountName}/";
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
?>
