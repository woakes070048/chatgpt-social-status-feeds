<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ajax.php
 * Description: ChatGPT API Status Generator
 */

session_start();
require_once '../config.php';
require_once '../lib/waf-lib.php';
require_once '../lib/ajax-lib.php';

$ip = $_SERVER['REMOTE_ADDR'];
if (is_blacklisted($ip)) {
    // Stop the script and show an error if the IP is blacklisted
    http_response_code(403); // Optional: Set HTTP status code to 403 Forbidden
    echo "Your IP address has been blacklisted. If you believe this is an error, please contact us.";
    exit();
} elseif (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // The user is not logged in, redirect to the login page
    header('Location: login.php');
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prompt'])) {
        $prompt = $_POST['prompt']; // You need to add this line
        $response = generateQuickStatus($prompt);
        echo $response;
        exit;
    }
}
