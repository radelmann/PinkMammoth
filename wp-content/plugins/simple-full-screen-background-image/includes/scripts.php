<?php

function fsb_load_css() {
	wp_enqueue_style('fsb-image', plugin_dir_url( __FILE__ ) . 'fullscreen-image.css');
}
add_action('init', 'fsb_load_css');

function fsb_load_admin_scripts() {
	wp_enqueue_script( 'media-upload ');
	wp_enqueue_script( 'thickbox' );
}
function fsb_load_admin_styles() {
	wp_enqueue_style( 'thickbox' );
}
if(isset($_GET['page']) && $_GET['page'] == 'full-screen-background') {
	add_action('admin_print_scripts', 'fsb_load_admin_scripts');
	add_action('admin_print_styles', 'fsb_load_admin_styles');
}