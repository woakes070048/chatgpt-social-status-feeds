<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: status-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';

function generateStatus($account, $key, $prompt)
{
    $data = [
        'model' => MODEL,
        'messages' => [['role' => 'user', 'content' => $prompt]],
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

    error_log("API response: " . $response); // Log the response for debugging

    $response_data = json_decode($response, true);

    if (isset($response_data['choices'][0]['message']['content'])) {
        $status = $response_data['choices'][0]['message']['content'];
        saveStatus($account, $status);
    } else {
        echo 'Invalid response from API.';
    }
}


function saveStatus($account, $status)
{
    $statusFile = "../storage/statuses/{$account}";
    $statuses = [];
    if (file_exists($statusFile)) {
        $statuses = unserialize(file_get_contents($statusFile));
    }

    array_unshift($statuses, $status);

    if (count($statuses) > MAX_STATUSES) {
        $statuses = array_slice($statuses, 0, MAX_STATUSES);
    }

    file_put_contents($statusFile, serialize($statuses));
}
