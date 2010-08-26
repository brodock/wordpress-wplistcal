<?php
/*
WPListCal Utility functions

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
function wplc_set_if_null(&$var, $option_id) {
	if (is_null($var))
		$var = get_option($option_id);
}

function wplc_br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

add_action('admin_print_scripts', 'wplc_js_admin_header');
function wplc_js_admin_header() {
	?>
	<script type="text/javascript" charset="utf-8">
	//<![CDATA[
	function wplcDeleteEvent(id, msg) {
		if(confirm(msg)) {

			var data = {
				action: 'wplc_delete_event',
				id: id
			};

			jQuery.post(ajaxurl, data, function(response) {
				if (response == '0') {
					var row = document.getElementById('event-'+id);
					if(row != null && row.parentNode != null)
						row.parentNode.removeChild(row);
				} else {
					alert('<?php _e('AJAX error in deleting event'); ?>');
				}
			});

			return true;
		}
	}
	
	function wplc_changeDisabled(id, disabled) {
		var elm = document.getElementById(id);
		if(elm != null && elm.disabled != disabled)
			elm.disabled = disabled;
	}
	
	var fieldsDirty = false;
	var editable = false;
	function wplc_matchValue(myid, matchid, isselect) {
		if(fieldsDirty)
			return;
			
		var me = document.getElementById(myid);
		var you = document.getElementById(matchid);
		
		if(isselect) {
			you.selectedIndex = me.selectedIndex;
		}
		else {
			you.value = me.value;
		}
	}
	
	function wplc_doallday(checked) {
		var visibility = checked ? "hidden" : "visible";
		
		var starttime = document.getElementById("start-time-cont");
		var endtime = document.getElementById("end-time-cont");
		
		if(starttime && typeof(starttime) != 'undefined') {
			starttime.style.visibility = visibility;
		}
		if(endtime && typeof(endtime) != 'undefined') {
			endtime.style.visibility = visibility;
		}
	}
	//]]>
	</script>
	<?php
}

add_action("admin_head", "wplc_admin_css");
function wplc_admin_css() {
	wp_enqueue_style( 'thickbox' );
	?>
	
	<style type="text/css" media="screen">
		.wplc_eventformfield {
			margin: 10px 8px 20px 20px;
			padding: 0px;
			border-color: rgb(235, 235, 235);
		}
		.wplc_date_label {
			float:left;
			display:block;
			width:50px;
			font-weight:bold;
			padding-top:7px;
		}
		.wplc_date_body {
			float:left;
			clear:right;
		}
		#link, #title-noformat, #location {
			margin: 1px;
			padding: 0;
			border: 0;
			width: 100%;
			font-size: 1.7em;
			outline: none;
		}
		#linkwrap, #titlewrap-noformat, #locwrap {
			border: 1px solid rgb(204, 204, 204);
			padding: 2px 3px;
		}
		.wplc_delete:hover {
			background-color: red;
			border-bottom: 1px solid red;
			color: white;
		}
		.wplc_link {
			border-bottom:1px solid;
			padding:1px 2px;
			text-decoration:none;
		}
		.wplc_baseline {
			vertical-align:baseline;
		}
		.wplc_plaincursor {
			cursor:default !important;
		}
	</style>
	<?php
}

function wplc_is_wplc_page() {
	global $wplc_plugin;

    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
	switch($page) {
		case $wplc_plugin:
		case 'wplc-edit':
		case 'wplc-options':
		case 'wplc-import':
		case 'wplc-export':
		case 'wplc-delete-event':
		case 'wplc_cleanup':
			return true;
		default:
			return false;
	}
}

function wplc_dbg_print_array(&$array) {
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

// Returns the server time converted to the wordpress timezone time
function wplc_time() {
	$time = time();
	$gmt_time = $time - intval(date('Z', $time));
	$wp_offset = get_option("gmt_offset");
	return $gmt_time + ($wp_offset * 3600);
}

class FormatToken {
	var $string;
	var $dependent;
	
	function FormatToken($_string, $_dependent=null) {
		$this->string = $_string;
		$this->dependent = $_dependent;
	}
}
?>
