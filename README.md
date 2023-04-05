![Header](./png_20230324_105953_0000.png)

# chatgpt-social-status2rss
A script and Web GUI that generates statuses using customizable prompts and add them to rss feeds That can be used to auto post statuses.


## Who This Is For
This is mostly ment anyone who manages social media for others. This allows you to create awesome status updates regularly with little to no effort. Also allows your clients to log in and make changes. This creates feeds that are meant to be fed into software such as Chatpion's rss autoposter.

## How It Works
- config.php - Contains the API key and other constants.
- status-helper.php - Handles status generation and saving.
- feeds.php - Provides the RSS feed for each account.
- cron.php - Generates statuses for each account using the specified prompt.

Each account should have a file in the /accounts/ folder with the format: ACCOUNTNAME. The file should contain a JSON object with an account, key, and prompt property. The generated statuses will be saved in the /statuses/ folder with the format: ACCOUNTNAME.You can access the RSS feed for each account at /feeds.php?acct=ACCOUNTNAME&key=KEY. To generate statuses for a specific account using a cron.  Basically use the feed as the source for status updates with whatever managment platform, bot or social content script you use aka like Chatpion. I'm using flat files storage honestly because it's easier and because it seems like a waste to use a whole database for something that's as simple with so little file writing.

You can upload images to each feed. if you upload 2 images the next 2 statuses will generate attaching an image to each one. If you generate another status it will be assigned _NOIMAGE_. If you upload 3 more the next 3 statuses will be assigned images. Deleting a status deletes the image.

### The Web GUI
You can use it for easy managment and sub account managment. This helps provide easy access to change security keys, create subaccess, check statuses and delete statuses. This script creates "accounts", each account is a feed. They each have their own cron job and sub login (optional).

-  Administrative account can edit all accounts and feeds. Sub accounts can edit only their feed.
-  Change security keys for feeds.
-  Change the sub account logins.
-  Generate a status.
-  Change account prompt.
-  Delete a status from a feed.

### To-Do
- Polish the GUI
- Generate AI Images?

### Resent Changes
- Added a WAF
- Improved GUI
- Added Link & Hashtags options
- Added status images