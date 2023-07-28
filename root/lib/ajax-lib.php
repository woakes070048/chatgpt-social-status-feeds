<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ajax-lib.php
 * Description: ChatGPT API Status Generator
 */

 function generateQuickStatus($prompt)
 {
     $system_message = SYSTEM_MSG;
     $user_message = $prompt;

     $data = [
         'model' => MODEL,
         'messages' => [
             ['role' => 'system', 'content' => $system_message],
             ['role' => 'user', 'content' => $user_message]
         ],
         'temperature' => TEMPERATURE,
         'max_tokens' => TOKENS,
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

     error_log("API request: " . json_encode($data), 3, LOG_DIR . "/api.log"); // Log the request for debugging
     error_log("API response: " . $response, 3, LOG_DIR . "/api.log"); // Log the response for debugging

     $response_data = json_decode($response, true);

     if (isset($response_data['choices'][0]['message']['content'])) {
         return $response_data['choices'][0]['message']['content'];
     } else {
         return 'Invalid response from API.';
     }
 }