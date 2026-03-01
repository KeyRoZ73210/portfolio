=== Spam Protect for Contact Form 7 ===
Contributors: nysl
Tags: Contact Form 7 security, Form spam prevention, Website form protection, Anti-spam plugin, WordPress form security
Requires at least: 5.2
Tested up to: 6.8
Stable tag: 1.2.10
Requires PHP: 5.4
Donate link: support@nysoftwarelab.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Spam Protect for Contact-Form7 protects from spam and bots. Customize defense strategies and monitor blocked attempts. Protect your time effectively!

== Description ==

Spam Protect for Contact Form 7, the ultimate solution to shield your website from the nuisance of spam and intrusive bots. With this incredible, user-friendly WordPress plugin, bid farewell to the hassle of sifting through irrelevant and unsolicited form submissions.

Gone are the days of wasting precious time on spammy data, advertisements, and unwanted contact details cluttering your inbox. Our plugin empowers you to take control effortlessly. Simply navigate to the Contact Form 7 edit screen and discover the all-new tab, exclusively designed to combat spam.

Customize your defense strategy by effortlessly adding emails, domains, or specific words and phrases to the block settings. As spammers and bots often employ consistent email domains and commonly used words for their marketing endeavors, you can now proactively prevent their mischief. Watch as their attempts to submit forms are thwarted, replaced by a sleek, custom error message of your choosing.

But worry not about blocking genuine visitors inadvertently! Our innovative log file system provides you with insightful monitoring, allowing you to identify and understand each blocked attempt. Stay confident that you're preserving the engagement of your valued audience while keeping the disruptive elements at bay.

Experience the unrivaled convenience and effectiveness of Spam Protect for Contact Form 7 today. Streamline your website's communication, protect your time, and bid farewell to spam like never before.

== Installation ==

Step #1: Edit any CF7 form and click on `Antispam Settings` tab.
Step #2: Add the emails, email's domain and/or and/or words and phrases as comma seperated values that you want to block.
Step #3: Enter the message to be shown to the spammers or bots in 'Set your error message' field.
Step #4: Set `Log the failed messages` option to `Yes` in order to have a control of what you are blocking.
Step #5: Click `Save`

OR

1. Upload `spam-protect-for-contact-form7` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Goto contact tab and Edit any CF7 form and click on `Antispam Settings` tab.
4. Add the emails, email's domain and/or and/or words and phrases as comma seperated values that you want to block.
5. Enter the message to be shown to the spammers or bots in 'Set your error message' field.
6. Step #4: Set `Log the failed messages` option to `Yes` in order to have a control of what you are blocking.
7. Click Save.

== Frequently Asked Questions ==

= Is the plugin settings global for all forms that I have ? =

No the settings are indepentent for each form. 
Each form has its own antispam settings and even a different log file if needed.

== Screenshots ==
1. Antispam Setting in contact form 7.
2. Restrict Spammer or Bot to submit the form.
3. Check the Log file.

== Changelog ==

= 1.2.10 =
Removed permanently the JS implementation
Removed analyzed log request feature
Fixed log file extension to always be (.log)

= 1.2.9 =
Unknown conflict with WooComerce, removed latest features from plugin temporary until further investigation. 

= 1.2.8 =
Minor bug fix, php warning in enqueue_scripts function
Readme file adjustments.

= 1.2.7 =
Minor fixes after CF7 Ver: 6.1 (latest major update)
Tested successfully with Wordpress 6.8.2

= 1.2.6 =
Tested successfully with Wordpress 6.8

= 1.2.5 =
svn file structure fixes

= 1.2.4 =
Tested successfully with Wordpress 6.6

= 1.2.3 =
Tested successfully with Wordpress 6.5

= 1.2.2 =
Fixed minor bug displaying false errors.
Improved field descriptions.

= 1.2.1 =
Fixed bug, custom log file didn't clear.

= 1.2.0 =
New better UI
New feature to block short links
New feature to block Top Level Domains
New feature to protect your form from blank text submitions
New feature to erase the logfile
New feature to request an analysis of your log file from our company NYSL
Improved spam text check.

= 1.1.9 =
Fixed missing variable in Log file.

= 1.1.8 =
Fixed typo in Log file.
Improved spam text detection.
Tested successfully with 6.4

= 1.1.6 =
Tested successfully with 6.3

= 1.1.5 =
Added ability to set your own filename for log.
Each Contact Form can have a different log file now.
Updated texts and descriptions in admin UI.

= 1.0.10 =
Fixed bug affecting the spaces in banned words list.

Added visitors IP address in log file.

= 1.0.0 =
First version launched

== A brief Markdown Example ==

1. Manually email block.
2. Email domain block.
3. Words and phrases block.
4. Top level domains block.
5. Protect form from messages that contain shortlinks.
6. Protect from blank text submitions.
7. Log the failed messages.

== Upgrade Notice ==

= 1.2.10 =
Removed permanently the JS implementation
Removed analyzed log request feature
Fixed log file extension to always be (.log)

= 1.2.9 =
Unknown conflict with WooComerce, removed latest features from plugin temporary until further investigation. 

= 1.2.8 =
Minor bug fix, php warning in enqueue_scripts function
Readme file adjustments.

= 1.2.7 =
Minor fixes after CF7 Ver: 6.1 (latest major update)
Tested successfully with Wordpress 6.8.2

= 1.2.6 =
Tested successfully with Wordpress 6.8

= 1.2.5 =
svn file structure fixes

= 1.2.4 =
Tested successfully with Wordpress 6.6

= 1.2.3 =
Tested successfully with Wordpress 6.5

= 1.2.2 =
Fixed minor bug displaying false errors.
Improved field descriptions.

= 1.2.1 =
Fixed bug, custom log file didn't clear.

= 1.2.0 =
New better UI
New feature to block short links
New feature to block Top Level Domains
New feature to protect your form from blank text submitions
New feature to erase the logfile
New feature to request an analysis of your log file from our company NYSL
Improved spam text check.

= 1.1.9 =
Minor changes and compatibility tests

= 1.1.8 =
Fixed typo in Log file.
Improved spam text detection.
Tested successfully with 6.4

= 1.1.6 =
Tested successfully with 6.3

= 1.1.5 =
Added ability to set your own filename for log.
Each Contact Form can have a different log file now.
Updated texts and descriptions in admin UI.

= 1.0.10 =
Fixed bug affecting the spaces in banned words list.
Added visitors IP address in log file.

= 1.0.0 =
Launched with basic requirements
