=== WordPress Power Tools ===
Contributors: matstars
Donate link: http://matgargano.com/donate
Tags: powertools, power tools, toolbar, tool bar, adminbar, admin bar
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 1.0

A backend-centric suite of tools designed to handle the non-sense grunt work on your WordPress installs.

== Description ==
WordPress Power Tools is a backend-centric suite of tools designed to handle the non-sense grunt work on your WordPress installs. A major drive is to consolidate these menial tasks into a single plugin while minimizing resource usage using efficient function calls and database queries, especially on the front-end of the WordPress installation.

It's current feature set includes:

 * An option to remove the WordPress Toolbar that appears for logged-in users who are viewing your live site. 
 * An option to remove the WordPress Toolbar when logged in to the back-end section for your WordPress install. +NEW+ When this is seleted, a menu item linking to live site is added to admin menu.  
 * +NEW+ A way to view and manage your options set in your wp_options (or PREFIX_options) table.
 * Completely rewritten object oriented and optimized code.  

== Installation ==
1. If installing manually, unzip and upload resulting directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Pat yourself on the back on a job well done!

== Frequently Asked Questions ==

= Does this plugin compensate for the loss of a link to the WordPress live site when removing the toolbar in the admin section? =
Yes it does! When opting to hide the Toolbar for logged in users in the administration section, WordPress Power Tools now adds a menu item with your site's title in the admin section that is lost when hiding the toolbar! Neato, huh?

= What if I have a question or suggestion? = 
Feel free to create a thread in the WordPress.org bbPress forum with the tag "wordpress-power-tools " or you can always catch me on Twitter, where my handle is @matgargano.

= Does this plugin work with versions of PHP < 5.2 and WordPress < 3.2?
Sorry, as WordPress dropped support for PHP 5.2 in version 3.2, I too have made the decision to only support WordPress version 3.2 and higher in a PHP 5.2 and higher environment. You will be unable to activate the plugin in such an environment.   

== Screenshots ==

1. WordPress Power Tools General Options
2. WordPress Power Tools Options Manager 

== Changelog ==
= 1.0 =
 * Added menu item linking to live site when hiding toolbar in admin section.  
 * Added front-end interface to manage/delete options set in your options database table.
 * Completely rewritten object oriented and optimized code.  

= 0.7 =
* Fixed minor code formatting issues

= 0.6 =

* Fixed a bug prohibiting the plugin to activate

= 0.3 =
* Updated to work with WordPress 3.3 "SONNY"
* Added the option of removing the toolbar in the admin section, site section or both  

= 0.2 =
* Updated the function names to avoid "cannot redeclare" error messages. (bugfix)  

= 0.1 =
* First version released.