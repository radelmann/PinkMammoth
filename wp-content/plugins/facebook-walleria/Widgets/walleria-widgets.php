<?php

class FWPG_Status extends WP_Widget {

    // constructor	 
    function __construct() {
        parent::WP_Widget('walleria-status', __('Facebook Walleria Posts', 'facebook-walleria'), array('description' => __('This will show your Facebook Statuses on a side bar', 'facebook-walleria')));
    }

    // display widget	 
    function widget($args, $instance) {

        if (sizeof($args) > 0) {
            extract($args, EXTR_SKIP);
            $fws_username = ($instance['fws_username'] != '') ? $instance['fws_username'] : 'codebyfreeman';
            $fws_title = ($instance['fws_title'] != '') ? $instance['fws_title'] : '';
            $fws_title = apply_filters('widget_title', $fws_title);
            $fws_fblink = ($instance['fws_fblink'] != '') ? $instance['fws_fblink'] : '';
            $fws_number = ($instance['fws_number'] != '') ? $instance['fws_number'] : '8';
            $fws_border = ($instance['fws_border'] != '') ? $instance['fws_border'] : '#94a3c4';
            $fws_text_color = ($instance['fws_text_color'] != '') ? $instance['fws_text_color'] : '#3b5998';
            $fws_links = ($instance['fws_links'] != '') ? $instance['fws_links'] : '#3B5998';
            $fws_body_bg = ($instance['fws_body_bg'] != '') ? $instance['fws_body_bg'] : '#fff';
            $fws_count = ($instance['fws_count'] != '') ? $instance['fws_count'] : '250px';
            $fws_height = ($instance['fws_height'] != '') ? $instance['fws_height'] : '400px';
            $fws_width = ($instance['fws_width'] != '') ? $instance['fws_width'] : '250px';
        }

        $tokenOpts = get_option('fwpg_accessToken');
        $token = $tokenOpts['access_token'];
        $options['limit'] = $fws_number;
        $options['type'] = 'posts';
        $feed = new WalleriaWall($fws_username, $options);
        $template = empty($template)? 'wall' : $template;
        $paging = isset($feedpaging->next) ? parse_url($feed->paging->next, PHP_URL_QUERY) : "";
        $arguments = array('feed' => $feed->stream, 'args' => $args, 'instance' => $instance, 'paging' => preg_replace('/access(.*&|.*)/', '', $paging), 'wallid' => $fws_username, 'settings' => $feed->settings, 'objecttype' => $feed->type);
        $output = Walleria::capture('status-widget', $arguments);
        echo $output;
    }

    // update/save function
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['fws_username'] = strip_tags($new_instance['fws_username']);
        $instance['fws_title'] = strip_tags($new_instance['fws_title']);
        $instance['fws_fblink'] = strip_tags($new_instance['fws_fblink']);
        $instance['fws_number'] = strip_tags($new_instance['fws_number']);
        $instance['fws_border'] = strip_tags($new_instance['fws_border']);
        $instance['fws_text_color'] = strip_tags($new_instance['fws_text_color']);
        $instance['fws_links'] = strip_tags($new_instance['fws_links']);
        $instance['fws_body_bg'] = strip_tags($new_instance['fws_body_bg']);
        $instance['fws_count'] = strip_tags($new_instance['fws_count']);
        $instance['fws_height'] = strip_tags($new_instance['fws_height']);
        $instance['fws_width'] = strip_tags($new_instance['fws_width']);
        //initialisation



