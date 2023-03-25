<?php
require_once '../config.php';
require_once '../lib/status.php';

if (isset($_GET['acct']) && isset($_GET['key'])) {
    $account = $_GET['acct'];
    $key = $_GET['key'];

    $accountFile = "../storage/accounts/{$account}";

    if (file_exists($accountFile)) {
        $accountInfo = unserialize(file_get_contents($accountFile));

        if ($accountInfo['key'] === $key) {
            generateStatus($account, $key, $accountInfo['prompt']);
            echo 'Status generated successfully.';
        } else {
            echo 'Invalid key.';
        }
    } else {
        echo 'Invalid account.';
    }
} else {
    echo 'Missing account or key.';
}
