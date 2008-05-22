=== WPListCal ===
Contributors: jonkern
Tags: calendar, events
Requires at least: 2.5
Tested up to: 2.5.1
Stable tag: 1.0.5

WPListCal allows you to list upcoming events on your blog in a list or table format.

== Description ==

WPListCal allows you to list upcoming events on your blog in a list or table format.  It plugs straight into the Wordpress admin pages to let you keep track of events just like posts and pages.  You can then list events on a page or post using a special tag, or incorporate events into your theme files using a PHP function call.

**Important: If you are running WordPress 2.5, you must use WPListCal 1.0.3 or later.  If you are running a pre-2.5 version of WordPress, you should use WPListCal 1.0.2 or upgrade your WordPress installation.  1.0.2 will be the last release for pre-2.5 versions of WordPress.**

== Installation ==

1. Upload `wplistcal.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

= Usage =

1. Change the default settings on the WPListCal options page
1. If you want to list your events on a page or post, insert the tag `<!--wplistcal-->` in the body of the page/post
1. If you want to list your events somewhere in your theme files, insert `<?php echo wplc_show_events(); ?>`
    > You can set special parameters to overwrite the default options if you use the PHP function call.

    > All parameters are optional, but you must preserve the ordering by passing in `null` for options that you want to use defaults for.

    > __Display Mode__ (string): `'list'` or `'table'`

    > __Event Format__ (string): The format of the list entries if Display Mode is set to `'list'`.  You can use the following variables: %NAME%, %START%, %END%, %DESCRIPTION%.

    > __Date Format__ (string): The format to display the start and end date and time.  Uses [the same date formatting that Wordpress uses](http://codex.wordpress.org/Formatting_Date_and_Time).

    > __Max. Events__ (int): The maximum number of events to display, -1 for unlimited.

    > __Show Past Events__ (boolean): true to show all events, false to show only current and future events

	> __Maximum Advanced Notice__ (int): How many days in advance to display events, -1 for unlimited.

    > _Example:_ `<?php echo wplc_show_events('list', '%NAME%: %START% - %END%<br />%DESCRIPTION%', 'M j, Y g:ia', -1, false, 30); ?>`

== Frequently Asked Questions ==

= Where does WPListCal store events? =

On activation time, the plugin adds a table called &lt;prefix&gt;_wplistcal that stores all your events.

= What happens to my events when I deactivate the plugin? =

On deactivation, __the events table is dropped__, so if you want to save your event data, back up the table before deactivating the plugin.

= Why is WPListCal different from other Wordpress calendar plugins? =

WPListCal is specialized to provide clean list or table based output for you to style or reparse any way you'd like.  Other calendar plugins force you to use a gregorian calendar view which may be inappropriate for many applications.

= Does WPListCal work on WordPress 2.5? =

WPListCal 1.0.3 and later work on 2.5

= When I try to edit my options, I get an error: "Your attempt to edit your settings has failed." =

Download WPListCal 1.0.3 or later.

= When I click edit on the manage page, I get the error: "Cannot find wplistcal.php" =

Download WPListCal 1.0.4 or later.

= I love WPListCal, but I'd like it to do &lt;blank&gt;. =

Great, I'm glad to hear feature requests.  Just post a comment on the [plugin's homepage](http://www.jonathankern.com/code/wplistcal "WPListCal Homepage").

== Changelog ==

= 1.0.5 =

* Return of the WPListCal options tab
* Fixed the visual editor
* Fixed htmlspecialchars_decode function error
* Plugin now works on servers with short_open_tag disabled

= 1.0.4 =

* Fixed edit &amp; options links to work when wplistcal.php is in a subfolder
* Removed options link from edit &amp; new event pages if the user doesn't have permissions to view it
* Settings tab no longer appears to users who do not have permissions to it
* Visual editor now works properly (also fixes switcheditors not defined error)

= 1.0.3 =

* Fixed options bug introduced by WordPress 2.5
* Restyled admin menus to look like WordPress 2.5
* Localized a few hardcoded strings
* Added 24hr time support for the admin area
* Added advanced notice limit option
* Every other event in both the list and table view has the css class 'wplc_alt' applied to it to allow alternating row formatting
* Fixed maximum events setting on wplc_show_events
* Fixed display of event titles containing single quotes

= 1.0.2 =

* Fixed a bug with the Maximum Events option that caused it to always default to show all events

= 1.0.1 =

* Added definition for `str_ireplace()` for servers not running PHP5

= 1.0 =

* Initial Release
