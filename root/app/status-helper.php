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
        $user_message .= ' Also add relevant hashtags (but donot use #CallToAction).';
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
    $statusFile = "../storage/statuses/{$accountOwner}/{$accountName}"; // Update the status file path
    $statuses = [];

    if (file_exists($statusFile)) {
        $statuses = json_decode(file_get_contents($statusFile), true);
        if ($statuses === null) {
            $statuses = []; // Initialize as an empty array if null
        }
    }

    $newStatus = [
        'text' => $status,
        'created_at' => date('Y-m-d H:i:s')
    ];

    array_unshift($statuses, $newStatus);

    if (count($statuses) > MAX_STATUSES) {
        $statuses = array_slice($statuses, 0, MAX_STATUSES); // Limit the array to MAX_STATUSES

        // Get the image file path
        $imageFile = "images/{$accountName}/img";
        $imageAssignments = [];

        if (file_exists($imageFile)) {
            $imageAssignments = file($imageFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        $oldestStatusIndex = MAX_STATUSES - 1;
        if (isset($statuses[$oldestStatusIndex])) {
            $oldestStatus = $statuses[$oldestStatusIndex];

            if (isset($oldestStatus['image_name']) && $oldestStatus['image_name'] !== "_NOIMAGE_") {
                $imagePath = "images/{$accountName}/" . basename($oldestStatus['image_name']);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Remove the oldest status and image assignment from the arrays
            array_splice($statuses, $oldestStatusIndex, 1);
            array_splice($imageAssignments, $oldestStatusIndex, 1);

            // Save the updated image assignments to the file
            file_put_contents($imageFile, implode(PHP_EOL, $imageAssignments));
        }
    }

    file_put_contents($statusFile, json_encode($statuses));
}
