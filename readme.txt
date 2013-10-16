=== SP News and Widget ===
Contributors: SP Technolab
Tags: news, latest news, custom post type, cpt, widget, vertical news scrolling widget, news widget
Requires at least: 3.1
Tested up to: 3.6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an extensible News custom post type, News widget and vertical news scrolling widget to Wordpress.

== Description ==

This plugin add a News custom post type,  News widget and vertical news scrolling widget to your Wordpress site.

The plugin adds a News tab to your admin menu, which allows you to enter news items just as you would regular posts.

Your all news items will appear at '/news', and single news items will appear at '/news/<permalink>'.

Default Single news and All News  templates for news items are also provided. One stylesheet is also provided with these templates so that you can design it as per your layout.

If you are getting any kind of problum with "/news" link means your are not able to see all news items then please remodify your permalinks Structure for example 
first select "Default" and save then again select "Custom Structure "  and save. 

Finally, the plugin adds a Recent News Items widget and vertical news scrolling widget , which can be placed on any sidebar available in your theme. You can set the title of this list and the number of news items to show.
 
== Installation ==

1. Upload the 'sp-news-and-widget' folder to the '/wp-content/plugins/' directory.
1. Activate the SP News plugin through the 'Plugins' menu in WordPress.
1. Add and manage news items on your site by clicking on the  'News' tab that appears in your admin menu.
1. Create a page with the name of News OR Latest News BUT Link name should be "/news"
1. (Optional) Add and configure the News Items widget and vertical news scrolling widget for one or more your sidebars.

== Frequently Asked Questions ==

= What news templates are available? =

There is one templates named 'single-news.php' which controls the display of each individual news item on a page. There is also a template named 'all-news.php' which controls the display of the list of all news items.

= Can I filter the list of news items by date? + =

Yes. Just as you can display a list of your regular posts by year, month, or day, you can display news items for a particular year (/news/2013/), month (/news/2013/04/), or day (/news/2013/04/20/).

= Do I need to update my permalinks after I activate this plugin? =

No, not usually. But if you are geting "/news" page OR 404 error on single news then please  update your permalinks to Custom Structure.   

= Are there shortcodes for news items? =

No, you just need to create a page with "News". Thats it

== Screenshots ==

1. Admin all News items view
2. Add new news
3. Single News view
4. Frontend all news view 
5. Vertical news scrolling widget
6. Admin side widgets view

== Changelog ==

= 1.0 =
* Initial release
* Adds custom post type for News item
* Adds all and single page templates for news
* Adds Letest news widget
* Adds Vertical news scrolling widget

== Upgrade Notice ==

= 1.0 =
Initial release
