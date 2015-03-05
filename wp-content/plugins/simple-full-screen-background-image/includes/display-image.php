<?php

function fsb_display_image() {
	global $fsb_options;
	
	if($fsb_options) {
		$image = '<img src="' . $fsb_options['image'] . '" id="fsb_image"/>';
	}
	// output the image
	echo $image;
}
add_action('wp_footer', 'fsb_display_image');