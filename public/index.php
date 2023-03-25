<?php
session_start();

require_once "config.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$isAdmin = $_SESSION["user"] === "admin";

if (isset($_POST["create_account"])) {
    $accountName = trim($_POST["account_name"]);
    $key = trim($_POST["key"]);
    $prompt = trim($_POST["prompt"]);
    $subAccountLogin = trim($_POST["sub_account_login"]);

    if (!empty($accountName) && !empty($key) && !empty($prompt)) {
        $accountData = [
            "account" => $accountName,
            "key" => $key,
            "prompt" => $prompt,
            "sub_account_login" => $subAccountLogin,
        ];

        $accountsDir = "accounts/";
        $accountFile = $accountsDir . $accountName;

        if (!file_exists($accountsDir)) {
            mkdir($accountsDir, 0755, true);
        }

        if (!file_exists($accountFile)) {
            file_put_contents($accountFile, serialize($accountData));
        } else {
            echo '<script>alert("Account with this name already exists.");</script>';
        }
    } else {
        echo '<script>alert("Please fill in all the required fields.");</script>';
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update"])) {
        $accountName = trim($_POST["account"]);
        $key = trim($_POST["key"]);
        $prompt = trim($_POST["prompt"]);
        $password = trim($_POST["password"]);

        $accountData = [
            "account" => $accountName,
            "key" => $key,
            "prompt" => $prompt,
            "password" => $password,
        ];

        $accountFile = "accounts/{$accountName}";

        if (file_exists($accountFile)) {
            file_put_contents($accountFile, serialize($accountData));
            echo '<script>alert("Account updated successfully.");</script>';
        } else {
            echo '<script>alert("Account does not exist.");</script>';
        }
    } elseif (isset($_POST["delete"])) {
        $accountName = trim($_POST["account"]);
        $accountFile = "accounts/{$accountName}";
        $statusFile = "statuses/{$accountName}";

        if (file_exists($accountFile)) {
            unlink($accountFile);
            echo '<script>alert("Account deleted successfully.");</script>';
        } else {
            echo '<script>alert("Account does not exist.");</script>';
        }

        if (file_exists($statusFile)) {
            unlink($statusFile);
        }
    } elseif (isset($_POST["delete_status"])) {
        $accountName = trim($_POST["account"]);
        $index = (int) $_POST["index"];

        $statusFile = "statuses/{$accountName}";
        $statuses = file_exists($statusFile)
            ? unserialize(file_get_contents($statusFile))
            : [];

        if (isset($statuses[$index])) {
            unset($statuses[$index]);
            file_put_contents($statusFile, serialize($statuses));
        }
    }
}

function getAccounts()
{
    $accounts = [];
    $accountFiles = glob("accounts/*");

    foreach ($accountFiles as $accountFile) {
        $accountInfo = unserialize(file_get_contents($accountFile));
        $accountInfo["name"] = basename($accountFile);
        $accounts[] = $accountInfo;
    }

    return $accounts;
}

$accounts = $isAdmin
    ? getAccounts()
    : [json_decode(file_get_contents("accounts/{$_SESSION["user"]}"), true)];

function getCronUrl($account, $Key)
{
    return "/cron.php?acct={$account}&key={$Key}";
}

