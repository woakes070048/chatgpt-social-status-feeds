<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/info-forms.php
 * Description: ChatGPT API Status Generator
 */

require_once '../lib/db.php'; // Ensure this points to your database class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["change_password"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password === $password2) {
            // Initialize database object
            $db = new Database();

            // Update password in the database
            $db->query("UPDATE users SET password = :password WHERE username = :username");
            $db->bind(':username', $username);
            $db->bind(':password', $password); // Consider using password_hash($password, PASSWORD_DEFAULT) for security
            $db->execute();

            echo '<script type="text/javascript">
    alert("Password Updated!");
    window.location.href = window.location.href;
</script>';
            exit;
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
?>
