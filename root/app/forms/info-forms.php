<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/info-forms.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["change_password"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password === $password2) {
            // Load user data from file
            $userFile = USERS_DIR . "/{$username}";
            if (file_exists($userFile)) {
                $userData = json_decode(file_get_contents($userFile), true);

                // Update password
                $userData['password'] = $password;

                // Save the updated user data back to the file
                file_put_contents($userFile, json_encode($userData));

                echo '<script type="text/javascript">
    alert("Password Updated!");
    window.location.href = window.location.href;
</script>';
                exit;
            }
        } else {
            // Passwords do not match
            echo '<script type="text/javascript">
    alert("Passwords do not match. Please try again.");
    window.location.href = window.location.href;
</script>';
            exit;
        }
    }
}
