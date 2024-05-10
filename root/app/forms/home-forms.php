<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/home-forms.php
 * Description: ChatGPT API Status Generator
 */

require_once '../lib/db.php'; // Make sure this points to your database access class

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
    }
}
?>
