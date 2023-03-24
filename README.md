# chatgpt-social-status2rss
A script and Web GUI that generates statuses using customizable prompts and add them to rss feeds That can be used to auto post statuses

## How It Works
- config.php - Contains the API key and other constants.
- status.php - Handles status generation and saving.
- feeds.php - Provides the RSS feed for each account.
- cron.php - Generates statuses for each account using the specified prompt.

Each account should have a file in the /accounts/ folder with the format: ACCOUNTNAME. The file should contain a JSON object with an account, key, and prompt property. The generated statuses will be saved in the /statuses/ folder with the format: ACCOUNTNAME.You can access the RSS feed for each account at /feeds.php?acct=ACCOUNTNAME&key=KEY. To generate statuses for a specific account using a cron.

### The Web GUI
You can use it for easy managment and sub account managment.  this helps provide easy access to change security keys, create subaccess, check statuses and delete 
