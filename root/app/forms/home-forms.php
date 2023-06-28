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

        $statusFile = "../storage/accounts/{$accountOwner}/{$accountName}/statuses";
        $statuses = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];

        if (isset($statuses[$index])) {
            $imageName = "";
            $imageFile = "images/{$accountOwner}/{$accountName}/img";
            $imageAssignments = [];
            if (file_exists($imageFile)) {
                $imageAssignments = file($imageFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            }

            if (isset($imageAssignments[$index])) {
                $imageName = $imageAssignments[$index];
                if ($imageName != "_NOIMAGE_") {
                    $imagePath = "images/{$accountOwner}/{$accountName}/" . basename($imageName);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            array_splice($statuses, $index, 1); // Remove item at index
            file_put_contents($statusFile, json_encode($statuses));

            array_splice($imageAssignments, $index, 1); // Remove item at index
            file_put_contents($imageFile, implode(PHP_EOL, $imageAssignments));
        }
    }
}
