<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: cron.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';
require_once '../app/status-helper.php';
require_once '../app/admin-helper.php';

function updateUnassociatedImages($account)
{
    $imageFolder = "images/{$account}/";
    if (!file_exists($imageFolder)) {
        mkdir($imageFolder, 0777, true);
    }

    $images = glob($imageFolder . "*.{jpg,jpeg,png}", GLOB_BRACE);
    $imgFile = $imageFolder . "img";

    $imageAssignments = [];
    if (file_exists($imgFile)) {
        $imageAssignments = file($imgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    $assignedImages = array_values(array_filter($imageAssignments));
    $unassociatedImages = array_diff($images, $assignedImages);

    if (empty($unassociatedImages)) {
        array_unshift($imageAssignments, "_NOIMAGE_");
        file_put_contents($imgFile, implode(PHP_EOL, $imageAssignments));
    } else {
        $newAssignments = $assignedImages;
        foreach ($unassociatedImages as $image) {
            $imageName = basename($image);
            if (!in_array($imageName, $assignedImages)) {
                array_unshift($newAssignments, $imageFolder . $imageName);
                break;
            }
        }
        file_put_contents($imgFile, implode(PHP_EOL, $newAssignments));
    }
}


// Check if the required query parameters are present in the URL
if (!isset($_GET['acct']) || !isset($_GET['key'])) {
    // If the parameters are missing, show an error message and exit the script
    echo 'Error: Missing required parameters';
    exit();
}

if (isset($_GET['acct']) && isset($_GET['key'])) {
    $account = $_GET['acct'];
    $key = $_GET['key'];

    $accountFile = "../storage/accounts/{$account}";

    if (file_exists($accountFile)) {
        $accountInfo = unserialize(file_get_contents($accountFile));

        if ($accountInfo['key'] === $key) {
            $prompt = $accountInfo['prompt'];
            $link = $accountInfo['link'];
            $hashtags = $accountInfo['hashtags'];
            generateStatus($account, $key, $prompt, $link, $hashtags);
            updateUnassociatedImages($account);
            header('Location: /index.php');
            exit;
        } else {
            echo 'Invalid key.';
        }
    } else {
        echo 'Invalid account.';
    }
} else {
    echo 'Missing account or key.';
}
