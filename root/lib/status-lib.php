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
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . API_KEY, // Make sure this is your DALL·E API key
    ];

    // Prepare the first request data for generating the social status
    $status_data = [
        'model' => MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $system_message],
            ['role' => 'user', 'content' => 'Generate a social status under 256 characters with NO hashtags: ' . $prompt . ' Also add the following CTA: Visit: ' . $link . ' (Followed by a brief reason to do so.)']
        ],
        'temperature' => TEMPERATURE,
        'max_tokens' => TOKENS,
    ];

    // Initialize cURL session for the first request
    $ch = curl_init(API_ENDPOINT);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($status_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $status_response = curl_exec($ch);
    if ($status_response === false) {
        // Log error if request fails
        error_log("API request error: " . curl_error($ch), 3, LOG_DIR . "/api_error.log");
    }
    curl_close($ch);

    // Decode the response
    $status_response_data = json_decode($status_response, true);
    $status_content = $status_response_data['choices'][0]['message']['content'] ?? 'Invalid response from API.';
    $status = $status_content;

    // Add here: DALL·E API request for image generation based on $status_content
    $image_data = [
        'model' => 'dall-e-3',
        'prompt' => $status_content,
        'n' => 1,
        'size' => "1792x1024"
    ];

    $image_api_endpoint = 'https://api.openai.com/v1/images/generations';
    $ch = curl_init($image_api_endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($image_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $image_response = curl_exec($ch);
    if ($image_response === false) {
        error_log("DALL·E API request error: " . curl_error($ch), 3, LOG_DIR . "/dalle_api_error.log");
    } else {
        $image_response_data = json_decode($image_response, true);
        if (isset($image_response_data['data'][0]['url'])) {
            $imageUrl = $image_response_data['data'][0]['url'];
            $imageContent = file_get_contents($imageUrl);
            if ($imageContent !== false) {
                $imageDirectory = "images/{$accountOwner}/{$accountName}/";
                if (!file_exists($imageDirectory)) {
                    mkdir($imageDirectory, 0777, true);
                }
                $imageFileName = uniqid() . '.png'; // Assuming the image is a PNG
                $imageFilePath = $imageDirectory . $imageFileName;
                file_put_contents($imageFilePath, $imageContent);
                $statusImage = $imageFileName; // Store the image file name for use in `saveStatus`
            }
        }
    }
    curl_close($ch);

    // Conditionally append hashtags to the status
    if ($hashtags) {
        // Prepare the second request data for generating hashtags
        $hashtag_data = [
            'model' => MODEL,
            'messages' => [
                ['role' => 'system', 'content' => $system_message],
                ['role' => 'user', 'content' => 'Generate and only reply with 3 to 5 relevant hashtags (Based on this status: ' . $status_content . ').']
            ],
            'temperature' => TEMPERATURE,
            'max_tokens' => TOKENS,
        ];

        // Re-initialize cURL session for the second request
        $ch = curl_init(API_ENDPOINT);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($hashtag_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $hashtag_response = curl_exec($ch);
        if ($hashtag_response === false) {
            // Log error if request fails
            error_log("API request error: " . curl_error($ch), 3, LOG_DIR . "/api_error.log");
        }
        curl_close($ch);

        // Decode the response
        $hashtag_response_data = json_decode($hashtag_response, true);
        $hashtag_content = $hashtag_response_data['choices'][0]['message']['content'] ?? '';

        $status .= ' ' . $hashtag_content;
    }

    // Save the final status
    saveStatus($accountName, $accountOwner, $status, $statusImage);
}

function saveStatus($accountName, $accountOwner, $status, $statusImage)
{
    // Ensure the directory for the account exists, create if not
    if (!file_exists(ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}")) {
        mkdir(ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}", 0777, true);
    }

    $statusFile = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/statuses";
    $statuses = [];

    // Read existing statuses if the file exists
    if (file_exists($statusFile)) {
        $statuses = json_decode(file_get_contents($statusFile), true);
        if ($statuses === null) {
            $statuses = [];
        }
    }

    // Limit the number of saved statuses
    if (count($statuses) >= MAX_STATUSES) {
        $oldestStatus = array_shift($statuses); // Remove the oldest status to make room
        // Optional: Add logic here to delete the associated image file of the oldest status
    }

    // Prepare the new status entry
    $newStatus = [
        'text' => $status,
        'created_at' => date('Y-m-d H:i:s'),
        'status-image' => $statusImage
    ];

    // Add the new status at the beginning of the array
    array_unshift($statuses, $newStatus);

    // Save the updated statuses back to the file
    file_put_contents($statusFile, json_encode($statuses));
}
