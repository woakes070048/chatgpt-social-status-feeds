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

function updateUnassociatedImages($accountName)
{
    $imageFolder = "images/{$accountName}/";
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
    $accountName = $_GET['acct'];
    $key = $_GET['key'];

    $accountFiles = glob("../storage/accounts/*/{$accountName}");

    if (!empty($accountFiles)) {
        // Get the account file
        $accountFile = $accountFiles[0];
        $accountData = json_decode(file_get_contents($accountFile), true);
        $accountOwner = $accountData['owner'];

        if ($accountData['key'] === $key) {
            // Check user's API usage limit
            $userFile = "../storage/users/{$accountOwner}";
            $userInfo = json_decode(file_get_contents($userFile), true);

            if ($userInfo['used-api-calls'] < $userInfo['max-api-calls']) {
                // Update user's API usage count
                $userInfo['used-api-calls'] += 1;
                file_put_contents($userFile, json_encode($userInfo));

                // Generate status and update images
                $prompt = $accountData['prompt'];
                $link = $accountData['link'];
                $hashtags = $accountData['hashtags'];
                generateStatus($accountName, $accountOwner, $key, $prompt, $link, $hashtags);
                updateUnassociatedImages($accountName);
                echo 'Status created.';
            } else {
                echo 'API usage limit reached for the user.';
            }
        } else {
            echo 'Invalid key.';
        }
    } else {
        echo 'Invalid account.';
    }
} else {
    echo 'Missing account or key.';
}
