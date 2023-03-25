<?php
session_start();
require_once 'config.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['user'] = 'admin';
        header('Location: index.php');
    } else {
        $accountFile = "accounts/{$username}";

        if (file_exists($accountFile)) {
            $accountInfo = json_decode(file_get_contents($accountFile), true);

            if ($accountInfo['password'] === $password) {
                $_SESSION['user'] = $username;
                header('Location: index.php');
            } else {
                $errorMessage = 'Invalid password.';
            }
        } else {
            $errorMessage = 'Invalid account.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
    <title>Login</title>
</head>

<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($errorMessage)) : ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>