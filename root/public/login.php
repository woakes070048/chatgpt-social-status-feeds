<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: login.php
 * Description: ChatGPT API Status Generator
 */
session_start();
require_once '../config.php';
require_once "../app/auth-helper.php";

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>AI Status Admin Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="login-box">
        <img src="assets/logo.png" alt="Logo" class="logo">
        <h2>AI Status Admin</h2>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username"><br><br>
            <label>Password:</label>
            <input type="password" name="password"><br><br>
            <input type="submit" value="Log In">
        </form>
        <?php if (isset($error_msg)) : ?>
            <div id="error-msg"><?php echo $error_msg; ?></div>
        <?php endif; ?>
    </div>
</body>

</html>