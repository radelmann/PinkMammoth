<?php
/*
  Plugin Name: Facebook Walleria
  Plugin URI: http://zoxion.com/walleria
  Description: This plugins embeds your Facebook Photo , Albums  and Wall into Wordpress.
  Author:  Freeman Chari
  Version: 3.0.8
  Author URI: http://www.zoxion.com
  License: You should have purchased a license from http://codecanyon.net
 */

/**
 * In future when most people migrate to PHP 5.3 we will use autoloading
 */
require_once('Walleria.class.php');
require_once('Classes/WalleriaClassLoader.class.php');
require_once('Widgets/walleria-widgets.php');

$walleria=new Walleria();

// Activation and deactivation hook
register_activation_hook( __FILE__, array($walleria, 'activate')  );
register_deactivation_hook( __FILE__, array($walleria, 'deactivate') );

$shortcodes=new WalleriaShortcodes();
if(is_admin()){
 require_once(WALLERIA_ABSPATH.'Admin/Classes/WalleriaAdmin.class.php');
 new WalleriaAdmin();
} 

 
