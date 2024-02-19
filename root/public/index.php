<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: index.php
 * Description: ChatGPT API Status Generator
*/
session_start();
require_once '../config.php';
require_once '../lib/waf-lib.php';
require_once '../lib/common-lib.php';
require_once '../lib/load-lib.php';
?>


<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/assets/js/header-scripts.js"></script>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/pages.css">
    <link rel="stylesheet" href="/assets/css/mobile.css">

    <title>Dashboard</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="/home">
                <img src="/assets/images/logo.png" alt="Logo">
            </a>
        </div>

        <div class="logout-button">
            <form action="/login.php" method="POST">
                <button class="orange-button" type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>

    <!-- Tab links -->
    <div class="tab">
        <a href="/home"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/home') echo 'active'; ?>">Statuses</button></a>
        <a href="/accounts"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/accounts') echo 'active'; ?>">Accounts</button></a>
        <?php
        if (isset($_SESSION['username'])) {
            $userData = getUserInfo($_SESSION['username']);
            if ($userData && isset($userData['admin'])) {
                if ($userData['admin'] == 1) :
        ?>
        <a href="/users"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/users') echo 'active'; ?>">Users</button></a>
        <?php
                elseif ($userData['admin'] == 0) :
            ?>
        <a href="/info"><button class="tablinks <?php if ($_SERVER['REQUEST_URI'] === '/info') echo 'active'; ?>">My info</button></a>
        <?php
                endif;
            }
        }
        ?>
    </div>

    <!-- Tab links -->
    <!-- Tab content -->
    <?php
    if (isset($pageOutput)) {
        require_once $pageOutput;
    }
    ?>
    <!-- Tab content -->
    <footer>
        <p>&copy;
            <?php echo date("Y"); ?> Vontainment. All Rights Reserved.
        </p>
    </footer>
    <script src="/assets/js/footer-scripts.js"></script>
</body>

</html>