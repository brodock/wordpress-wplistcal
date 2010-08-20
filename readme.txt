=== WPListCal ===
Contributors: jonkern
Donate link: http://www.jonathankern.com/code/wplistcal
Tags: calendar, events
Requires at least: 2.7
Tested up to: 3.0-alpha
Stable tag: 1.3.5
Text Domain: wplistcal

WPListCal allows you to list upcoming events on your blog in a list or table format.

== Description ==

WPListCal allows you to list upcoming events on your blog in a list or table format.  It plugs straight into the Wordpress admin pages to let you keep track of events just like posts and pages.  You can then list events on a page or post using a shortcode, show events in your sidebar with a widget, or incorporate events into your theme files using a PHP function call.

= Version Guide =

* WordPress 2.7 or later &rarr; Use WPListCal 1.3.5 (current stable release)
* WordPress 2.5-2.6.3 &rarr; Use WPListCal 1.0.8.2
* WordPress 2.0.3-2.3.3 &rarr; Use WPListCal 1.0.2

== Installation ==

1. Upload the `wplistcal` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

= Upgrade =

1. If you are upgrading from version 1.0.5 or earlier, DO NOT DEACTIVATE THE PREVIOUS VERSION OF THE PLUGIN! Doing so will remove all your events.
1. Upload the `wplistcal` folder to the `wp-content/plugins/` directory
1. Deactivate and then activate the plugin though the 'Plugins' menu in WordPress

= Usage =

1. Change the default settings on the WPListCal options page
1. If you want to list your events on a page or post, use the [wplistcal] shortcode. Use the parameters in the example below. Leave out parameters to default to the options defined in the WPListCal settings page. (1.2 or later only)
	> _Example:_ `[wplistcal display_mode="list", event_format="%NAME%", date_format="M j, Y g:ia", max_events="-1", show_past_events="false", advance_days="-1", event_order="asc", hide_same_date="true", date2_time_format="g:ia", no_events_msg="No events!"]`

