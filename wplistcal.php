<?php
/*
Plugin Name: WPListCal
Plugin URI: http://www.jonathankern.com/code/wplistcal
Description: WPListCal will display a simple listing of events anywhere on your Wordpress site.
Version: 1.1b1
Author: Jonathan Kern
Author URI: http://www.jonathankern.com

Permission is hereby granted, free of charge, to any person or organization
obtaining a copy of the software and accompanying documentation covered by
this license (the "Software") to use, reproduce, display, distribute,
execute, and transmit the Software, and to prepare derivative works of the
Software, and to permit third-parties to whom the Software is furnished to
do so, all subject to the following:

The copyright notices in the Software and this entire statement, including
the above license grant and author attributions, this restriction, and the
following disclaimer, must be included in all copies of the Software, in
whole or in part, and all derivative works of the Software, unless such
copies or derivative works are solely in the form of machine-executable 
object code generated by a source language processor.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. IN NO EVENT
SHALL THE COPYRIGHT HOLDERS OR ANYONE DISTRIBUTING THE SOFTWARE BE LIABLE
FOR ANY DAMAGES OR OTHER LIABILITY, WHETHER IN CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
*/

define("WPLC_DB_VERSION", "1.1b");
$wplc_domain = "wplistcal";
$wplc_is_setup = false;
$wplc_plugin = plugin_basename(__FILE__);
$wplc_dir = "../wp-content/plugins".dirname("/".$wplc_plugin);

// If not running PHP5, define str_ireplace()
if(!function_exists("str_ireplace")) {
	function str_ireplace($search, $replace, $subject) {
	   $i = 0;
	   while($pos = strpos(strtolower($subject), $search, $i)) {
	       $subject = substr($subject, 0, $pos).$replace.substr($subject, $pos+strlen($search));
	       $i = $pos+strlen($replace);
	   }
	   return $subject;
	}
}

if(!function_exists("htmlspecialchars_decode")) {
	function htmlspecialchars_decode($string,$style=ENT_COMPAT) {
		$translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
		if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
		return strtr($string,$translation);
	}
}

