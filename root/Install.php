<?php
// Include database class from earlier
require_once 'lib-db.php';
require_once 'config.php';

if (!INSTALLED) {
    try {
        // Create a new database instance
        $db = new Database();

        // Create tables and insert default data
        $db->query("CREATE TABLE status_updates (
            username VARCHAR(255) NOT NULL,
            account VARCHAR(255) NOT NULL,
            id INT NOT NULL,
            status TEXT,
            created_at DATETIME,
            status_image VARCHAR(255),
            INDEX (username)
        );");
        $db->execute();

        $db->query("INSERT INTO status_updates (username, account, id, status, created_at, status_image)
        VALUES ('admin', 'admin', 1, 'status update content', '2024-02-18 06:00:04', 'jdhdj.jpg');");
        $db->execute();

        $db->query("CREATE TABLE accounts (
            account VARCHAR(255) NOT NULL,
            username VARCHAR(255) NOT NULL,
            key VARCHAR(255) NOT NULL,
            prompt TEXT,
            hashtags BOOLEAN DEFAULT FALSE,
            link VARCHAR(255),
            cron INT DEFAULT 0,
            PRIMARY KEY (account),
            INDEX username_idx (username)
        );");
        $db->execute();

        $db->query("INSERT INTO accounts (account, username, key, prompt, hashtags, link, cron) 
        VALUES ('admin', 'admin', '123', 'Write a Facebook status update for my business page.', TRUE, 'https://domain.com/', 3);");
        $db->execute();

        $db->query("CREATE TABLE users (
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            total_accounts INT DEFAULT 10,
            max_api_calls BIGINT DEFAULT 9999999999,
            used_api_calls BIGINT DEFAULT 0,
            admin TINYINT DEFAULT 0,
            PRIMARY KEY (username)
        );");
        $db->execute();

        $db->query("INSERT INTO users (username, password, total_accounts, max_api_calls, used_api_calls, admin) 
        VALUES ('admin', 'admin', 10, 9999999999, 0, 1);");
        $db->execute();

        // Update the config file to set INSTALLED to true
        $configData = file_get_contents('config.php');
        $configData = str_replace("define('INSTALLED', false);", "define('INSTALLED', true);", $configData);
        file_put_contents('config.php', $configData);

        echo "Installation completed successfully.";
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
} else {
    echo "The system is already installed.";
}
?>
