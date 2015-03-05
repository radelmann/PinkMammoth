<?php 

function fsb_admin_page() {

	global $fsb_options;
	?>

	<script type="text/javascript">
		//<![CDATA[
			jQuery(document).ready(function()
			{
				// Media Uploader
				window.widgetFormfield = '';

				jQuery('.upload_image_button').live('click', function() {
					window.widgetFormfield = jQuery('.upload_field',jQuery(this).parent());
					tb_show('Choose Image', 'media-upload.php?TB_iframe=true');
					return false;
				});

				window.original_send_to_editor = window.send_to_editor;
				window.send_to_editor = function(html) {
					if (window.widgetFormfield) {
						imgurl = jQuery('img',html).attr('src');
						window.widgetFormfield.val(imgurl);
						tb_remove();
					}
					else {
						window.original_send_to_editor(html);
					}
					window.widgetFormfield = '';
					window.imagefield = false;
					
					jQuery('#fsb_preview_image').attr('src', imgurl); 
				}	
			});
		//]]>   
	  </script>	

	<div class="wrap">
		<div id="fsb-wrap" class="fsb-help">
			<h2>Full Screen Background Image</h2>
			<?php
			if ( ! isset( $_REQUEST['updated'] ) )
				$_REQUEST['updated'] = false;
			?>
			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">

				<?php settings_fields( 'fsb_register_settings' ); ?>
				
				<h4>Choose Your Image</h4>
				
				<p>
					<input id="fsb_settings[image]" name="fsb_settings[image]" type="text" class="upload_field" value="<?php echo $fsb_options['image']; ?>"/>
					<input class="upload_image_button button-secondary" type="button" value="Choose Image"/>
					<label class="description" for="fsb_settings[image]"><?php _e( 'This image will be applied to the background of your website' ); ?></label>
				</p>

				<p>
					<?php if($fsb_options['image']) { ?>
						<img src="<?php echo $fsb_options['image']; ?>" id="fsb_preview_image" style="padding: 3px; border: 1px solid #f0f0f0; max-width: 600px; overflow: hidden;"/>
					<?php } else { ?>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'preview.jpg'; ?>" id="fsb_preview_image" style="padding: 3px; border: 1px solid #f0f0f0; max-width: 600px; overflow: hidden;"/>
					<?php } ?>
				</p>
				
				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
				</p>
								
				
			</form>
		</div><!--end fsb-wrap-->
	</div><!--end wrap-->
	<?php	
	
}
function fsb_init_admin() {
	add_submenu_page( 'themes.php', 'Full Screen Background Image', 'Fullscreen BG Image', 'manage_options', 'full-screen-background', 'fsb_admin_page' );
}
add_action('admin_menu', 'fsb_init_admin');

// register the plugin settings
function fsb_register_settings() {
	register_setting( 'fsb_register_settings', 'fsb_settings' );
}
add_action( 'admin_init', 'fsb_register_settings' );