<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: login.php
 * Description: ChatGPT API Status Generator
 */

session_start();
require_once __DIR__ .  '/../config.php';
require_once __DIR__ .  '/../db.php';
require_once __DIR__ .  '/../lib/waf-lib.php';
require_once __DIR__ .  '/../lib/common-lib.php';
require_once __DIR__ .  '/../lib/auth-lib.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login($username, $password)) {
        header('Location: /home');
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password.";
    }
}
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
        <img src="assets/images/logo.png" alt="Logo" class="logo">
        <h2>AI Status Admin</h2>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username"><br><br>
            <label>Password:</label>
            <input type="password" name="password"><br><br>
            <input type="submit" value="Log In">
        </form>
        <?php if (isset($_SESSION['error'])) : ?>
            <div id="error-msg"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
    </div>
</body>

</html>
