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
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
    header('Location: /home');
    exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   if (isset($_POST['username']) && isset($_POST['password'])) {
        // Get user info
        $userInfo = getUserInfo($_POST['username']);

        // Validate the login
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($userInfo && $username === $userInfo['username'] && $password === $userInfo['password']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: /home');
            exit();
        }
    }
}
