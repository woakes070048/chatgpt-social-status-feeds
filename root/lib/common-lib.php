<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /lib/common.php
 * Description: ChatGPT API Status Generator
 */

// Retrieve user information from the database
function getUserInfo($username)
{
    $db = new Database();
    $db->query("SELECT * FROM users WHERE username = :username");
    $db->bind(':username', $username);
    return $db->single();
}

// Retrieve account information from the database
function getAcctInfo($username, $account)
{
    $db = new Database();
    $db->query("SELECT * FROM accounts WHERE username = :username AND account = :account");
    $db->bind(':username', $username);
    $db->bind(':account', $account);
    return $db->single();
}

// Retrieve status information from the database
// Retrieve status information from the database
function getStatusInfo($username, $account)
{
    $db = new Database();
    $db->query("SELECT * FROM status_updates WHERE username = :username AND account = :account ORDER BY created_at DESC");
    $db->bind(':username', $username);
    $db->bind(':account', $account);
    return $db->resultSet(); // Return the result set directly
}

// Retrieve all users from the database
function getAllUsers()
{
    $db = new Database();
    $db->query("SELECT * FROM users");
    return $db->resultSet();  // Returns an array of user objects
}

// Retrieve all account names for a given username from the database
function getAllUserAccts($username)
{
    $db = new Database();
    $db->query("SELECT account FROM accounts WHERE username = :username");
    $db->bind(':username', $username);
    return $db->resultSet();  // Returns an array of objects where each object contains account information
}

// Function to display and clear session messages
function display_and_clear_messages()
{
    if (isset($_SESSION['messages']) && count($_SESSION['messages']) > 0) {
        echo '<div class="messages">';
        foreach ($_SESSION['messages'] as $message) {
            echo '<p>' . htmlspecialchars($message) . '</p>';
        }
        echo '</div>';
        // Clear messages after displaying
        unset($_SESSION['messages']);
    }
}
