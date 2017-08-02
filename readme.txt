=== Feed Subscriber Stats ===
Contributors: Allan Collins
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=acollins%40paonia%2ecom&item_name=Feed%20Subscriber%20Stats&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: feed,feeds,feedburner,rss,sidebar,stats,widgets,widget,admin,blog,count,dashboard,subscriber,subscribers
Requires at least: 2.5.1
Tested up to: 2.7.1
Stable tag: 2.2

This plugin will show the number of subscribers according to FeedBurner/Google.

== Description ==

Ever wanted to see your FeedBurner stats on the Wordpress Dashboard? Well now you can with this simple plugin. The plugin uses FeedBurner's Awareness API to get your feed subscriber stats directly from FeedBurner. So now you can easily check your subscriber stats from the Wordpress Administration section.

-Backwards compatibility to old FeedBurner for those who have not switched yet.<br/>
-Added compatibility to for Wordpress 2.5.1 and up.<br/>
-Customizable sidebar widget added.<br/>
-Function to add to theme to display subscribers.<br/>


== Installation ==

1. Download the plugin, extract and upload it to your plugins folder on your server.

2. Activate the plugin.

3. Go to your Wordpress Dashboard.

4. It will tell you "Feed Not Found". Hit the "Edit" link and enter your FeedBurner information and hit "Update".

Make sure that the Awareness API is activated on your FeedBurner Account. You can do this by logging in to your FeedBurner/Google account, click on "Publicize",. Then click "Awareness API" and Activate it.



<b>Theme Function:</b>

&lt;?php if (function_exists('fss_subscribers')) { fss_subscribers(); } ?&gt;

The above code will show the subscriber count only.



<b>Sidebar Widget:</b>

If you have a widget ready dynamic sidebar, you can add the FSS Widget. 

Add this code [fss_subscribers] to view the subscriber count.

You can customize how this looks for example:  

There are [fss_subscribers] subscribers to this blog.

WILL DISPLAY:

There are 20 subscribers to this blog.



== Screenshots ==

1. Dashboard view of Feed Subscriber Stats.

