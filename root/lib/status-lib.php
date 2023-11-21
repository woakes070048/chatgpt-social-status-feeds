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
    $user_message = 'Generate a social status under 256 characters with NO hashtags: ' . $prompt;
    $user_message .= 'Also add the following CTA: Visit: ' . $link . ' (Followed by a brief reason to do so.)';

    // Request 1: Generate the social status without hashtags
    $data = [
        'model' => MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $system_message],
            ['role' => 'user', 'content' => $user_message]
        ],
        'temperature' => TEMPERATURE,
        'max_tokens' => TOKENS,
    ];
    $status_response = getApiResponse($data);

    // Request 2 (Optional): Generate hashtags if required
    $hashtag_response = null;
    if ($hashtags) {
        $hashtag_message = 'Generate and only reply with 3 to 5 relevant hashtags (Based on this status: ' . $status_response . ').';
        $hashtag_data = [
            'model' => MODEL,
            'messages' => [
                ['role' => 'system', 'content' => $system_message],
                ['role' => 'user', 'content' => $hashtag_message]
            ],
            'temperature' => TEMPERATURE,
            'max_tokens' => TOKENS,
        ];
        $hashtag_response = getApiResponse($hashtag_data);
    }

    // Merge the responses to create the final status
    $status = $status_response . ' ' . ($hashtag_response ?? "");

    saveStatus($accountName, $accountOwner, $status);
}

function getApiResponse($data)
{
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

    error_log("API request: " . json_encode($data), 3, LOG_DIR . "/api.log"); // Log the request for debugging
    error_log("API response: " . $response, 3, LOG_DIR . "/api.log"); // Log the response for debugging

    $response_data = json_decode($response, true);

    if (isset($response_data['choices'][0]['message']['content'])) {
        return $response_data['choices'][0]['message']['content'];
    } else {
        return 'Invalid response from API.';
    }
}

function generateImage($accountName, $accountOwner, $status_response)
{
    // Define the API endpoint and API key for DALL-E
    define('API_ENDPOINT', 'https://api.openai.com/v1/images/generations');

    $data = [
        'prompt' => 'Generate an image to go with this social media status: ' . $status_response . '(Generate image in 6:19 aspect ratio)',
        'n' => 1 // Number of images to generate
    ];

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_ENDPOINT);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . API_KEY,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session and get the response
    $response = curl_exec($ch);
    curl_close($ch);

    // Logging the request and response
    error_log("API request: " . json_encode($data), 3, LOG_DIR . "/api.log");
    error_log("API response: " . $response, 3, LOG_DIR . "/api.log");

    // Decode the response
    $response_data = json_decode($response, true);

    // Handle the response from DALL-E API
    if (isset($response_data['id'])) {
        return $response_data['id']; // Returns the image ID or other relevant data
    } else {
        return 'Invalid response from API.';
    }
}

function saveStatus($accountName, $accountOwner, $status)
{
    $statusFile = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/statuses";
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
            $imagePath = IMAGES_DIR . "/{$accountOwner}/{$accountName}/{$statusImage}";

            // Delete the image associated with the oldest status
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    // Randomly pick an image file from the directory
    $imageDirectory = IMAGES_DIR . "/{$accountOwner}/{$accountName}/";
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
