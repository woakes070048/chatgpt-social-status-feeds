<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: index.php
 * Description: ChatGPT API Status Generator
*/

session_start();

require_once "../app/auth-helper.php";
require_once "../app/form-helper.php";

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles.css">
    <script src="/assets/script.js"></script>
    <title>Dashboard</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="/">
                <img src="/assets/logo.png" alt="Logo">
            </a>
        </div>

        <div class="logout-button">
            <form action="<?php echo htmlspecialchars(
                                $_SERVER["PHP_SELF"]
                            ); ?>" method="POST">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>
    <div class="container">
        <?php require_once "../app/box-helper.php"; ?>
    </div>

    <button id="add-account-btn">Add Account</button>

    <?php require_once "../app/popup-helper.php"; ?>

    <footer>
        <p>&copy;
            <?php echo date("Y"); ?> Vontainment. All Rights Reserved.
        </p>
    </footer>
</body>

</html>