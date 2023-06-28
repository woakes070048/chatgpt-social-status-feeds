<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/users-forms.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_users'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $totalAccounts = $_POST['total-accounts'];
        $maxApiCalls = $_POST['max-api-calls'];
        $usedApiCalls = $_POST['used-api-calls'];
        $admin = isset($_POST['admin']) ? 1 : 0;  // Convert checkbox value to 1 or 0

        if (empty($username) || empty($password)) {
            // One or more values are not valid
            echo '<script type="text/javascript">
            alert("A field is missing or has incorrect data. Please try again.");
            window.location.href = window.location.href;
            </script>';
            exit;
        }

        // Check if the username matches the session's username
        if ($username === $_SESSION['username']) {
            echo '<script type="text/javascript">
            alert("Sorry, you can\'t delete your own account.");
            window.location.href = window.location.href;
            </script>';
            exit;
        }

        // Construct user data array
        $userData = [
            'username' => $username,
            'password' => $password,
            'total-accounts' => $totalAccounts,
            'max-api-calls' => $maxApiCalls,
            'used-api-calls' => $usedApiCalls,
            'admin' => $admin,  // Store the converted value
        ];

        // Convert array to JSON
        $userJson = json_encode($userData);

        // Define the path for the user file
        $userFile = "../storage/users/{$username}";

        // Save JSON to file
        file_put_contents($userFile, $userJson);

        // Create directories if they don't exist
        $accountsDir = "../storage/accounts/{$username}";
        if (!is_dir($accountsDir)) {
            mkdir($accountsDir, 0755, true);
        }

        // Create directories if they don't exist
        $imageDir = "images/{$username}";
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // Create directories if they don't exist
        $imageDir2 = "../storage/images/{$username}";
        if (!is_dir($imageDir2)) {
            mkdir($imageDir2, 0755, true);
        }

        // Success message
        echo '<script type="text/javascript">
        alert("User has been created or modified");
        window.location.href = window.location.href;
        </script>';
        exit;
    } elseif (isset($_POST['delete_user']) && isset($_POST['username'])) {
        $username = $_POST['username'];

        // Check if the username matches the session's username
        if ($username === $_SESSION['username']) {
            echo '<script type="text/javascript">
            alert("Sorry, you can\'t delete your own account.");
            window.location.href = window.location.href;
            </script>';
            exit;
        }

        // Define the paths
        $userFile = "../storage/users/{$username}";
        $accountDirectory = "../storage/accounts/{$username}";
        $imageDirectory = "images/{$username}";
        $imageDirectory2 = "../storage/images/{$username}";

        // Delete the account directory and its contents
        if (is_dir($accountDirectory)) {
            $files = array_diff(scandir($accountDirectory), array('.', '..'));

            foreach ($files as $file) {
                $fullPath = "$accountDirectory/$file";

                if (is_dir($fullPath)) {
                    $subFiles = array_diff(scandir($fullPath), array('.', '..'));

                    foreach ($subFiles as $subFile) {
                        $subFullPath = "$fullPath/$subFile";

                        if (is_dir($subFullPath)) {
                            array_map('unlink', glob("$subFullPath/*.*"));
                            rmdir($subFullPath);
                        } else {
                            unlink($subFullPath);
                        }
                    }

                    rmdir($fullPath);
                } else {
                    unlink($fullPath);
                }
            }

            rmdir($accountDirectory);
        }


        // Delete the image directory and its contents
        if (is_dir($imageDirectory)) {
            $files = array_diff(scandir($imageDirectory), array('.', '..'));
            foreach ($files as $file) {
                $fullPath = "$imageDirectory/$file";
                if (is_dir($fullPath)) {
                    array_map('unlink', glob("$fullPath/*.*"));
                    rmdir($fullPath);
                } else {
                    unlink($fullPath);
                }
            }
            rmdir($imageDirectory);
        }

        // Delete the image directory and its contents
        if (is_dir($imageDirectory2)) {
            $files = array_diff(scandir($imageDirectory2), array('.', '..'));
            foreach ($files as $file) {
                $fullPath = "$imageDirectory2/$file";
                if (is_dir($fullPath)) {
                    array_map('unlink', glob("$fullPath/*.*"));
                    rmdir($fullPath);
                } else {
                    unlink($fullPath);
                }
            }
            rmdir($imageDirectory2);
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
