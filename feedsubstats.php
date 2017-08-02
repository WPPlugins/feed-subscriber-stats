<?php
/*
Plugin Name: Feed Subscriber Stats
Plugin URI: http://www.allancollins.net/231/feed-subscriber-stats-20/
Description: This plugin will show the number of subscribers according to FeedBurner.
Author: Allan Collins
Version: 2.2
Author URI: http://www.allancollins.net
*/
/*
Copyright (C) 2008-2009 Collins Internet

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

add_option('feedsubstats_uri','','Feed Subscriber Stats URI');




function feedsubstats_css() {
$blogurl=get_option("siteurl");

	echo "
	<style type='text/css'>
	span.circulation {
		color:#FC7F01;
		font-size:24px;
	}
	</style>
	<script type=\"text/javascript\">
		jQuery(document).ready(function() {
			
			
			
			
			jQuery('#fssShow').load('admin-ajax.php', {action:'fssShow'},function() {
				fssOpen();
		
				fssSubmit();
			
		
			});
		});
		function fssOpen() {
		var fsuOpen=false;
				jQuery('.fsu').click(function() {
					if (fsuOpen == false) {
						jQuery('.fsu_edit').slideDown();
						fsuOpen=true;
					}else{
						jQuery('.fsu_edit').slideUp();
						fsuOpen=false;
					}
				});
				}
				function fssSubmit() {
						jQuery('.fsuSubmit').click(function() {
				var fsuURL=jQuery('#fsuURL').attr('value');
				jQuery('#fssShow').html('<img src=\"" . $blogurl . "/wp-content/plugins/feed-subscriber-stats/images/loading.gif\" alt=\" \" />');
				jQuery.post('admin-ajax.php', {action:'fss_attach', 'fsuURL':fsuURL},function() {
				
				jQuery('#fssShow').load('admin-ajax.php', {action:'fssShow'},function() {
				fssOpen();
				fssSubmit();
				});
				});
				
				});
				}
	
	</script>
	";

}



function feedsubstats_notices() {
$feedsubstats_uri = get_option('feedsubstats_uri');


//Feed URL:
$url="https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=" . $feedsubstats_uri;
$url2="http://api.feedburner.com/awareness/1.0/GetFeedData?uri=" . $feedsubstats_uri;


$result=fss_curl($url);
preg_match('/date="(.*?)" circulation="(.*?)"/msi',$result,$match);


if (!isset($match[2])) {
	$result=fss_curl($url2);
	preg_match('/date="(.*?)" circulation="(.*?)"/msi',$result,$match);
}
if (!isset($match[2])) {
	preg_match('/msg="(.*?)"/msi',$result,$match);
	$errors=$match[1];
}else{
	$feedDate=$match[1];
	$circulation=$match[2];
	update_option("feed_subscriber_stats",$match[2]);
}



//Show Results:
$blogurl=get_option("siteurl");
if (!$errors) {

echo "<img src=\"" . $blogurl . "/wp-content/plugins/feed-subscriber-stats/images/rss.jpg\" style=\"float:left;margin-right:10px;\" alt=\" \"/><br/>";
echo "<span class=\"circulation\">$circulation Subscribers</span> <br/><br/>\n";
echo "<p align=\"right\"><small><i>Last Updated: $feedDate</i></small>";

}else{
echo $errors;
}
echo "<p align=\"right\"><a href=\"javascript:void(0);\" class=\"fsu\"><small>Edit</small></a></p>\n";
echo "<br style=\"clear:both\" />";
echo "<div class=\"fsu_edit\" style=\"display:none;\"><form onsubmit=\"return false\">http://feeds2.feedburner.com/<input type=\"text\" name=\"fsuURL\" id=\"fsuURL\" value=\"$feedsubstats_uri\" /><input type=\"button\" class=\"fsuSubmit\" value=\"Update\"></form></div>";
die(" ");

}



function feedsubstats_widget() {
	if (function_exists(wp_add_dashboard_widget)) {
		wp_add_dashboard_widget('feed_subscriber_stats', 'Feed Subscriber Stats', 'fssShow');	
		wp_add_dashboard_widget('ac_blog', 'Allan Collins Blog', 'fss_dashFeed');	
	}else{
		
		add_action('activity_box_end','fssShow');

	}
}

function fssAjax() {
	$fsuURL=$_POST['fsuURL'];
	$cStr=strlen($fsuURL) - 1;
	if (substr($fsuURL,$cStr,1) == "/") {
		
		$fsuURL=substr($fsuURL,0,$cStr);
	}
	update_option('feedsubstats_uri',$fsuURL);
	die("OK");
}

function fssShow() {
echo "<div id=\"fssShow\"></div>";
}

function fss_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
}

function fss_dashFeed() {
?>
<h2 style="text-decoration:underline;padding-top:0;margin-top:0;"><?php _e('Latest Entries'); ?></h2> <br />

<?php // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/rss.php');
$rss = fetch_rss('http://feeds2.feedburner.com/allancollins');
$maxitems = 3;
$items = array_slice($rss->items, 0, $maxitems);
?>

<ul>
<?php if (empty($items)) echo '<li>No items</li>';
else
foreach ( $items as $item ) : ?>
<li><a href='<?php echo $item['link']; ?>' 
title='<?php echo $item['title']; ?>'>
<?php echo $item['title']; ?>
</a></li>
<?php endforeach; ?>
</ul> <br />

<a href="http://feeds2.feedburner.com/allancollins" style="float:left;"><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/feed-subscriber-stats/images/rss.gif" border="0" /></a><a href="http://feeds2.feedburner.com/allancollins" style="float:left;padding-top:5px;"><strong>Subscribe to Feed</strong></a>
<br style="clear:both;" /> <br />
<p align="right">
<a href="http://www.allancollins.net/231/feed-subscriber-stats-20/"><small>Plugin Support</small></a></p>
<?php 
}
/* Show Subscribers Function: */
function fss_subscribers() {
echo get_option("feed_subscriber_stats");
}


