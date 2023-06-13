<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: auth-helper.php
 * Description: ChatGPT API Status Generator
 */

// Include the config file
require_once '../config.php';

// Function to get user data
function getUserData($username) {
    $userFile = "../storage/users/$username";
    if (file_exists($userFile)) {
        $userData = json_decode(file_get_contents($userFile), true);
        return $userData;
    } else {
        return null;
    }
}

// Check if the user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If the current file is not login.php, redirect to login.php
    $current_file = basename($_SERVER['PHP_SELF']);
    if ($current_file !== 'login.php') {
        header('Location: login.php');
        exit();
    }

    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Get user data
        $userData = getUserData($_POST['username']);

        // Validate the login
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($userData && $username === $userData['username'] && $password === $userData['password']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        }
    }
} else {
    // If the user is already logged in and the current file is login.php, redirect to index.php
    $current_file = basename($_SERVER['PHP_SELF']);
    if ($current_file === 'login.php') {
        header('Location: index.php');
        exit();
    }
}

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
