<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /forms/users.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_users'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $admin = isset($_POST['admin']) ? 1 : 0;  // Convert checkbox value to 1 or 0
        $totalAccounts = $_POST['total-accounts'];
        $maxApiCalls = $_POST['max-api-calls'];
        $usedApiCalls = $_POST['used-api-calls'];

        if (empty($username) || empty($password) || empty($totalAccounts) || empty($maxApiCalls) || !is_numeric($usedApiCalls)) {
            // One or more values are not valid
            echo '<script type="text/javascript">
            alert("A field is missing or has incorrect data. Please try again.");
            window.location.href = window.location.href;
            </script>';
            exit;
        }


        // Construct user data array
        $userData = [
            'username' => $username,
            'password' => $password,
            'admin' => $admin,  // Store the converted value
            'total-accounts' => $totalAccounts,
            'max-api-calls' => $maxApiCalls,
            'used-api-calls' => $usedApiCalls,
        ];

        // Convert array to JSON
        $userJson = json_encode($userData);

        // Define the path for the user file
        $userFile = "../storage/users/{$username}";

        // Save JSON to file
        file_put_contents($userFile, $userJson);


        // Create directories if they don't exist
        $statusesDir = "../storage/statuses/{$username}";
        if (!is_dir($statusesDir)) {
            mkdir($statusesDir, 0755, true);
        }

        $accountsDir = "../storage/accounts/{$username}";
        if (!is_dir($accountsDir)) {
            mkdir($accountsDir, 0755, true);
        }

        // Success message
        echo '<script type="text/javascript">
    alert("User has been created or modified");
    window.location.href = window.location.href;
    </script>';
        exit;
    } elseif (isset($_POST['delete_user']) && isset($_POST['username'])) {
        $username = $_POST['username'];
        $accountOwner = $username;

        $accountDirectory = "../storage/accounts/{$accountOwner}";
        $statusDirectory = "../storage/statuses/{$accountOwner}";
        $imageDirectory = "/images";  // Corrected image directory path
        $userFile = "../storage/users/{$accountOwner}";

        $successMessage = "User '{$username}' has been successfully deleted.";

        // Delete account files
        if (is_dir($accountDirectory)) {
            $accounts = scandir($accountDirectory);
            $accounts = array_diff($accounts, array('.', '..'));

            foreach ($accounts as $account) {
                $accountFile = "{$accountDirectory}/{$account}";
                if (file_exists($accountFile)) {
                    unlink($accountFile);
                }

                // Delete matching image folder if it exists
                $imageFolder = "{$imageDirectory}/{$account}";
                if (is_dir($imageFolder)) {
                    $files = glob($imageFolder . '/*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            unlink($file);
                        }
                    }
                    rmdir($imageFolder);
                }
            }

            // Delete the account directory if it exists and is empty
            if (count(glob($accountDirectory . '/*')) === 0) {
                rmdir($accountDirectory);
            }
        }

        // Delete status files
        if (is_dir($statusDirectory)) {
            $statuses = scandir($statusDirectory);
            $statuses = array_diff($statuses, array('.', '..'));

            foreach ($statuses as $status) {
                $statusFile = "{$statusDirectory}/{$status}";
                if (file_exists($statusFile)) {
                    unlink($statusFile);
                }
            }

            // Delete the status directory if it exists and is empty
            if (count(glob($statusDirectory . '/*')) === 0) {
                rmdir($statusDirectory);
            }
        }

        // Delete the user file
        if (file_exists($userFile)) {
            unlink($userFile);
        }

        echo '<script type="text/javascript">
        alert("User Deleted");
        window.location.href = window.location.href;
        </script>';
        exit;
    }
}