        update_option('walleria-status', $instance);
        return $instance;
    }

    // admin control form
    function form($instance) {

        $instance = wp_parse_args((array) $instance, array('fws_title' => '', 'fws_count' => '250px', 'fws_height' => '400px', 'fws_username' => '', 'fws_number' => '', 'fws_border' => '', 'fws_text_color' => '', 'fws_fblink' => '', 'fws_links' => '', 'fws_body_bg' => '', 'fws_follow_image' => ''));
        $fws_username = strip_tags($instance['fws_username']);
        $fws_fblink = $instance['fws_fblink'] != "" ? strip_tags($instance['fws_fblink']) : "Yes";
        $fws_title = $instance['fws_title'] != "" ? strip_tags($instance['fws_title']) : "";
        $fws_number = $instance['fws_number'] != "" ? strip_tags($instance['fws_number']) : 5;
        $fws_border = $instance['fws_border'] != "" ? strip_tags($instance['fws_border']) : 'none';
        $fws_text_color = $instance['fws_text_color'] != "" ? strip_tags($instance['fws_text_color']) : '#333';
        $fws_links = ($instance['fws_links'] != '') ? strip_tags($instance['fws_links']) : '#3B5998';
        $fws_body_bg = $instance['fws_body_bg'] != "" ? strip_tags($instance['fws_body_bg']) : "transparent";
        $fws_width = $instance['fws_width'] != "" ? strip_tags($instance['fws_width']) : '250px';
        $fws_height = $instance['fws_height'] != "" ? strip_tags($instance['fws_height']) : '400px';
        ?>
        <p><label for="<?php echo $this->get_field_id('fws_username'); ?>">Facebook ID: <input class="widefat" id="<?php echo $this->get_field_id('fws_username'); ?>" name="<?php echo $this->get_field_name('fws_username'); ?>" type="text" value="<?php echo esc_attr($fws_username); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_title'); ?>">Title : <input class="widefat" id="<?php echo $this->get_field_id('fws_title'); ?>" name="<?php echo $this->get_field_name('fws_title'); ?>" type="text" value="<?php echo esc_attr($fws_title); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_fblink'); ?>">Show Facebook Link : <select class="widefat" id="<?php echo $this->get_field_id('fws_fblink'); ?>" name="<?php echo $this->get_field_name('fws_fblink'); ?>" type="text">
                    <option <?php if (esc_attr($fws_fblink) == 'Yes') {
            echo "selected";
        } ?> >Yes</option>
                    <option <?php if (esc_attr($fws_fblink) == 'No') {
            echo "selected";
        } ?> >No</option>
                </select></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_number'); ?>">Number of Statuses: <input class="widefat" id="<?php echo $this->get_field_id('fws_number'); ?>" name="<?php echo $this->get_field_name('fws_number'); ?>" type="text" value="<?php echo esc_attr($fws_number); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_border'); ?>">Border (e.g. 1px solid #94a3c4): <input class="widefat" id="<?php echo $this->get_field_id('fws_border'); ?>" name="<?php echo $this->get_field_name('fws_border'); ?>" type="text" value="<?php echo esc_attr($fws_border); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_text_color'); ?>">Text Color (e.g. #3b5998): <input class="widefat" id="<?php echo $this->get_field_id('fws_text_color'); ?>" name="<?php echo $this->get_field_name('fws_text_color'); ?>" type="text" value="<?php echo esc_attr($fws_text_color); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_links'); ?>">Name Caption Color (e.g. #eceff5): <input class="widefat" id="<?php echo $this->get_field_id('fws_links'); ?>" name="<?php echo $this->get_field_name('fws_links'); ?>" type="text" value="<?php echo esc_attr($fws_links); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_body_bg'); ?>">Background Color (e.g. #ffffff): <input class="widefat" id="<?php echo $this->get_field_id('fws_body_bg'); ?>" name="<?php echo $this->get_field_name('fws_body_bg'); ?>" type="text" value="<?php echo esc_attr($fws_body_bg); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_width'); ?>">Width (e.g. 250px): <input class="widefat" id="<?php echo $this->get_field_id('fws_width'); ?>" name="<?php echo $this->get_field_name('fws_width'); ?>" type="text" value="<?php echo esc_attr($fws_width); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_height'); ?>">Height (e.g. 400px): <input class="widefat" id="<?php echo $this->get_field_id('fws_height'); ?>" name="<?php echo $this->get_field_name('fws_height'); ?>" type="text" value="<?php echo esc_attr($fws_height); ?>" /></label></p>

        <?php
    }

}

class FWPG_Photos extends WP_Widget {

    // constructor	 
    function __construct() {
        parent::WP_Widget('walleria-photos', __('Facebook Walleria Photo Album', 'facebook-walleria'), array('description' => __('This will show a chosen Facebook Album on a side bar', 'facebook-walleria')));
    }

