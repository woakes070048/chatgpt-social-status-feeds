<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: status-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';

function generateStatus($accountName, $accountOwner, $key, $prompt, $link, $hashtags)
{
    $system_message = SYSTEM_MSG;
    $user_message = PROMPT_PREFIX . $prompt . ' ALWAYS include a relevant call to action with the link ' . $link;

    if ($hashtags) {
        $user_message .= ' Also add relevant hashtags (but do not use #CallToAction).';
    } else {
        $user_message .= ' Also DONOT include any hashtags!';
    }

    $data = [
        'model' => MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $system_message],
            ['role' => 'user', 'content' => $user_message]
        ],
        'temperature' => TEMPERATURE,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_ENDPOINT);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . API_KEY,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    error_log("API request: " . json_encode($data), 3, "../api.log"); // Log the request for debugging
    error_log("API response: " . $response, 3, "../api.log"); // Log the response for debugging

    $response_data = json_decode($response, true);

    if (isset($response_data['choices'][0]['message']['content'])) {
        $status = $response_data['choices'][0]['message']['content'];
        saveStatus($accountName, $accountOwner, $status); // Pass $accountOwner to saveStatus
    } else {
        echo 'Invalid response from API.';
    }
}

function saveStatus($accountName, $accountOwner, $status)
{
    $statusFile = "../storage/accounts/{$accountOwner}/{$accountName}/statuses";
    $statuses = [];

    if (file_exists($statusFile)) {
        $statuses = json_decode(file_get_contents($statusFile), true);
        if ($statuses === null) {
            $statuses = [];
        }
    }

    // Check if the current amount of statuses is more than MAX_STATUSES
    if (count($statuses) >= MAX_STATUSES) {
        $oldestStatus = array_pop($statuses);
        $statusImage = $oldestStatus['status-image'];

        if ($statusImage !== null) {
            $imagePath = "../storage/images/{$accountOwner}/{$accountName}/{$statusImage}";

            // Delete the image associated with the oldest status
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    // Randomly pick an image file from the directory
    $imageDirectory = "../storage/images/{$accountOwner}/{$accountName}/";
    $imageFiles = glob($imageDirectory . "*.jpg");

    if (empty($imageFiles)) {
        $statusImage = null;
    } else {
        $randomImageFile = $imageFiles[array_rand($imageFiles)];

        // Move the image file to the new location
        $imageFileName = basename($randomImageFile);
        $newImageLocation = "images/{$accountOwner}/{$accountName}/{$imageFileName}";
        rename($randomImageFile, $newImageLocation);

        $statusImage = $imageFileName;
    }

    // Add the "status-image" property to the new status
    $newStatus = [
        'text' => $status,
        'created_at' => date('Y-m-d H:i:s'),
        'status-image' => $statusImage
    ];

    array_unshift($statuses, $newStatus);

    // Save the updated statuses JSON data
    file_put_contents($statusFile, json_encode($statuses));
}
