<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: waf.php
 * Description: ChatGPT API Status Generator
 */


// Sanitize and validate input data
function sanitize_input($data)
{
    // Trim whitespace and remove HTML tags
    $data = trim(strip_tags($data));

    // Filter the input using appropriate filters
    $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    // Add additional security measures
    $data = str_replace(array("<?", "?>", "<%", "%>"), "", $data);
    $data = str_replace(array("<script", "</script"), "", $data);
    $data = str_replace(array("/bin/sh", "exec(", "system(", "passthru(", "shell_exec(", "phpinfo("), "", $data);

    return $data;
}

// Function to check if a string contains a disallowed character
function contains_disallowed_chars($str)
{
    global $disallowed_chars;
    foreach ($disallowed_chars as $char) {
        if (strpos($str, $char) !== false) {
            return true;
        }
    }
    return false;
}

// Function to check if a string contains a disallowed pattern
function contains_disallowed_patterns($str)
{
    global $disallowed_patterns;
    foreach ($disallowed_patterns as $pattern) {
        if (strpos($str, $pattern) !== false) {
            return true;
        }
    }
    return false;
}


function update_failed_attempts($ip)
{
    $db = new Database();

    // Check if the IP already exists in the database
    $db->query("SELECT * FROM ip_blacklist WHERE ip_address = :ip");
    $db->bind(':ip', $ip);
    $result = $db->single();

    if ($result) {
        // IP exists, update login attempts and check for blacklisting
        $attempts = $result['login_attempts'] + 1;
        $is_blacklisted = ($attempts >= 3) ? true : false;
        $timestamp = ($is_blacklisted) ? time() : $result['timestamp'];

        $db->query("UPDATE ip_blacklist SET login_attempts = :attempts, blacklisted = :blacklisted, timestamp = :timestamp WHERE ip_address = :ip");
        $db->bind(':attempts', $attempts);
        $db->bind(':blacklisted', $is_blacklisted);
        $db->bind(':timestamp', $timestamp);
    } else {
        // IP does not exist, insert new entry
        $db->query("INSERT INTO ip_blacklist (ip_address, login_attempts, blacklisted, timestamp) VALUES (:ip, 1, FALSE, :timestamp)");
        $db->bind(':ip', $ip);
        $db->bind(':timestamp', time());
    }
    $db->execute();
}

function is_blacklisted($ip)
{
    $db = new Database();
    $db->query("SELECT * FROM ip_blacklist WHERE ip_address = :ip AND blacklisted = TRUE");
    $db->bind(':ip', $ip);
    $result = $db->single();

    if ($result) {
        // Check if the blacklist timestamp is older than three days
        if (time() - $result['timestamp'] > (3 * 24 * 60 * 60)) {
            // Update to remove the IP from the blacklist
            $db->query("UPDATE ip_blacklist SET blacklisted = FALSE WHERE ip_address = :ip");
            $db->bind(':ip', $ip);
            $db->execute();
            return false;
        }
        return true;
    }
    return false;
}