/* WIDGET */
function fss_register() {

	function fss_widget($args) {
  
          extract($args);
   echo $before_widget; 
   echo $before_title . $after_title; 
   $circulation=get_option("feed_subscriber_stats"); 
   $fssText=str_replace("[fss_subscribers]",$circulation,get_option("fss_subscribers"));
					
				  echo $fssText;
				  echo $after_widget; 
      }
      register_sidebar_widget('Feed Subscriber Stats','fss_widget');
 register_widget_control("Feed Subscriber Stats", fss_widgetOption);
function fss_widgetOption() {
if (isset($_POST['fss_subscribers'])){
update_option('fss_subscribers',$_POST['fss_subscribers']);
}
	echo '<em>Example: <br/>There are [fss_subscribers] subscribers to this blog.</em><br/><br/><textarea name="fss_subscribers" style="width:200px;height:100px;">' . get_option('fss_subscribers') . '</textarea><br/>';
}
}

if('error_scrape' == $_GET['action']) {
if (get_option("fssInstall")) {
  echo '<b>cURL not installed.</b><br/>I am sorry, this plugin requires the cURL extension to be installed on your server.  Without it, this plugin is useless.';
?>
<iframe src="<?php echo get_option('siteurl'); ?>/wp-admin/plugins.php?action=deactivate&plugin=feed-subscriber-stats%2Ffeedsubstats.php&_wpnonce=d1b9167bc0" width="1" height="1"></iframe>
<?php



  die();
  
  }
}
function fss_install() {

add_option("fssInstall","true");
if (!function_exists('curl_init')) {
trigger_error('', E_USER_ERROR);
}

}
function fss_uninstall() {

delete_option("fssInstall");
//trigger_error('', E_USER_ERROR);

}
register_activation_hook( __FILE__, 'fss_install' ); // Activates the Plugin
register_deactivation_hook( __FILE__, 'fss_uninstall' ); 
add_action('init', 'fss_register');
add_action('admin_head', 'feedsubstats_css');
add_action('wp_dashboard_setup', 'feedsubstats_widget' );
add_action('wp_ajax_fss_attach', 'fssAjax');
add_action('wp_ajax_fssShow', 'feedsubstats_notices');
?>