=== VerticalResponse Newsletter Widget ===
Contributors: katzwebdesign
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=zackkatz%40gmail%2ecom&item_name=Vertical%20Response%20Widget&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: vertical response,verticalresponse, newsletter widget, email newsletter form, newsletter form, newsletter signup, email widget, email marketing, newsletter, form, signup
Requires at least: 2.8
Tested up to: 3.3.2
Stable tag: trunk

Add a VerticalResponse signup form to your sidebar. Lots of configuration options. Now with custom colors and shortcode support!

== Description ==

> This plugin requires a VerticalResponse account. <strong><a href="http://www.verticalresponse.com/wordpress-newsletter-plugin">Sign up for VerticalResponse today</a></strong>.

<h3>Add a VerticalResponse signup form to your sidebar without touching code.</h3> 

This plugin includes many configuration options:

* __Custom widget title__ (if empty, shows no title)
* __Define your own Button Text__ (Subscribe, Submit, Add Me!, Go, etc.)
* __Show an intro paragraph__ and define your own wrapping tag (defaults to a paragraph tag). 
* __Choose from two styles:__
  1. Default VerticalResponse style (gray)
  2. Choose your own colors
  3. No style (make your own using CSS)
* __Choose from three types of forms:__
  1. Full Name, Email, Subscribe button
  2. Email, Subscribe button
  3. Subscribe button only
  
The plugin includes a full-featured `[verticalresponse]` shortcode. <a href="http://wordpress.org/extend/plugins/vertical-response-newsletter-widget/faq/" rel="nofollow">Learn how to configure the shortcode</a>.

== Screenshots ==

1. How the widget appears in the Widgets panel

== Installation ==

Follow the following steps to install this plugin.

1. Download plugin to the `/wp-content/plugins/` folder
1. Upload the plugin to your web host
1. Activate the plugin through the Plugins page (`wp-admin/plugins.php`)
1. Add the widget to your sidebar on the Widgets page (`wp-admin/widgets.php`)
1. Configure the widget and save the settings

You can also embed in your website by configuring above, then removing the widget from your sidebar. Then:

* Add `<?php widget_vr(); ?>` in your template code
* Write `[verticalresponse]` in your post's or page's content.

== Frequently Asked Questions ==

= How do I use the shortcode? =

Here's an example shortcode (Version 1.6+):

`
[verticalresponse title="Sign Up Here" bg_color="#000000" label_color:"#FFFFFF" border_color="333333" border_width="5" email_input_size=15]
`

You can overwrite the plugin settings in the shortcode using the following attributes:

* `code` (default: '')
* `title` (default: 'Sign up for our Newsletter')
* `email_default_value` (default: 'Enter your email address')
* `div_class` (default: 'widget_vr')
* `form_class` (default: 'vr_form')
* `form_id` (default: ($number_of_vr_widgets === 1) ? 'vr_form' : 'vr_form_'.$number_of_vr_widgets)
* `showname` (default: 'full')
* `bg_color` (default: '#dddddd')
* `border_color` (default: '#000000')
* `font_color` (default: '#333333')
* `label_color` (default: '#000000')
* `button_bg_color` (default: '#333333')
* `button_border_color` (default: '#999999')
* `button_font_color` (default: '#000000')
* `border_width` (default: 1)
* `preface` (default: '')
* `wrap` (default: 'p')
* `button` (default: 'Subscribe')
* `defaultstyle` (default: 'yes')
* `credit` (default: 'yes')
* `show_vr_code` (default: 'yes')
* `email_input_size` (default: 20)
* `name_input_size` (default: 15)
* `last_name_label` (default: 'Last Name:')
* `first_name_label` (default: 'First Name:')
* `email_label` (default: 'Email:')
* `before_widget` (default: '&lt;div class="widget [div_class]"&gt;')
* `after_widget` (default: '&lt;/div&gt;')
* `before_title` (default: '&lt;h4 class="widgettitle"&gt;')
* `after_title` (default: '&lt;/h4&gt;')

= How do I use the new `add_filters()` functionality? (Added 1.1) =
If you want to change some code in the widget, you can use the WordPress `add_filter()` function to achieve this.

You can add code to your theme's `functions.php` file that will modify the widget or shortcode output. Here's an example:
<pre>
function my_example_function($widget) { 
	// The $widget variable is the output of the widget
	// This will replace 'this word' with 'that word' in the widget output.
	$widget = str_replace('this word', 'that word', $widget);
	// Make sure to return the $widget variable, or it won't work!
	return $widget;
}
add_filter('vr_widget_form', 'my_example_function');
</pre>

= What is the license? =

This plugin is released under a GPL license.

== Changelog == 

= 1.6 =
* Added shortcode configuration options. <a href="http://wordpress.org/extend/plugins/vertical-response-newsletter-widget/faq/" rel="nofollow">Learn how to configure the shortcode</a>.
* Improved code structure
* Switched away from some depreciated functions
* Added `vr_widget_defaults` and `vr_widget_args` filters to modify default form configuration

= 1.5.1 = 
* Fixed `[verticalresponse]` shortcode bug where form would be shown at the beginning of content rather than embedded inside it (as reported in <a href="http://wordpress.org/support/topic/438919" rel="nofollow">issue #438919</a>)

= 1.5 = 
* Dropped a decimal place in the version numbers
* If you want to modify widget or shortcode output, there's now an `add_filters` method to do so.
* Improved widget controls configuration

= .1482 = 
* Fixed `. />` code validation errors on line 183 and 193. Thanks to [Doug Ng](http://design-ng.com) for pointing this out

= .1481 = 
* Added GPL notice

= .148 = 
* Added shortcode

= .147 = 
* Added color options for backgrounds (form, button), borders (form, button), text (main, labels, button)
* Updated versions

= .146 = 
* Fixed error on line 146
* Added give credit option

= .145 = 
* Minor update to fix fatal error, changing <? to <?php
* Added id's and classes to improve CSS customization capabilities

= .144 = 
* Removed white space at end of widget to possibly fix fatal error

= .143 = 
* Updated stripslashes() to better handle apostrophes in fields

= .142	= 
* Fixed tag closure order error. Thanks patrickrileydesign.com!

= .141	= 
* Updated Plugin URI link to go to widget's page

= .14 = 
* Fixed bug that showed the VerticalResponse form instead of saving the widget options or removing the widget.

== Upgrade Notice ==

= 1.6 =
* Now requires WordPress 2.8 or better
* Added shortcode configuration options. <a href="http://wordpress.org/extend/plugins/vertical-response-newsletter-widget/faq/" rel="nofollow">Learn how to configure the shortcode</a>.
* Improved code structure, which may fix fatal errors on strangely-coded themes...

= 1.5.1 = 
* Fixed `[verticalresponse]` shortcode bug where form would be shown at the beginning of content rather than embedded inside it (as reported in <a href="http://wordpress.org/support/topic/438919" rel="nofollow">issue #438919</a>)

= 1.5 = 
* If you want to modify widget or shortcode output, there's now an `add_filters` method to do so.
* Improved widget controls configuration

= .1482 = 
* Fixed XHTML validation errors caused by lines 183 and 193. No major functionality improvements.
