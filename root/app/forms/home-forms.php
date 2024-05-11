<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/home-forms.php
 * Description: ChatGPT API Status Generator
 */

require_once '/lib/cron.php'; // Include your cron functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["delete_status"])) {
        $accountName = trim($_POST["account"]);
        $accountOwner = trim($_POST["username"]);
        $statusId = (int) $_POST["index"];  // Assuming 'index' is the status ID to be deleted

        // Initialize database object
        $db = new Database();

        // Delete status from the database
        $db->query("DELETE FROM status_updates WHERE id = :statusId AND account = :accountName AND username = :accountOwner");
        $db->bind(':statusId', $statusId);
        $db->bind(':accountName', $accountName);
        $db->bind(':accountOwner', $accountOwner);
        $db->execute();

        // Redirect to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } elseif (isset($_POST["generate_status"])) {
        $accountName = trim($_POST["account"]);
        $accountOwner = trim($_POST["username"]);

        // Call the function to run the status update job
        runStatusUpdateJobs($accountName, $accountOwner); // Adapt this function as needed

        // Redirect to avoid form resubmission
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
}
