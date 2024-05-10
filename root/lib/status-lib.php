<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: status-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';
require_once '../db.php';

function generateStatus($accountName, $accountOwner, $prompt, $link, $hashtags)
{
    $system_message = SYSTEM_MSG;

    // Retrieve the status content from API
    $status_content = getStatus($prompt, $link, $system_message);

    // Generate image associated with the status
    $image_response = getImage($accountName, $accountOwner, $system_message); // Adjusted to include system message if needed
    $status_image_url = $image_response['url'] ?? '';

    // Conditionally append hashtags if requested
    if ($hashtags) {
        $hashtag_content = getHashtags($status_content, $system_message);
        $status_content .= ' ' . $hashtag_content;
    }

    saveStatus($accountName, $accountOwner, $status_content, $status_image_url);
}

function getStatus($prompt, $link, $system_message)
{
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . API_KEY,
    ];

    $status_data = [
        'model' => MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $system_message],
            ['role' => 'user', 'content' => 'Generate a social status under 256 characters with NO hashtags: ' . $prompt . ' Also add the following CTA: Visit: ' . $link]
        ],
        'temperature' => TEMPERATURE,
        'max_tokens' => TOKENS,
    ];

    $ch = curl_init(API_ENDPOINT);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($status_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $status_response = curl_exec($ch);
    curl_close($ch);

    if ($status_response === false) {
        error_log("API request error: " . curl_error($ch), 3, LOG_DIR . "/api_error.log");
        return 'API request failed'; // Consider how to handle failures more robustly
    }

    $status_response_data = json_decode($status_response, true);
    return $status_response_data['choices'][0]['message']['content'] ?? 'Invalid response from API.';
}


function getHashtags($status_content, $system_message)
{
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . API_KEY,
    ];

    $hashtag_data = [
        'model' => MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $system_message],
            ['role' => 'user', 'content' => 'Generate and only reply with 3 to 5 relevant hashtags based on this status: ' . $status_content]
        ],
        'temperature' => TEMPERATURE,
        'max_tokens' => 60,
    ];

    $ch = curl_init(API_ENDPOINT);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($hashtag_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $hashtag_response = curl_exec($ch);
    curl_close($ch);

    if ($hashtag_response === false) {
        error_log("API request error for hashtags: " . curl_error($ch), 3, LOG_DIR . "/api_error.log");
        return ''; // Return an empty string if the API request fails
    }

    $hashtag_response_data = json_decode($hashtag_response, true);
    return $hashtag_response_data['choices'][0]['message']['content'] ?? '';
}

function getImage($accountName, $accountOwner, $headers)
{
    $db = new Database();
    // Retrieve the image prompt from the database
    $db->query("SELECT image_prompts FROM accounts WHERE account = :account AND username = :username");
    $db->bind(':account', $accountName);
    $db->bind(':username', $accountOwner);
    $image_prompt = $db->single()['image_prompts'];

    $image_data = [
        'model' => 'dall-e-3',
        'prompt' => $image_prompt,
        'n' => 1,
        'size' => "1792x1024"
    ];

    $ch = curl_init('https://api.openai.com/v1/images/generations');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($image_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $image_response = curl_exec($ch);
    curl_close($ch);

    return json_decode($image_response, true);
}

function saveStatus($accountName, $accountOwner, $status, $imageUrl)
{
    $db = new Database();
    $sql = "INSERT INTO status_updates (username, account, status, created_at, status_image) VALUES (:username, :account, :status, NOW(), :imageUrl)";
    $db->query($sql);
    $db->bind(':username', $accountOwner);
    $db->bind(':account', $accountName);
    $db->bind(':status', $status);
    $db->bind(':imageUrl', $imageUrl);
    $db->execute();
}
