=== Page2cat: Category, Pages & Posts Shortcodes ===
Contributors: swergroup, pixline
Donate link: http://swergroup.com
Tags: category, categories, pages, posts, page, post, shortcode, shortcodes, list, archives, relations, page2cat, swer
Requires at least: 3.5
Tested up to: master
Stable tag: trunk

Useful shortcodes to display a post or page content or a list of posts.

== Description ==

[![Build Status (development git master)](https://travis-ci.org/swergroup/category-pages-shortcodes.png?branch=master)](https://travis-ci.org/swergroup/category-pages-shortcodes)

Category Pages & Posts Shortcodes offers helpful shortcodes to display a post or page content, or a list of posts.
It also allow an exclusive relationship between a Category and a Page, in order to display the page content as "header" of category archives, with a simple php tag.

You can safely use shortcodes inside a post or a page, you can embed them in your templates or in your sidebar 
using the [do_shortcode](http://codex.wordpress.org/Function_Reference/do_shortcode) WordPress function, like that:

`<?php do_shortcode('[showsingle <options>"]'); ?>`

* [Documentation and usage examples](http://dev.swergroup.com/pages-and-posts-shortcodes)
* [Support Forum](http://wordpress.org/support/plugin/page2cat)


GPL2(C) 2008+ [SWER Sviluppo siti internet Torino](http://swergroup.com/sviluppo/siti-internet-torino/)

== Installation ==

1. Download the plugin, unzip, upload folder to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Look for the documentation on the [official wiki](http://dev.swergroup.com/pages-and-posts-shortcodes/wiki/Home) and learn how to use it.
1. Check in the forum if something feels wrong, but please triple check it before posting a new question. 
1. Enjoy your brand new shortcodes!

== Frequently Asked Questions ==

= Is this plugin supported? =

We'll try our best to support it on the [support forum](http://wordpress.org/support/plugin/page2cat) but we can't assure response time.
If you rely on this plugin for production websites and you can't have an answer please get in touch with our [helpdesk](http://swergroup.zendesk.com): open source plugins support starts from $5/hour, 24h turnaround response time (please note we are GMT+1). 

= How can I modify my theme? =

You can find this and more info on the [official wiki](http://dev.swergroup.com/pages-and-posts-shortcodes/wiki/Home).

= How to show all the posts of a certain category in one page? =

`[showlist catid="<category_ID>" length="<number_of_posts>"]`

where category_ID is your category number and number_of_posts is a number higher than your category posts count.
Again, you can find this and more info on the [official wiki](http://dev.swergroup.com/pages-and-posts-shortcodes/wiki/Home).

= Where is the last pre 3.0 version? =

Last pre-3.0 version is 2.6.3, [SVN r367559](http://plugins.trac.wordpress.org/browser/page2cat?rev=367559)

You can download it with a SVN client: 

`svn -r 367559 checkout http://plugins.svn.wordpress.org/page2cat/trunk/ page2cat-2.6.3`

== Screenshots ==

1. [showsingle] input (edit page)
2. [showsingle] output (shortcode rendered in host page)

== Changelog ==

= 3.2.1 =
* (21/05/2013) FIX object caching issue (props arodeus)
* (20/05/2013) FIX activation issues and rendering bug
* (10/04/2013) FIX do_shortcode on content (props Xmod08 + MRLR2)
* (21/05/2013) NEW more/better CSS styling support
* (21/04/2013) NEW online documentation & code examples
* (20/04/2013) NEW admin sections / options
* (02/02/2013) NEW initial test support

= 3.0.6 =
* (11/11/2012) FIX Warning on admin Page area. 
* (11/11/2012) FIX Better headers and descriptions
* (11/11/2012) FIX [showauto] formatting and styles

= 3.0.5 =
* (11/11/2012) Fix error on the admin Page area. 

= 3.0.3 =
* (10/11/2012) Better descriptions in edit-page and edit-category forms
* (10/11/2012) Fix links

= 3.0.2 =
* (04/11/2012) showlist query fix.

= 3.0.1 =
* (03/11/2012) *[showlist]* shortcode fix, category › page link restored.

= 3.0 =
* (30/10/2012) Complete rewrite for WordPress 3.4+

== Upgrade Notice ==

= 3.2.1 =
* (21/05/2013) DEPRECATION NOTICE: aptools-* CSS classes are deprecated, and will disappear in a future version. Please use page2cat-* classes (or custom classes) in your CSS styling. 

= 3.0.6 =
* (11/11/2012) FIX warning on admin page area, [showauto] formatting, headers and descriptions.

= 3.0.5 =
* (11/11/2012) Fix error on the admin Page area. 

= 3.0.3 =
CAUTION: This plugin requires WordPress 3.4.x, and will **break** your WP 2.5 setup.
- Better descriptions in edit-page and edit-category forms
- Fix links

= 3.0.2 = 
CAUTION: This plugin requires WordPress 3.4.x, and will **break** your WP 2.5 setup.
FIX showlist category query.

= 3.0 =
This plugin requires WordPress 3.4.x, and will **break** your WP 2.5 setup.
It also won't be compatible with [Category Page Extender](http://categorypageextender.wordpress.com) anymore. 
On activation, it will clean every option set by the previous versions. Please test it offline first.

