<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: auth-helper.php
 * Description: ChatGPT API Status Generator
 */

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        header('Location: /home');
        exit();
    }
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    // Get user info
    $userInfo = getUserInfo($_POST['username']);
    // Perform your login authentication logic here
    // Validate the login
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($userInfo && $username === $userInfo['username'] && $password === $userInfo['password']) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['timeout'] = time();  // Set the session timeout time
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT']; // Store the User-Agent string
        session_regenerate_id(true); // Regenerate the session ID
        header('Location: /home');
        exit();
    } else {
        // Failed login
        $ip = $_SERVER['REMOTE_ADDR'];

        if (is_blacklisted($ip)) {
            // Show the message that the user has been blacklisted
            $error_msg = "Your IP has been blacklisted due to multiple failed login attempts.";
        } else {
            // Update the number of failed login attempts and check if the IP should be blacklisted
            update_failed_attempts($ip);
            $error_msg = "Invalid username or password.";
        }
    }
}
