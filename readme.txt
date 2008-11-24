=== WPListCal ===
Contributors: jonkern
Donate link: http://www.jonathankern.com/code/wplistcal
Tags: calendar, events
Requires at least: 2.5
Tested up to: 2.6.3
Stable tag: 1.0.8.2

WPListCal allows you to list upcoming events on your blog in a list or table format.

== Description ==

WPListCal allows you to list upcoming events on your blog in a list or table format.  It plugs straight into the Wordpress admin pages to let you keep track of events just like posts and pages.  You can then list events on a page or post using a special tag, or incorporate events into your theme files using a PHP function call.

= Version Guide =

* WordPress 2.7 or later &rarr; Use WPListCal 1.1 (development release, not yet stable)
* WordPress 2.5-2.6.3 &rarr; Use WPListCal 1.0.8.2 (current stable release)
* WordPress 2.0.3-2.3.3 &rarr; Use WPListCal 1.0.2

== Installation ==

1. Upload `wplistcal.php` to the `/wp-content/plugins/` directory or any subdirectory
1. Activate the plugin through the 'Plugins' menu in WordPress

= Upgrade =

1. DO NOT DEACTIVATE THE PREVIOUS VERSION OF THE PLUGIN! Doing so could remove all your events.
1. Upload `wplistcal.php` to the `wp-content/plugins/` directory or any subdirectory
1. Deactivate and then activate the plugin though the 'Plugins' menu in WordPress

= Usage =

1. Change the default settings on the WPListCal options page
1. If you want to list your events on a page or post, insert the tag `<!--wplistcal-->` in the body of the page/post
1. If you want to list your events in your sidebar, activate the WPListCal widget in the widget admin section
1. If you want to list your events somewhere in your theme files, insert `<?php echo wplc_show_events(); ?>`
    > You can set special parameters to overwrite the default options if you use the PHP function call.

    > All parameters are optional, but you must preserve the ordering by passing in `null` for options that you want to use defaults for.

    > __Display Mode__ (string): `'list'` or `'table'`

    > __Event Format__ (string): The format of the list entries if Display Mode is set to `'list'`.  You can use the following variables: %NAME%, %LINK%, %LINKEDNAME%, %START%, %END%, %DESCRIPTION%.

    > __Date Format__ (string): The format to display the start and end date and time.  Uses [the same date formatting that Wordpress uses](http://codex.wordpress.org/Formatting_Date_and_Time).

    > __Max. Events__ (int): The maximum number of events to display, -1 for unlimited.

    > __Show Past Events__ (boolean): true to show all events, false to show only current and future events

	> __Maximum Advanced Notice__ (int): How many days in advance to display events, -1 for unlimited.
	
	> __Event Order__ (string): `'asc'` to show the closest event first or `'desc'` to show the furthest event first.
	
	> __Hide Same Date__ (bool): Format the end date with the format string defined in the next parameter if it is on the same day as the start date.
	
	> __Date 2 Time Format__ (string): If Hide Same Date is enabled, use this format string for the end date.
	
	> __No Events Message__ (string): If there are no events, show this string instead, leave blank for none.

    > _Example:_ `<?php echo wplc_show_events('list', '%LINKEDNAME%: %START% - %END%<br />%DESCRIPTION%', 'M j, Y g:ia', -1, false, 30, 'asc', true, 'g:ia', 'Sorry, no events'); ?>`

== Frequently Asked Questions ==

= Where does WPListCal store events? =

On activation time, the plugin adds a table called &lt;prefix&gt;_wplistcal that stores all your events.

= What happens to my events when I deactivate the plugin? =

Before version 1.0.6, on deactivation, __the events table is dropped__, so if you want to save your event data, back up the table before deactivating the plugin.

As of 1.0.6, deactivating the plugin has no effect on your data. When upgrading to 1.0.6, DO NOT deactivate the plugin until you have uploaded the new version of `wplistcal.php`

= Why is WPListCal different from other Wordpress calendar plugins? =

WPListCal is specialized to provide clean list or table based output for you to style or reparse any way you'd like.  Other calendar plugins force you to use a gregorian calendar view which may be inappropriate for many applications.

= Does WPListCal work on WordPress 2.7? =

WPListCal 1.1 and later work on 2.7

= Does WPListCal work on WordPress 2.5? =

WPListCal 1.0.3 and later work on 2.5

= When I try to edit my options, I get an error: "Your attempt to edit your settings has failed." =

Download WPListCal 1.0.3 or later.

= When I click edit on the manage page, I get the error: "Cannot find wplistcal.php" =

Download WPListCal 1.0.4 or later.

= Why do some of my events show N/A for author and create date? =

Events created before upgrading to version 1.1 did not have those values set, therefore WPListCal marks them as N/A.

= I love WPListCal, but I'd like it to do &lt;blank&gt;. =

Great, I'm glad to hear feature requests.  Just post a comment on the [plugin's homepage](http://www.jonathankern.com/code/wplistcal "WPListCal Homepage").

== Changelog ==

= 1.1b1 (development release) =

* Updated all styles and elements for WordPress 2.7
* Menus refactored to fit into the new WordPress 2.7 menu structure
* Dashboard now shows number of events published
* Added a location field
* Added widget support
* Refactored code into separate files
* Fixed a non-localizable string literal in the options page

= 1.0.8.2 (current stable) =

* Removed a 2.7-only function that caused PHP warnings on 2.6
* Re-enabled media upload buttons since they now work right

= 1.0.8.1 =

* Fixed options page warning

= 1.0.8 =

* Fixed the visual editor (again)

= 1.0.7 =

* Added the option to use a different date format for the end date if the event starts and ends on the same day
* Added the option to display a message if there are no events to show
* Added the option to set rel='nofollow' on links in the event listing
* Added the option to display events in reverse order

= 1.0.6 =

* Added link field to events
* Description box now has the correct tab index on the new &amp; edit pages
* Table view now uses properly cleaned fields
* Past events option now defaults to "Only show current and future events" if it is not set
* Deactivating the plugin no longer deletes all WPListCal settings and data
* Write section tab now named Event instead of Add Event for consistency with WordPress

= 1.0.5 =

* Return of the WPListCal options tab
* Fixed the visual editor
* Fixed `htmlspecialchars_decode` function error
* Plugin now works on servers with `short_open_tag` disabled

= 1.0.4 =

* Fixed edit &amp; options links to work when `wplistcal.php` is in a subfolder
* Removed options link from edit &amp; new event pages if the user doesn't have permissions to view it
* Settings tab no longer appears to users who do not have permissions to it
* Visual editor now works properly (also fixes `switcheditors not defined` error)

= 1.0.3 =

* Fixed options bug introduced by WordPress 2.5
* Restyled admin menus to look like WordPress 2.5
* Localized a few hardcoded strings
* Added 24hr time support for the admin area
* Added advanced notice limit option
* Every other event in both the list and table view has the css class `wplc_alt` applied to it to allow alternating row formatting
* Fixed maximum events setting on `wplc_show_events`
* Fixed display of event titles containing single quotes

= 1.0.2 =

* Fixed a bug with the Maximum Events option that caused it to always default to show all events

= 1.0.1 =

* Added definition for `str_ireplace()` for servers not running PHP5

= 1.0 =

* Initial Release
