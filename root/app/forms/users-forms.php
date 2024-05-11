<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/users"-forms.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'], $_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    if (isset($_POST['edit_users'])) {
        $username = $_POST['username']; // Convert username to lowercase and replace spaces with hyphens
        $password = $_POST['password'];
        $totalAccounts = $_POST['total-accounts'];
        $maxApiCalls = $_POST['max-api-calls'];
        $usedApiCalls = $_POST['used-api-calls'];
        $admin = isset($_POST['admin']) ? 1 : 0;

        // Validate username and password
        if (!preg_match('/^[a-z0-9]{8,18}$/', $username)) {
            $_SESSION['messages'][] = "Username must be 8-18 characters long, lowercase letters and numbers only.";
        } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_]).{8,16}$/', $password)) {
            $_SESSION['messages'][] = "Password must be 8-16 characters long, including at least one letter, one number, and one symbol.";
        } elseif (!empty($username) && !empty($password) && !empty($totalAccounts) && !empty($maxApiCalls)) {
            $db = new Database();

            $db->query("SELECT * FROM users WHERE username = :username");
            $db->bind(':username', $username);
            $userExists = $db->single();

            if ($userExists) {
                $db->query("UPDATE users SET password = :password, total_accounts = :totalAccounts, max_api_calls = :maxApiCalls, used_api_calls = :usedApiCalls, admin = :admin WHERE username = :username");
            } else {
                $db->query("INSERT INTO users (username, password, total_accounts, max_api_calls, used_api_calls, admin) VALUES (:username, :password, :totalAccounts, :maxApiCalls, :usedApiCalls, :admin)");
                // Create directory for images if user is being created
                $userImagePath = __DIR__ .  '/../../public/images/' . $username;
                if (!file_exists($userImagePath)) {
                    mkdir($userImagePath, 0777, true); // Create the directory recursively
                    // Create index.php in the new directory
                    $indexFilePath = $userImagePath . '/index.php';
                    file_put_contents($indexFilePath, '<?php die(); ?>');
                }
            }
            $db->bind(':username', $username);
            $db->bind(':password', $password);
            $db->bind(':totalAccounts', $totalAccounts);
            $db->bind(':maxApiCalls', $maxApiCalls);
            $db->bind(':usedApiCalls', $usedApiCalls);
            $db->bind(':admin', $admin);
            $db->execute();

            $_SESSION['messages'][] = "User has been created or modified.";
            header("Location: /users");
            exit;
        } else {
            $_SESSION['messages'][] = "A field is missing or has incorrect data. Please try again.";
            header("Location: /users");
            exit;
        }
    } elseif (isset($_POST['delete_user']) && isset($_POST['username'])) {
        $username = $_POST['username'];

        // Check if the user is trying to delete their own account
        if ($username === $_SESSION['username']) {
            $_SESSION['messages'][] = "Sorry, you can't delete your own account.";
        } else {
            $db = new Database();

            // Remove the user from the user table
            $db->query("DELETE FROM users WHERE username = :username");
            $db->bind(':username', $username);
            $db->execute();

            // Remove all accounts associated with the user from the account table
            $db->query("DELETE FROM accounts WHERE username = :username");
            $db->bind(':username', $username);
            $db->execute();

            // Remove all statuses associated with the user from the status table
            $db->query("DELETE FROM status_updates WHERE username = :username");
            $db->bind(':username', $username);
            $db->execute();

            $_SESSION['messages'][] = "User Deleted";
        }

        header("Location: /users");
        exit;
    }
} else {
    // CSRF validation failed, handle the error
    echo 'CSRF token mismatch.';
    exit;
}