function getFeedUrl($account, $Key)
{
    return "/feeds.php?acct={$account}&key={$Key}";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles.css">
    <title>Dashboard</title>
</head>

<body>
    <header>
        <div class="logo">Logo</div>
        <div class="logout-button">
            <form action="<?php echo htmlspecialchars(
                                $_SERVER["PHP_SELF"]
                            ); ?>" method="POST">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>
    <div class="container">
        <?php foreach ($accounts as $account) : ?>
            <div class="account-box">
                <div class="account-wrapper">
                    <div class="update-form">
                        <h2>
                            <?php echo htmlspecialchars($account["name"]); ?>
                        </h2>
                        <form action="<?php echo htmlspecialchars(
                                            $_SERVER["PHP_SELF"]
                                        ); ?>" method="POST">
                            <input type="hidden" name="account" value="<?php echo htmlspecialchars(
                                                                            $account["name"]
                                                                        ); ?>">
                            <label for="key-<?php echo htmlspecialchars(
                                                $account["name"]
                                            ); ?>">Key:</label>
                            <input type="text" id="key-<?php echo htmlspecialchars(
                                                            $account["name"]
                                                        ); ?>" name="key" value="<?php echo htmlspecialchars(
                                                                                        $account["key"] ?? ""
                                                                                    ); ?>">
                            <label for="prompt-<?php echo htmlspecialchars(
                                                    $account["name"]
                                                ); ?>">Prompt:</label>
                            <textarea id="prompt-<?php echo htmlspecialchars(
                                                        $account["name"]
                                                    ); ?>" name="prompt"><?php echo htmlspecialchars(
                                                                                $account["prompt"] ?? ""
                                                                            ); ?></textarea>
                            <label for="password-<?php echo htmlspecialchars(
                                                        $account["name"]
                                                    ); ?>">Sub Account Password:</label>
                            <input type="password" id="password-<?php echo htmlspecialchars(
                                                                    $account["name"]
                                                                ); ?>" name="password" value="<?php echo !empty($account["password"])
                                                                                                    ? "****"
                                                                                                    : ""; ?>">
                            <button type="submit" class="update-button" name="update">Update</button>
                            <?php if ($isAdmin) : ?>
                                <button type="submit" class="delete-button" name="delete">Delete Account</button>
                            <?php endif; ?>
                        </form>

                    </div>
                    <div class="statuses">
                        <h3>Statuses</h3>
                        <?php
                        $statusFile = "statuses/{$account["name"]}";
                        $statuses = file_exists($statusFile)
                            ? unserialize(file_get_contents($statusFile))
                            : [];
                        ?>
                        <ul>
                            <?php foreach ($statuses as $index => $status) : ?>
                                <?php if (!empty($status)) : ?>
                                    <li>
                                        <?php echo htmlspecialchars($status ?? "", ENT_QUOTES, "UTF-8"); ?>
                                        <form class="delete-status-form" action="<?php echo htmlspecialchars(
                                                                                        $_SERVER["PHP_SELF"]
                                                                                    ); ?>" method="POST">
                                            <input type="hidden" name="account" value="<?php echo htmlspecialchars(
                                                                                            $account["name"]
                                                                                        ); ?>">
                                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                                            <button type="submit" class="delete-status-button" name="delete_status">Delete</button>
                                        </form>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <div class="cron-feed-addresses">
                            <p>Cron Job: <a href="<?php echo htmlspecialchars(getCronUrl($account['name'], $account['key'])); ?>"><?php echo htmlspecialchars(getCronUrl($account['name'], $account['key'])); ?></a></p>
                            <p>Feed: <a href="<?php echo htmlspecialchars(getFeedUrl($account['name'], $account['key'])); ?>"><?php echo htmlspecialchars(getFeedUrl($account['name'], $account['key'])); ?></a></p>
                            <form action="/cron.php" method="GET">
                                <input type="hidden" name="acct" value="<?php echo htmlspecialchars($account['name']); ?>">
                                <input type="hidden" name="key" value="<?php echo htmlspecialchars($account['key']); ?>">
                                <button type="submit">Trigger Cron Job</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- New Account Box -->
        <div class="account-box">
            <div class="create-account-form">
                <h3>Create New Account</h3>
                <form action="<?php echo htmlspecialchars(
                                    $_SERVER["PHP_SELF"]
                                ); ?>" method="POST">
                    <label for="account_name">Account Name:</label>
                    <input type="text" name="account_name" id="account_name" required>
                    <label for="key">Key:</label>
                    <input type="text" name="key" id="key" required>
                    <label for="prompt">Prompt:</label>
                    <textarea name="prompt" id="prompt" required></textarea>
                    <label for="sub_account_login">Sub Account Login (optional):</label>
                    <input type="text" name="sub_account_login" id="sub_account_login">
                    <button type="submit" class="add-account-button" name="create_account">Create
                        Account</button>
                </form>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy;
            <?php echo date("Y"); ?>Your Company Name. All Rights Reserved.
        </p>
    </footer>
</body>

</html>