    // display widget	 
    function widget($args, $instance) {

        if (sizeof($args) > 0) {
            extract($args, EXTR_SKIP);
            $fws_title = ($instance['fws_title'] != '') ? $instance['fws_title'] : '';
            $fws_title = apply_filters('widget_title', $fws_title);
            $fws_albumid = ($instance['fws_album'] != '') ? $instance['fws_album'] : '';
            $fws_fblink = ($instance['fws_fblink'] != '') ? $instance['fws_fblink'] : '';
            $fws_number = ($instance['fws_number'] != '') ? $instance['fws_number'] : '2';
            $fws_border = ($instance['fws_border'] != '') ? $instance['fws_border'] : '#94a3c4';
            $fws_text_color = ($instance['fws_text_color'] != '') ? $instance['fws_text_color'] : '#3b5998';
            $fws_links = ($instance['fws_links'] != '') ? $instance['fws_links'] : '#3B5998';
            $fws_body_bg = ($instance['fws_body_bg'] != '') ? $instance['fws_body_bg'] : '#fff';
            $fws_height = ($instance['fws_height'] != '') ? $instance['fws_height'] : '400px';
            $fws_width = ($instance['fws_width'] != '') ? $instance['fws_width'] : '250px';
            $fws_size = ($instance['fws_size'] != '') ? $instance['fws_size'] : 'medium';
            $fws_count = ($instance['fws_count'] != '') ? $instance['fws_count'] : '20';
        }
       
        $options['limit'] = $fws_count;
        $album = new WalleriaAlbum($fws_albumid, $options);
        $photos = $album->photos;

        /**
         * 
         */
        $sizes = array(
            'large' => array('code' => 'p206x206', 'position' => 5, 'classtext' => 'Large'),
            'medium' => array('code' => 'p160x160', 'position' => 6, 'classtext' => 'Medium'),
            'small' => array('code' => 'p120x120', 'position' => 7, 'classtext' => 'Small')
        );
        $arguments = array_merge($sizes[$fws_size], array('id' => $fws_albumid, 'photos' => $photos, 'paging' => $album->paging,'args' => $args, 'instance' => $instance,  'settings' => $album->settings));
        $template = empty($template)? 'photos-widget' : $template;
        $output = Walleria::capture($template, $arguments);
        echo $output;
    }

    // update/save function
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['fws_album'] = strip_tags($new_instance['fws_album']);
        $instance['fws_title'] = strip_tags($new_instance['fws_title']);
        $instance['fws_fblink'] = strip_tags($new_instance['fws_fblink']);
        $instance['fws_number'] = strip_tags($new_instance['fws_number']);
        $instance['fws_border'] = strip_tags($new_instance['fws_border']);
        $instance['fws_text_color'] = strip_tags($new_instance['fws_text_color']);
        $instance['fws_links'] = strip_tags($new_instance['fws_links']);
        $instance['fws_body_bg'] = strip_tags($new_instance['fws_body_bg']);
        $instance['fws_count'] = strip_tags($new_instance['fws_count']);
        $instance['fws_height'] = strip_tags($new_instance['fws_height']);
        $instance['fws_size'] = strip_tags($new_instance['fws_size']);
        $instance['fws_width'] = strip_tags($new_instance['fws_width']);
        $instance['fws_count'] = strip_tags($new_instance['fws_count']);
        //initialisation



