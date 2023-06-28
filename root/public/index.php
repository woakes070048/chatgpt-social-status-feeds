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
require_once '../lib/common-lib.php';
require_once '../lib/status-lib.php';

// Check if the user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit();
    }
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Check if $page-helper.php exists
    $helperFile = "../app/helpers/" . $page . "-helper.php";
    if (file_exists($helperFile)) {
        require_once($helperFile);
    }

    // Check if $page-forms.php exists
    $formsFile = "../app/forms/" . $page . "-forms.php";
    if (file_exists($formsFile)) {
        require_once($formsFile);
    }

    // Check if $page.php exists
    $pageFile = "../app/pages/" . $page . ".php";
    if (file_exists($pageFile)) {
        $pageOutput = $pageFile;
    } else {
        $pageOutput = "../lib/404-lib.php";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <a href="/home"><button class="tablinks">Manage Statuses</button></a>
        <a href="/accounts"><button class="tablinks">Manage Accounts</button></a>
        <a href="/gallery"><button class="tablinks">Manage Gallery</button></a>
        <?php
        if (isset($_SESSION['username'])) {
            $userData = getUserInfo($_SESSION['username']);
            if ($userData['admin'] == 1) :
        ?>
                <a href="/users"><button class="tablinks">Manage Users</button></a>
            <?php
            elseif ($userData['admin'] == 0) :
            ?>
                <a href="/info"><button class="tablinks">Change Password</button></a>
        <?php
            endif;
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
    <?php require_once '../lib/support-lib.php'; ?>
</body>

</html>