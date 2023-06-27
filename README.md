![Header](./png_20230324_105953_0000.png)

# chatgpt-social-status2rss
A script and Web GUI that generates statuses using customizable prompts and add them to rss feeds That can be used to auto post statuses. This is mostly ment anyone who manages social media for others. This allows you to create awesome status updates regularly with little to no effort. Also allows your clients to log in and make changes. This creates feeds that are meant to be fed into software such as Chatpion's rss autoposter. You can use it for easy managment and sub account managment. This helps provide easy access to change security keys, create subaccess, check statuses and delete statuses. This script creates "accounts", each account is a feed. They each have their own cron job and sub login (optional).

## NEWEST UPDATES!!
- Just added multi user support
- Default login is admin admin
- New tabbed interface
- Added a WAF
- Improved GUI
- Added Link & Hashtags options
- Added status images
- User limits and more

![GUI](screenshot.png)

## How It Works
- Admins vs Users
Admins can create users and remove users. Users can create "accounts" each account is a prompt, link and image stash.
- Status Generation
Each account made has a cron job that can be used to create statuses on schedual or manual. Cron job links contain the account name and secret key.
- Rss Feeds
Each account feeds generated status into a Rss feed that you can you IFTTT or such to feed to social networks.
- Image Stash
Each each account aka "social account" but can be used how you want. Have a image stach. Upload images from the GUI and they are randomly attached to statuses.
- Limits
You can set max api calls and max accounts on users. you can also set max statuses. Say 30, after 30 have been generated on an account the oldest and its image is deleted when a new one is made.
- Get Started
Edit the config.php for API key and other settings. Look in storage/users/admin file for default login.

### To-Do
- Polish the GUI
- Generate AI Images?
- Improve code structure and security.

![Header](./png_20230324_105953_0000.png)

# ChatGPT Social Status2RSS
ChatGPT Social Status2RSS is a powerful script and Web GUI that takes social media management to the next level. With customizable prompts and automatic generation of engaging statuses, this tool allows you to effortlessly create captivating status updates for your clients. But it doesn't stop there! The generated statuses are seamlessly added to RSS feeds, making it a breeze to automate posting across various social media platforms.

## Exciting Updates!
We're thrilled to introduce some exciting new features and improvements:

Multi-user support: Collaborate with your team and assign different roles to manage users effectively.
Enhanced interface: Enjoy the convenience of a sleek and intuitive tabbed interface for streamlined navigation.
Robust Web Application Firewall (WAF): Your data and accounts are now even more secure with our advanced security measures.
Expanded options: Customize your statuses with links and hashtags, enhancing engagement and driving traffic.
Status images: Bring your statuses to life by attaching captivating images, grabbing attention and boosting engagement.
User limits and more: Set API call limits, maximum number of accounts per user, and maximum number of statuses to manage resources effectively.
GUI

![GUI](screenshot.png)

### How It Works
Admins vs Users
As an admin, you have full control to create and manage users within the system. Users, on the other hand, have the ability to create "accounts" that represent unique prompts, link collections, and image stashes.

#### Status Generation
Each account created in the system has its own cron job, which can be scheduled or triggered manually to generate statuses. The cron job links contain the account name and a secret key for secure access.

#### RSS Feeds
Every account automatically generates an RSS feed where the statuses are seamlessly incorporated. You can easily leverage this RSS feed by connecting it to automation tools like IFTTT, allowing for effortless posting across various social networks.

#### Image Stash
Each account serves as a "social account" with a dedicated image stash. Through the user-friendly GUI, you can upload images directly and have them randomly attached to the generated statuses. This adds visual appeal and diversity to your social media posts.

#### Limits
To ensure optimal performance and resource management, you can set limits on API calls, maximum accounts per user, and maximum statuses per account. For example, you can specify a limit of 30 statuses per account, with the system automatically removing the oldest status and its associated image when a new one is generated.

#### Get Started
Getting started is quick and easy! Simply edit the config.php file to configure your API key and other settings. For the default admin login credentials, please refer to the storage/users/admin file.

##To-Do List
We're continuously working to enhance the ChatGPT Social Status2RSS experience. Here are some items on our to-do list:

Polish the GUI to provide an even more delightful user experience.
Explore the possibility of generating AI-powered images to further enrich your statuses.
Continuously improve the code structure and security to ensure a robust and reliable platform.
Join us on this exciting journey as we revolutionize social media management and empower you with cutting-edge tools to create captivating and engaging content effortlessly.

Get ready to take your social media game to new heights with ChatGPT Social Status2RSS!