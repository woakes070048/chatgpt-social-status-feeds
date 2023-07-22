<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/home-forms.php
 * Description: ChatGPT API Status Generator
 */

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["delete_status"])) {
        $accountName = trim($_POST["account"]);
        $accountOwner = trim($_POST["username"]);
        $index = (int) $_POST["index"];

        $statusFile = ACCOUNTS_DIR . "/{$accountOwner}/{$accountName}/statuses";
        $statuses = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];

        if (isset($statuses[$index])) {
            array_splice($statuses, $index, 1); // Remove item at index
            file_put_contents($statusFile, json_encode($statuses));
        }
    }
}

