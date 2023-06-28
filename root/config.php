<?php
// Set api/ai
// Set the api
define('API_KEY', '');
define('API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('MODEL', 'gpt-3.5-turbo');
define('TEMPERATURE', 0.7);


// Set your domain
define('DOMAIN', 'https://ai-status.servicesbyv.com');

// Set the system prompt
define('SYSTEM_MSG', 'You are a social media marketer. You will respond only with the requested status and nothing else.');
define('PROMPT_PREFIX', 'Your currant task is:');

// Set the maximum width for resizing
define('MAX_WIDTH', 720);

// Set the max statuses to exist in each feed
define('MAX_STATUSES', 30);