if(!$wplc_is_included) {
	$wplc_is_included = true;
	
	// Localization setup
	function wplc_setup() {
		global $wplc_domain, $wplc_is_setup;
		if($wplc_is_setup)
			return;
		load_plugin_textdomain($wplc_domain, 'wp-content/plugins');
		$wplc_is_setup = true;
	}

	// Plugin DB Installation
	function wplc_install() {
		wplc_setup();
		global $wpdb, $wplc_domain, $current_user;
		get_currentuserinfo();
	
		$tbl_name = $wpdb->prefix."wplistcal";
	
		// Check if DB exists and add it if necessary
		if($wpdb->get_var("SHOW TABLES LIKE '$tbl_name'") != $tbl_name) {
			$sql = "CREATE TABLE ".$tbl_name."(
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				event_name text NOT NULL,
				event_link text,
				event_loc text,
				event_desc text,
				event_start_time bigint(11) DEFAULT '0' NOT NULL,
				event_end_time bigint(11) DEFAULT '0' NOT NULL,
				event_author bigint(20) unsigned,
				event_create_time bigint(11) DEFAULT '0' NOT NULL,
				event_modified_time bigint(11) DEFAULT '0' NOT NULL,
				PRIMARY KEY  id (id)
			);";
		
			require_once(ABSPATH.'wp-admin/upgrade-functions.php');
			dbDelta($sql);
		
			// Add dummy data
			$welcome_event_name = __("Add events to WPListCal", $wplc_domain);
			$welcome_event_desc = __("Congratulations, you've just installed WPListCal! Now you just need to add your events into the system via the event tab in the Write area of the admin panel.", $wplc_domain);
			$welcome_event_start_time = time();
			$welcome_event_end_time = time() + 3600;
			$welcome_event_create_mod_time = time();
		
			$insert = "INSERT INTO ".$tbl_name.
					  " (event_name, event_desc, event_start_time, event_end_time, event_create_time, event_modified_time, event_author) ".
					  "VALUES('".$wpdb->escape($welcome_event_name)."',
							  '".$wpdb->escape($welcome_event_desc)."',
							  '".$wpdb->escape($welcome_event_start_time)."',
							  '".$wpdb->escape($welcome_event_end_time)."',
							  '".$wpdb->escape($welcome_event_create_mod_time)."',
							  '".$wpdb->escape($welcome_event_create_mod_time)."',
							  '".$wpdb->escape($current_user->ID)."');";
			$results = $wpdb->query($insert);
		}
		
		// If an option already exists, these functions do nothing
		add_option("wplc_db_version", WPLC_DB_VERSION);
		add_option("wplc_tbl_name", $tbl_name);
		add_option("wplc_date_format", "M j, Y g:ia");
		add_option("wplc_display_mode", "list");
		add_option("wplc_event_format", "<strong>%LINKEDNAME%</strong> &mdash; %START% - %END%\n<div style='margin-left:20px;'>%DESCRIPTION%</div>");
		add_option("wplc_max_events", -1);
		add_option("wplc_advance_days", -1);
		add_option("wplc_show_past_events", false);
		add_option("wplc_manage_items_per_page", 25);
		add_option("wplc_use_24hr_time", false);
		add_option("wplc_open_links_in_new_window", false);
		add_option("wplc_event_order", "asc");
		add_option("wplc_hide_same_date", true);
		add_option("wplc_date2_time_format", "g:ia");
		add_option("wplc_nofollow_links", true);
		add_option("wplc_no_events_msg", "");
		add_option("wplc_widget_title", __("Upcoming Events", $wplc_domain));
		
		wplc_upgrade_if_needed();
	}

	register_activation_hook(__FILE__, "wplc_install");
	
	// Upgrades the database schema if necessary
	function wplc_upgrade_if_needed() {
		global $wpdb;
		$installed_ver = get_option("wplc_db_version");
		if($installed_ver != WPLC_DB_VERSION) {
			$tbl_name = $wpdb->prefix."wplistcal";
			require_once(ABSPATH."wp-admin/includes/upgrade.php");
			
			// v1.0 -> v1.0.6
			$sql = "ALTER TABLE $tbl_name ADD event_link text;";
			maybe_add_column($tbl_name, "event_link", $sql);
			
			// v1.0.6 -> v1.1b
			$sql = "ALTER TABLE $tbl_name ADD event_loc text;";
			maybe_add_column($tbl_name, "event_loc", $sql);
			$sql = "ALTER TABLE $tbl_name ADD event_create_time bigint(11) DEFAULT '0' NOT NULL;";
			maybe_add_column($tbl_name, "event_create_time", $sql);
			$sql = "ALTER TABLE $tbl_name ADD event_modified_time bigint(11) DEFAULT '0' NOT NULL;";
			maybe_add_column($tbl_name, "event_modified_time", $sql);
			$sql = "ALTER TABLE $tbl_name ADD event_author bigint(20) UNSIGNED;";
			maybe_add_column($tbl_name, "event_author", $sql);
			
			update_option("wplc_db_version", WPLC_DB_VERSION);
		}
	}

	// Show the event list
	//----------------------------------------------------------------------------------------------
	// Parameters (all optional - defaults are defined on the options page):
	// display_mode (string): Either "list" or "table"
	// event_format (string): The format of the event string. You can use %NAME%, %LINK%, %LINKEDNAME%,
	//    %LOCATION%, %DESCRIPTION%, %START%, %END%, and %AUTHOR% to include event data
	// date_format (string): The format for dates/times. Use the PHP date() format just like
	//	  Wordpress options. Instructions available at http://us.php.net/manual/en/function.date.php
	// max_events (int):  the maximum number of events to display, defaults to -1 (show all)
	// show_past_events (bool): whether to show past events, defaults to false
	// advance_days (int): the amount of days in advance to display events, -1 for no limit
	// event_order (string): Either "asc" or "desc". "asc" shows the closest event first, 
	//    "desc" shows the furthest event first
 	// hide_same_date (bool): whether to hide the second date if it is on the same day, defaults to true
	// date2_time_format (string): if hide_same_date is true, then format the second timestamp with this
	// no_events_msg (string): The message to show if there are no events to display, empty string for none
	function wplc_show_events($display_mode=null, $event_format=null, $date_format=null, $max_events=null, $show_past_events=null, $advance_days=null, $event_order=null, $hide_same_date=null, $date2_time_format=null, $no_events_msg=null) {
		wplc_setup();
		global $wplc_domain, $wpdb;
	
		// Setup default parameter values
		wplc_set_if_null($display_mode, "wplc_display_mode");
		wplc_set_if_null($event_format, "wplc_event_format");
		wplc_set_if_null($date_format, "wplc_date_format");
		wplc_set_if_null($max_events, "wplc_max_events");
		wplc_set_if_null($advance_days, "wplc_advance_days");
		wplc_set_if_null($show_past_events, "wplc_show_past_events");
		wplc_set_if_null($hide_same_date, "wplc_hide_same_date");
		wplc_set_if_null($date2_time_format, "wplc_date2_time_format");
		wplc_set_if_null($no_events_msg, "wplc_no_events_msg");
		wplc_set_if_null($event_order, "wplc_event_order");
	
		$max_events = intval($max_events);
		if($max_events == 0) {
			$max_events = -1;
		}
		$advance_days = intval($advance_days);
		if($advance_days == 0) {
			$advance_days = -1;
		}
		if(!is_bool($show_past_events)) {
			$show_past_events = $show_past_events == "true";
		}

		$tbl_name = get_option("wplc_tbl_name");
	
		// Get events from DB
		$whered = false;
		$sql = "SELECT e.id as id,
					e.event_name as event_name,
					e.event_link as event_link,
					e.event_loc as event_loc,
					e.event_desc as event_desc,
					e.event_start_time as event_start_time,
					e.event_end_time as event_end_time";
		
		// Check if the format contains the author variable to decide whether to do the join or not
		$needuserjoin = false;
		if(strpos($event_format, "%AUTHOR%") > -1) {
			$sql .= ", u.display_name as event_author";
			$needuserjoin = true;
		}
		
		$sql .= " FROM $tbl_name e";
		
		if($needuserjoin) {
			$sql .= ", $wpdb->users u";
		}
		
		if(!$show_past_events) {
			$sql .= " WHERE e.event_end_time >= ".time();
			$whered = true;
		}
		if($advance_days > -1) {
			if($whered) {
				$sql .= " AND ";
			}
			else {
				$sql .= " WHERE ";
				$whered = true;
			}
			
			$sql .= "e.event_start_time < ".(time() + ($advance_days * 3600 * 24));
		}
		if($needuserjoin) {
			if($whered) {
				$sql .= " AND ";
			}
			else {
				$sql .= " WHERE ";
				$whered = true;
			}
			
			$sql .= "e.event_author = u.ID";
		}
		
		if($event_order == "asc") {
			$order = "ASC";
		}
		else {
			$order = "DESC";
		}
		
		$sql .= " ORDER BY e.event_start_time ".$order.", e.event_end_time ".$order;
		
		if($max_events > -1)
			$sql .= " LIMIT ".$max_events;
		
		$events = $wpdb->get_results($wpdb->escape($sql), ARRAY_A);
		
		if(!empty($no_events_msg) && count($events) == 0) {
			return $no_events_msg;
		}
	
		// Print events
		if($display_mode == "list")
			$ret = "<ul class='wplc_event_list'>\n";
		elseif($display_mode == "table")
			$ret = "<table class='wplc_table'><tbody>";
		for($i=0; $i<count($events); $i++) {
			// Prepare event string
			$start = date($date_format, $events[$i]['event_start_time']);
			$end = date($date_format, $events[$i]['event_end_time']);
			// Check for same date
			if($hide_same_date) {
				$start_date = date("Ymd", $events[$i]['event_start_time']);
				$end_date = date("Ymd", $events[$i]['event_end_time']);
				
				if($start_date == $end_date) {
					$end = date($date2_time_format, $events[$i]['event_end_time']);
				}
			}
			$cleaned_name = str_replace(" & ", " &amp; ", str_replace('"', "&quot;", stripslashes(stripslashes($events[$i]['event_name']))));
			$cleaned_desc = nl2br(htmlspecialchars_decode(str_replace(" & ", " &amp; ", str_replace('"', "&quot;", stripslashes(stripslashes($events[$i]['event_desc']))))));
			$cleaned_link = htmlspecialchars(stripslashes(stripslashes($events[$i]['event_link'])));
			$target = get_option("wplc_open_links_in_new_window") == "true" ? " target='_blank'" : "";
			$nofollow = get_option("wplc_nofollow_links") == "true" ? " rel='nofollow'" : "";
			$linked_name = empty($cleaned_link) ? $cleaned_name : "<a href='".$cleaned_link."'".$target.$nofollow.">".$cleaned_name."</a>";
			$cleaned_author = str_replace(" & ", " &amp; ", str_replace('"', "&quot;", stripslashes(stripslashes($events[$i]['event_author']))));
		
			if($display_mode == "list") {
				$evt = str_replace("%NAME%", $cleaned_name, $event_format);
				$evt = str_replace("%LINK%", $cleaned_link, $evt);
				$evt = str_replace("%LINKEDNAME%", $linked_name, $evt);
				$evt = str_replace("%LOCATION%", $events[$i]['event_loc'], $evt);
				$evt = str_replace("%DESCRIPTION%", $cleaned_desc, $evt);
				$evt = str_replace("%START%", $start, $evt);
				$evt = str_replace("%END%", $end, $evt);
				$evt = str_replace("%ID%", $events[$i]['id'], $evt);
				$evt = str_replace("%AUTHOR%", $cleaned_author, $evt);
				$ret .= "<li".(($i % 2 == 1) ? " class='wplc_alt'" : "").">".$evt."</li>";
			}
			elseif($display_mode == "table") {
				$ret .= "<tr".(($i % 2 == 1) ? " class='wplc_alt'" : "").">\n\t"
							."<td class='wplc_event_name'>".$linked_name."</td>\n\t"
							."<td class='wplc_event_location'>".$events[$i]['event_loc']."</td>\n\t"
							."<td class='wplc_event_start_time'>".$start."</td>\n\t"
							."<td class='wplc_event_end_time'>".$end."</td>\n"
						."</tr>\n"
						."<tr".(($i % 2 == 1) ? " class='wplc_alt'" : "").">\n\t"
							."<td class='wplc_event_desc' colspan='4'>".$cleaned_desc."</td>\n"
						."</tr>";
			}
		}
		if($display_mode == "list")
			$ret .= "</ul>";
		elseif($display_mode == "table")
			$ret .= "</tbody></table>";
		return $ret;
	}
	
	// Content filter to place a calendar on a post or page
	add_filter("the_content", "wplc_content_filter");
	function wplc_content_filter($content) {
		return str_ireplace("<!--wplistcal-->", wplc_show_events(), $content);
	}
	
	require_once("utility.inc.php");
	require_once("admin.inc.php");
}
?>