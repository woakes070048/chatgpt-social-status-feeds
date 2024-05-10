<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/users-forms.php
 * Description: ChatGPT API Status Generator
 */

require_once '../lib/db.php'; // Make sure this points to your database class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_users'])) {
        // Retrieve user inputs
        $username = $_POST['username'];
        $password = $_POST['password'];  // Consider hashing this password
        $totalAccounts = $_POST['total-accounts'];
        $maxApiCalls = $_POST['max-api-calls'];
        $usedApiCalls = $_POST['used-api-calls'];
        $admin = isset($_POST['admin']) ? 1 : 0;

        // Initialize database object
        $db = new Database();

        // Check if the user already exists
        $db->query("SELECT * FROM users WHERE username = :username");
        $db->bind(':username', $username);
        $userExists = $db->single();

        // Insert or update user information
        if ($userExists) {
            $db->query("UPDATE users SET password = :password, total_accounts = :totalAccounts, max_api_calls = :maxApiCalls, used_api_calls = :usedApiCalls, admin = :admin WHERE username = :username");
        } else {
            $db->query("INSERT INTO users (username, password, total_accounts, max_api_calls, used_api_calls, admin) VALUES (:username, :password, :totalAccounts, :maxApiCalls, :usedApiCalls, :admin)");
        }
        $db->bind(':username', $username);
        $db->bind(':password', $password);
        $db->bind(':totalAccounts', $totalAccounts);
        $db->bind(':maxApiCalls', $maxApiCalls);
        $db->bind(':usedApiCalls', $usedApiCalls);
        $db->bind(':admin', $admin);
        $db->execute();

        echo '<script type="text/javascript">alert("User has been created or modified"); window.location.href = window.location.href;</script>';
        exit;
    } elseif (isset($_POST['delete_user']) && isset($_POST['username'])) {
        // Delete user
        $username = $_POST['username'];

        if ($username === $_SESSION['username']) {
            echo '<script type="text/javascript">alert("Sorry, you can\'t delete your own account."); window.location.href = window.location.href;</script>';
            exit;
        }

        $db = new Database();
        $db->query("DELETE FROM users WHERE username = :username");
        $db->bind(':username', $username);
        $db->execute();

        echo '<script type="text/javascript">alert("User Deleted"); window.location.href = window.location.href;</script>';
        exit;
    }
}
?>