1. If you want to list your events on a page or post, insert the tag `<!--wplistcal-->` in the body of the page/post
1. If you want to list your events in your sidebar, activate the WPListCal widget in the widget admin section
1. If you want to list your events somewhere in your theme files, insert `<?php echo wplc_show_events(); ?>`
    > You can set special parameters to overwrite the default options if you use the PHP function call.

    > All parameters are optional, but you must preserve the ordering by passing in `null` for options that you want to use defaults for.

    > __Display Mode__ (string): `'list'` or `'table'`

    > __Event Format__ (string): The format of the list entries if Display Mode is set to `'list'`.  You can use the following variables: %NAME%, %LINK%, %LINKEDNAME%, %START%, %END%, %DESCRIPTION%, %AUTHOR%, and %EXPORTURL%. You can also make statements dependent on a variable by wrapping them in curly brackets (ex. {Date: %START%}). See &quot;Dependent Statements&quot; below for more information.

    > __Date Format__ (string): The format to display the start and end date and time.  Uses [the same date formatting that Wordpress uses](http://codex.wordpress.org/Formatting_Date_and_Time).

    > __Max. Events__ (int): The maximum number of events to display, -1 for unlimited.

    > __Show Past Events__ (boolean): true to show all events, false to show only current and future events

	> __Maximum Advanced Notice__ (int): How many days in advance to display events, -1 for unlimited.
	
	> __Event Order__ (string): `'asc'` to show the closest event first or `'desc'` to show the furthest event first.
	
	> __Hide Same Date__ (bool): Format the end date with the format string defined in the next parameter if it is on the same day as the start date.
	
	> __Date 2 Time Format__ (string): If Hide Same Date is enabled, use this format string for the end date.
	
	> __No Events Message__ (string): If there are no events, show this string instead, leave blank for none.

    > _Example:_ `<?php echo wplc_show_events('list', '%LINKEDNAME%: %START% - %END%{<br />%DESCRIPTION%} <a href="%EXPORTURL%">(export)</a>', 'M j, Y g:ia', -1, false, 30, 'asc', true, 'g:ia', 'Sorry, no events'); ?>`

= Dependent Statements (1.2 or later only) =

You can make a statement dependent on the existence of a variable by wrapping it in curly brackets. By default, the statement will only print if the first variable in the statement is not empty. You cannot have nested dependent statements.

**Example 1:**

The statement in the curly brackets won't print if %LOCATION% is empty

	%TITLE%{ at %LOCATION%} on %START%

**Example 2:**

To print a literal curly bracket, escape it with '^'

	%TITLE%{ at %LOCATION} ^{new^}

**Example 3:**

To skip a variable when determining the dependent variable, escape its '%' characters with '^'. This method also works to print a literal '%' inside a dependent statement. In this example, the statement in the curly brackets will print if %LOCATION% is not empty. Note that %AUTHOR% will be properly substituted even though it is escaped.

	%TITLE%{ hosted by ^%AUTHOR^% at %LOCATION%}

**Example 4:** (invalid)

This example is invalid. You cannot have nested dependent statements.

	%TITLE{ at %LOCATION%{ on %START%}}

**Example 5:**

However, you can have multiple dependent statements in a format.

	%TITLE%{ hosted by %AUTHOR%}{ at %LOCATION} on %START%

== Frequently Asked Questions ==

= Where does WPListCal store events? =

On activation time, the plugin adds a table called &lt;prefix&gt;_wplistcal that stores all your events.

= What happens to my events when I deactivate the plugin? =

Before version 1.0.6, on deactivation, __the events table is dropped__, so if you want to save your event data, back up the table before deactivating the plugin.

As of 1.0.6, deactivating the plugin has no effect on your data. When upgrading to 1.0.6, DO NOT deactivate the plugin until you have uploaded the new version of `wplistcal.php`

= Why is WPListCal different from other Wordpress calendar plugins? =

WPListCal is specialized to provide clean list or table based output for you to style or reparse any way you'd like.  Other calendar plugins force you to use a gregorian calendar view which may be inappropriate for many applications.

= Why do some of my events show N/A for author and create date? =

Events created before upgrading to version 1.1 did not have those values set, therefore WPListCal marks them as N/A.

= I am unable to make events that start or end past January 19, 2038 at 3:14:08am =

This is a known bug in PHP (id# [44209](http://bugs.php.net/44209)) and was fixed in version 5.2.6. The specific issue was that strtotime() did not support 64-bit timestamps.

= Does WPListCal use any 3rd party libraries? =

Yes, WPListCal is packaged with iCalcreator which is released under the GNU LGPL

= My event times are all wrong after upgrading to WordPress 2.9. =

Go to WordPress General settings and reset your timezone to a city rather than a manual UTC offset.

= I love WPListCal, but I'd like it to do &lt;blank&gt;. =

Great, I'm glad to hear feature requests.  Just post a comment on the [plugin's homepage](http://www.jonathankern.com/code/wplistcal "WPListCal Homepage").

== Changelog ==

= 1.3.5 =

* FIXED: WordPressMU support on options page (thanks Gabriel Mazetto for the patch)
* FIXED: Multiple i18n bugs, now using date_i18n to allow localized dates (thanks Jonas for the patch)

= 1.3.4 =

* FIXED: Event cleanup link was pointed incorrectly and didn't work properly
* FIXED: Linked "events" in the dashboard to match other dashboard counters

= 1.3.3 =

* FIXED: Updated compatible version to 2.9 (no code changes)

= 1.3.2 =

* FIXED: timezone_abbreviations_list() error

= 1.3.1 =

* FIXED: Event Operations page had an invalid link for Export

= 1.3 =

* NEW: Export single events from event listing (%EXPORTURL%)
* NEW: Refactored export and cleanup into one admin page
* FIXED: Resolved an issue where the upload settings are not updated on each activation of the plugin

= 1.2.2 =

* FIXED: WordPress 2.8 compatibility
* FIXED: Cursor is no longer 'move' for section headers in the event edit page since you can't drag anyway

= 1.2.1 =

* FIXED: Table view didn't show events
* NEW: Added POT file for translation

= 1.2 =

* NEW: Event cleanup
* FIXED: Updated admin menus to use Wordpress Capabilities instead of user levels (fixes settings page bug)
* NEW: Conditional format strings (i.e. bracketed statements)
* NEW: Shortcode support
* FIXED: All timestamps are now based on the WordPress timezone option instead of server time

= 1.1.1 =

* FIXED: Moved the timezone functions into a PHP5-only block for back-compat
* FIXED: Load scripts only when we're on a WPListCal page

= 1.1 =

* NEW: Updated all styles and elements for WordPress 2.7
* NEW: Menus refactored to fit into the new WordPress 2.7 menu structure
* NEW: Dashboard now shows number of events published
* NEW: Location field
* NEW: Widget support
* FIXED: Refactored code into separate files
* FIXED: Replaced a non-localizable string literal in the options page
* NEW: Event export
* NEW: Added link to the WordPress 2.7 admin favorites menu

= 1.0.8.2 =

* FIXED: Removed a 2.7-only function that caused PHP warnings on 2.6
* FIXED: Re-enabled media upload buttons since they now work right

= 1.0.8.1 =

* FIXED: Options page warning

= 1.0.8 =

* FIXED: Visual editor was broken (again)

= 1.0.7 =

* NEW: Option to use a different date format for the end date if the event starts and ends on the same day
* NEW: Option to display a message if there are no events to show
* NEW: Option to set rel='nofollow' on links in the event listing
* NEW: Option to display events in reverse order

= 1.0.6 =

* NEW: Link field on events
* FIXED: Description box now has the correct tab index on the new &amp; edit pages
* FIXED: Table view now uses properly cleaned fields
* FIXED: Past events option now defaults to "Only show current and future events" if it is not set
* FIXED: Deactivating the plugin no longer deletes all WPListCal settings and data
* FIXED: Write section tab now named Event instead of Add Event for consistency with WordPress

= 1.0.5 =

* FIXED: Return of the WPListCal options tab
* FIXED: the visual editor didn't work
* FIXED: `htmlspecialchars_decode` function threw error
* FIXED: Plugin now works on servers with `short_open_tag` disabled

= 1.0.4 =

* FIXED: Edit &amp; options links to work when `wplistcal.php` is in a subfolder
* FIXED: Removed options link from edit &amp; new event pages if the user doesn't have permissions to view it
* FIXED: Settings tab no longer appears to users who do not have permissions to it
* FIXED: Visual editor now works properly (also fixes `switcheditors not defined` error)

= 1.0.3 =

* FIXED: Options bug introduced by WordPress 2.5
* NEW: Restyled admin menus to look like WordPress 2.5
* FIXED: Localized a few hardcoded strings
* NEW: 24hr time support for the admin area
* NEW: Advanced notice limit option
* NEW: Every other event in both the list and table view has the css class `wplc_alt` applied to it to allow alternating row formatting
* FIXED: Maximum events setting on `wplc_show_events` was broken
* FIXED: Event titles containing single quotes printed wrong

= 1.0.2 =

* FIXED: Maximum Events option always defaulted to show all events

= 1.0.1 =

* FIXED: Added definition for `str_ireplace()` for servers not running PHP5

= 1.0 =

* Initial Release
