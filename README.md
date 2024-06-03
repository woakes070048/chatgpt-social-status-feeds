![Header](/images/header.png)

# ChatGPT Social Status2RSS
ChatGPT Social Status2RSS is a powerful script and Web GUI that takes social media management to the next level. With customizable prompts and automatic generation of engaging statuses, this tool allows you to effortlessly create captivating status updates for your clients. But it doesn't stop there! The generated statuses are seamlessly added to RSS feeds, making it a breeze to automate posting across various social media platforms. Collaborate with your team and assign different roles to manage users effectively. Enjoy the convenience of a sleek and intuitive tabbed interface for streamlined navigation.Enjoy a robust Web Application Firewall (WAF) so your data and accounts are now even more secure with our advanced security measures Customize your statuses with links and hashtags, enhancing engagement and driving traffic. Bring your statuses to life Dall-e-3 image generation. Set API call limits, maximum number of accounts per user, and maximum number of statuses to manage resources effectively. The new help button, set your own help service.

<table>
  <tr>
    <td><img src="/images/ss-1.jpg" alt="Screenshot 1" width="100%"/></td>
    <td><img src="/images/ss-2.jpg" alt="Screenshot 2" width="100%"/></td>
    <td><img src="/images/ss-4.jpg" alt="Screenshot 4" width="100%"/></td>
  </tr>
  <tr>
    <td><img src="/images/ss-6.jpg" alt="Screenshot 6" width="100%"/></td>
    <td><img src="/images/ss-7.jpg" alt="Screenshot 7" width="100%"/></td>
    <td><img src="/images/ss-8.jpg" alt="Screenshot 8" width="100%"/></td>
  </tr>
  <tr>
    <td><img src="/images/ss-9.jpg" alt="Screenshot 9" width="100%"/></td>
    <td><img src="/images/ss-10.jpg" alt="Screenshot 10" width="100%"/></td>
    <td><img src="/images/ss-11.jpg" alt="Screenshot 11" width="100%"/></td>
  </tr>
</table>

### How It Works
Admins vs Users
As an admin, you have full control to create and manage users within the system. Users, on the other hand, have the ability to create "accounts" that represent unique prompts, link collections, and image stashes. Each account created in the system has its own cron job, which can be scheduled or triggered manually to generate statuses. The cron job links contain the account name and a secret key for secure access.

Every account automatically generates an RSS feed where the statuses are seamlessly incorporated. You can easily leverage this RSS feed by connecting it to automation tools like IFTTT, allowing for effortless posting across various social networks. Each account serves as a "social account" with a dedicated image prompt. Dall-e-3 generates an image to go with each status.

To ensure optimal performance and resource management, you can set limits on API calls, maximum accounts per user, and maximum statuses per account. For example, you can specify a limit of 30 statuses per account, with the system automatically removing the oldest status and its associated image when a new one is generated.

### Get Started
- Getting started is quick and easy! Simply edit the config.php file to configure your API key and other settings.
- Upload files to web server.
- Change docroot to public folder.
- Create MySQL Database
- Update config.php
- Add cron jobs
  0	12	1	*	* /usr/bin/php /PATH_TO/public_html/cron.php reset_usage
  0	12	*	*	0 /usr/bin/php /PATH_TO/public_html/cron.php clear_list
  0	*	*	*	* /usr/bin/php /PATH_TO/public_html/cron.php run_status
- Login admin/admin
- Get ready to take your social media game to new heights with ChatGPT Social Status2RSS!


### Newest Updates
- Completely moved to Mysql DB.
- Added Login-as user option for Admins.
- You can now schedule posts for Account/Campaigns by day(s) of the week and hours(s).