        update_option('walleria-status', $instance);
        return $instance;
    }

    // admin control form
    function form($instance) {

        $instance = wp_parse_args((array) $instance, array('fws_album' => '',"fws_title"=>'','fws_size'=>'','fws_width'=>'','fws_count'=>'', 'fws_fblink'=>"",'fws_number' => '', 'fws_border' => '', 'fws_text_color' => '', 'fws_links' => '', 'fws_body_bg' => '', 'fws_follow_image' => ''));
        $fws_albumid = strip_tags($instance['fws_album']);
        $fws_fblink = $instance['fws_fblink'] != "" ? strip_tags($instance['fws_fblink']) : "Yes";
        $fws_title = $instance['fws_title'] != "" ? strip_tags($instance['fws_title']) : "";
        $fws_number = $instance['fws_number'] != "" ? strip_tags($instance['fws_number']) : 2;
        $fws_border = $instance['fws_border'] != "" ? strip_tags($instance['fws_border']) : 'none';
        $fws_text_color = $instance['fws_text_color'] != "" ? strip_tags($instance['fws_text_color']) : '#333';
        $fws_links = ($instance['fws_links'] != '') ? strip_tags($instance['fws_links']) : '#3B5998';
        $fws_body_bg = $instance['fws_body_bg'] != "" ? strip_tags($instance['fws_body_bg']) : "transparent";
        $fws_size = $instance['fws_size'] != "" ? strip_tags($instance['fws_size']) : 'medium';
        $fws_count = $instance['fws_count'] != "" ? strip_tags($instance['fws_count']) : '20';
        $fws_width = $instance['fws_width'] != "" ? strip_tags($instance['fws_width']) : '250px';
        ?>
        <p><label for="<?php echo $this->get_field_id('fws_title'); ?>">Title : <input class="widefat" id="<?php echo $this->get_field_id('fws_title'); ?>" name="<?php echo $this->get_field_name('fws_title'); ?>" type="text" value="<?php echo esc_attr($fws_title); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_fblink'); ?>">Show Facebook Link : <select class="widefat" id="<?php echo $this->get_field_id('fws_fblink'); ?>" name="<?php echo $this->get_field_name('fws_fblink'); ?>" type="text">
                    <option <?php if (esc_attr($fws_fblink) == 'Yes') {
            echo "selected";
        } ?> >Yes</option>
                    <option <?php if (esc_attr($fws_fblink) == 'No') {
            echo "selected";
        } ?> >No</option>
                </select></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_album'); ?>">Album ID: <input class="widefat" id="<?php echo $this->get_field_id('fws_album'); ?>" name="<?php echo $this->get_field_name('fws_album'); ?>" type="text" value="<?php echo esc_attr($fws_albumid); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_number'); ?>">Number of Photos Shown: <input class="widefat" id="<?php echo $this->get_field_id('fws_number'); ?>" name="<?php echo $this->get_field_name('fws_number'); ?>" type="text" value="<?php echo esc_attr($fws_number); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_border'); ?>">Border  (e.g. 1px solid #94a3c4): <input class="widefat" id="<?php echo $this->get_field_id('fws_border'); ?>" name="<?php echo $this->get_field_name('fws_border'); ?>" type="text" value="<?php echo esc_attr($fws_border); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_text_color'); ?>">Text Color (e.g. #3b5998): <input class="widefat" id="<?php echo $this->get_field_id('fws_text_color'); ?>" name="<?php echo $this->get_field_name('fws_text_color'); ?>" type="text" value="<?php echo esc_attr($fws_text_color); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_links'); ?>">Name Caption Color (e.g. #eceff5): <input class="widefat" id="<?php echo $this->get_field_id('fws_links'); ?>" name="<?php echo $this->get_field_name('fws_links'); ?>" type="text" value="<?php echo esc_attr($fws_links); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_body_bg'); ?>">Background Color (e.g. #ffffff): <input class="widefat" id="<?php echo $this->get_field_id('fws_body_bg'); ?>" name="<?php echo $this->get_field_name('fws_body_bg'); ?>" type="text" value="<?php echo esc_attr($fws_body_bg); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_width'); ?>">Width (e.g. 250px): <input class="widefat" id="<?php echo $this->get_field_id('fws_width'); ?>" name="<?php echo $this->get_field_name('fws_width'); ?>" type="text" value="<?php echo esc_attr($fws_width); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_size'); ?>">Thumbnail Size(e.g. 250px): <select class="widefat" id="<?php echo $this->get_field_id('fws_size'); ?>" name="<?php echo $this->get_field_name('fws_size'); ?>" type="text"  >
                    <option <?php if (esc_attr($fws_size) == 'large') {
            echo "selected";
        } ?> value="large">Large</option>
                    <option <?php if (esc_attr($fws_size) == 'medium') {
            echo "selected";
        } ?>  value="medium">Medium</option>
                    <option <?php if (esc_attr($fws_size) == 'small') {
            echo "selected";
        } ?> value="small">Small</option>
                </select></label></p>
        <p><label for="<?php echo $this->get_field_id('fws_count'); ?>"> Total Photos to Scroll(e.g. 20): <input class="widefat" id="<?php echo $this->get_field_id('fws_count'); ?>" name="<?php echo $this->get_field_name('fws_count'); ?>" type="text" value="<?php echo esc_attr($fws_count); ?>" /></label></p>


        <?php
    }

}

add_action('widgets_init', create_function('', 'register_widget("FWPG_Status");'));

add_action('widgets_init', create_function('', 'register_widget("FWPG_Photos");'));
?>
