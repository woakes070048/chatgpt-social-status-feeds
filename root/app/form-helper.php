<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: form-helper.php
 * Description: ChatGPT API Status Generator
 */

require_once '../config.php';
require_once "../app/admin-helper.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usersFilePath = '../storage/users/';
    $currentUser = $_SESSION['username'];
    $currentUserInfo = json_decode(file_get_contents($usersFilePath . $currentUser), true);

    // Only admins can add/update or delete users
    if ($currentUserInfo['admin'] == 1) {
        // If it's an add_update_user request
        if (isset($_POST['add_update_user'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $admin = isset($_POST['admin']) ? 1 : 0;
            $totalAccounts = $_POST['total-accounts'];
            $accountAccess = $_POST['account-access']; // This should be an array

            // Construct user data array
            $user = array(
                "username" => $username,
                "password" => $password,
                "admin" => $admin,
                "total-accounts" => $totalAccounts,
                "account-access" => $accountAccess,
            );

            // Convert array to JSON
            $userJson = json_encode($user);

            // Save JSON to file
            file_put_contents($usersFilePath . $username, $userJson);
        }

        // If it's a delete_user request
        elseif (isset($_POST['delete_user'])) {
            $username = $_POST['username'];

            // Delete the user file
            unlink($usersFilePath . $username);
        }
    } else {
        // Non-admin user tried to access admin functionality, log them out and redirect to login page
        unset($_SESSION['username']);
        header("Location: /login.php"); // Replace with your actual login page URL
        exit();
    }
    if (isset($_POST["update"])) {
        $accountName = trim($_POST["account"]);
        $key = trim($_POST["key"]);
        $prompt = trim($_POST["prompt"]);
        $hashtags = isset($_POST["hashtags"]) ? true : false;
        $link = trim($_POST["link"]);

        $accountData = [
            "account" => $accountName,
            "key" => $key,
            "prompt" => $prompt,
            "hashtags" => $hashtags,
            "link" => $link,
        ];

        $accountFile = "../storage/accounts/{$accountName}";

        if (file_exists($accountFile)) {
            file_put_contents($accountFile, serialize($accountData));
            echo '<script>
        alert("Account updated successfully.");
    </script>';
            header('Location: /index.php');
            exit;
        } else {
            echo '<script>
        alert("Account does not exist.");
    </script>';
        }
    } elseif (isset($_POST["delete"])) {
        $accountName = trim($_POST["account"]);
        $accountFile = "../storage/accounts/{$accountName}";
        $statusFile = "../storage/statuses/{$accountName}";

        if (file_exists($accountFile)) {
            unlink($accountFile);
            // Account Deleted
            header('Location: /index.php');
            exit;
        } else {
            echo '<script>
    alert("Account does not exist.");
</script>';
        }

        if (file_exists($statusFile)) {
            unlink($statusFile);
        }

        $imageFolder = "images/{$accountName}/";
        if (file_exists($imageFolder)) {
            $files = glob($imageFolder . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($imageFolder);
        }
    } elseif (isset($_POST["delete_status"])) {
        $accountName = trim($_POST["account"]);
        $index = (int) $_POST["index"];

        $statusFile = "../storage/statuses/{$accountName}";
        $statuses = file_exists($statusFile) ? unserialize(file_get_contents($statusFile)) : [];

        if (isset($statuses[$index])) {
            $imageName = "";
            $imageFile = "images/{$accountName}/img";
            $imageAssignments = [];
            if (file_exists($imageFile)) {
                $imageAssignments = file($imageFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            }

            if (isset($imageAssignments[$index])) {
                $imageName = $imageAssignments[$index];
                if ($imageName != "_NOIMAGE_") {
                    $imagePath = "images/{$accountName}/" . basename($imageName);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            unset($statuses[$index]);
            $statuses = array_values($statuses); // Reset array keys
            file_put_contents($statusFile, serialize($statuses));

            unset($imageAssignments[$index]);
            $imageAssignments = array_values($imageAssignments); // Reset array keys
            file_put_contents($imageFile, implode(PHP_EOL, $imageAssignments));
        }
    } elseif (isset($_POST["create_account"])) {
        $accountName = trim($_POST["account_name"]);
        $key = trim($_POST["key"]);
        $prompt = trim($_POST["prompt"]);
        $hashtags = isset($_POST["hashtags"]) ? true : false;
        $link = trim($_POST["link"]);

        if (!empty($accountName) && !empty($key) && !empty($prompt) && !empty($link)) { // Make sure link field is not empty
            $accountData = [
                "account" => $accountName,
                "key" => $key,
                "prompt" => $prompt,
                "hashtags" => $hashtags,
                "link" => $link,
            ];

            $accountsDir = "../storage/accounts/";
            $accountFile = $accountsDir . $accountName;

            if (!file_exists($accountsDir)) {
                mkdir($accountsDir, 0755, true);
            }

            if (!file_exists($accountFile)) {
                file_put_contents($accountFile, serialize($accountData));
                // Account Created
                header('Location: /index.php');
                exit;
            } else {
                echo '<script>
            alert("Account with this name already exists.");
           </script>';
            }
        } elseif (empty($accountName) || empty($key) || empty($prompt) || empty($link)) { // Check if any required fields are empty
            echo '<script>
            alert("Please fill in all the required fields.");
        </script>';
        }
    } elseif (isset($_POST['upload-image'])) {
        $accountName = $_POST['account_name'];
        $imageFolder = "images/{$accountName}/";
        if (!file_exists($imageFolder)) {
            mkdir($imageFolder, 0755, true);
        }
        $imageName = $_FILES['image-file']['name'];
        $imageTmpName = $_FILES['image-file']['tmp_name'];
        $imageType = $_FILES['image-file']['type'];
        $imageError = $_FILES['image-file']['error'];
        if ($imageError === 0) {
            $imagePath = $imageFolder . $imageName;
            if (move_uploaded_file($imageTmpName, $imagePath)) {
                // File uploaded successfully
            } else {
                // Failed to upload file
            }
        } else {
            // Error uploading file
        }
    } elseif (isset($_POST['delete-image'])) {
        $accountName = $_POST['account_name'];
        $imageName = $_POST['image_name'];
        $imagePath = "images/{$accountName}/{$imageName}";
        if (file_exists($imagePath)) {
            unlink($imagePath);
            // File deleted successfully

            $imgFile = "images/{$accountName}/img";
            if (file_exists($imgFile)) {
                $imageLines = file($imgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $index = array_search($imagePath, $imageLines); // Updated to search for the full image path
                if ($index !== false) {
                    unset($imageLines[$index]);
                    file_put_contents($imgFile, implode(PHP_EOL, $imageLines));
                }
            }
        } else {
            // File does not exist
            header('Location: /index.php');
            exit;
        }
    }
}
