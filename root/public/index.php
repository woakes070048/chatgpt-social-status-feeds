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
require_once "../app/auth-helper.php";
require_once '../app/status-helper.php';

// Get the value of "?page=" from the URL
$page = isset($_GET['page']) ? $_GET['page'] : '';

// Path to the forms and helpers directories
$helpersDir = '../helpers/';
$formsDir = '../forms/';

// Check if the PHP file exists in the helpers directory
$helpersFile = $helpersDir . $page . '.php';
if (file_exists($helpersFile)) {
    require_once($helpersFile);
}

// Check if the PHP file exists in the forms directory
$formsFile = $formsDir . $page . '.php';
if (file_exists($formsFile)) {
    require_once($formsFile);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- All the scripts at the end -->
        <script src="/assets/js/script.js"></script>
    <script src="/assets/js/tabs.js"></script>
    <?php
    if ($page) {
        // Check if the 'page' parameter equals 'gallery'
        if ($page === 'gallery') {
            echo '
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css" rel="stylesheet" />
            ';
        }

        // Check if the additional JavaScript file exists
        $additionalJsFile = __DIR__ . '/assets/js/inc/' . $page . '.js';
        if (file_exists($additionalJsFile)) {
            echo '<script src="/assets/js/inc/' . $page . '.js"></script>';
        }
    }
    ?>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/tabs.css">

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
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>

    <!-- Tab links -->
    <div class="tab">
    <a href="/home"><button class="tablinks">Manage Statuses</button></a>
    <a href="/accounts"><button class="tablinks">Manage Accounts</button></a>
    <a href="/gallery"><button class="tablinks">Manage Gallery</button></a>
    <?php $userData = getUserData($_SESSION['username']); if ($userData['admin'] == 1) : ?>
        <a href="/users"><button class="tablinks">Manage Users</button></a>
    <?php endif; ?>
</div>

    <!-- Tab content -->
    <?php
if($page) {
    $file = '../pages/' . $page . '.php';

    if(file_exists($file)) {
        include($file);
    } else {
        // Handle the error, for example, redirect to a 404 page
        // header('Location: /404.html');
        // exit();
    }
} else {
    // Handle the error, for example, redirect to a homepage
    // header('Location: /');
    // exit();
}
?>
    <footer>
        <p>&copy;
            <?php echo date("Y"); ?> Vontainment. All Rights Reserved.
        </p>
    </footer>
    <?php require_once '../app/support-helper.php'; ?>
</body>
</html>