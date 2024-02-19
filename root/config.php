<?php
/*
 * Project: ChatGPT API
 * Version: 3.0.0
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: config.php
 * Description: ChatGPT API Status Generator
*/

define('API_KEY', 'gdfgdfgdgfdfgdfgdfg');
define('API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('MODEL', 'gpt-3.5-turbo');
define('TEMPERATURE', 0.5);
define('TOKENS', 256);
// Set your domain
define('DOMAIN', 'https://dfgdgfdfgdfgdfgdgfdgf.com');

// Set the system prompt
define('SYSTEM_MSG', 'You are a social media marketer. You will respond with professional but fun social status update and nothing else.');

// Set the maximum width for resizing
define('MAX_WIDTH', 720);

// Set the max statuses to exist in each feed
define('MAX_STATUSES', 30);

define('BASE_DIR', dirname($_SERVER['DOCUMENT_ROOT']));
define('BLACKLIST_DIR', BASE_DIR . '/storage');
define('IMAGES_DIR', BASE_DIR . '/storage/images');
define('USERS_DIR', BASE_DIR . '/storage/users');
define('ACCOUNTS_DIR', BASE_DIR . '/storage/accounts');
define('LOG_DIR', BASE_DIR . '/storage/logs');