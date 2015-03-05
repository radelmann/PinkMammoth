<?php
/**
 * Walleria Admin
 *
 * @author fchari
 */
class WalleriaAdmin {
    
    public function __construct() {
       add_action('admin_menu', array (&$this, 'add_menu') );
       add_action('admin_enqueue_scripts',array($this,'admin_styles'));
       add_action('admin_enqueue_scripts',array($this,'admin_scripts'));
    }
    
    function add_menu(){
        add_menu_page('Walleria', 'Walleria', 'activate_plugins','walleria', array($this,'show_menu'),path_join(WALLERIA_URL, 'Admin/images/icon-16.png'));
    }
    
    function show_menu(){
        include_once(path_join(WALLERIA_ABSPATH, 'Admin/Templates/admin.php'));
    }
    
    public function admin_styles(){
    wp_deregister_style('jquery-ui');
    wp_register_style('jquery-ui', WALLERIA_URL . 'Admin/css/jquery-ui.css');
    wp_enqueue_style('jquery-ui');
    }
    public function admin_scripts(){
    wp_deregister_script('walleria-admin');
    wp_register_script('walleria-admin', WALLERIA_URL . 'Admin/js/admin.js',array('jquery','jquery-ui-tabs'));
    wp_enqueue_script('walleria-admin');
    }
}

?>
