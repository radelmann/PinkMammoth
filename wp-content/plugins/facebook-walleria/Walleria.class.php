<?php

/**
 * Core Walleria class
 *
 * @author fchari
 */
class Walleria {

    public $plugin_name = 'facebook-walleria';
    public $version = '3.0.8';
    public $options;
    public $token;
    public $token_expiry;

    public function __construct() {

        add_action('admin_init', array($this, 'admin_init'));
        $this->options = get_option('walleria');

        $this->define_constants();
        /**
         * Update checks
         */
        add_filter('pre_set_site_transient_update_plugins', array($this, 'update'));
        /**
         * Plugin screen
         */
        add_filter('plugins_api', array($this, 'pluginApiCall'), 10, 3);
        /**
         * Load styles
         */
        add_action('init', array($this, 'loadStyles'));
        /**
         * Load scripts
         */
        add_action('init', array($this, 'loadJS'));

        /**
         * 
         */
        add_action('plugins_loaded', array($this, 'loadTextDomain'));
        /**
         * set up ajax calls
         */
        $this->ajaxHooks();
    }

    public function activate() {
        global $wp_version;

        if (function_exists('is_multisite') && is_multisite()) {
            // check if it is a network activation - if so, run the activation function for each blog id
            if ($networkwide) {
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    /**
                     * Check version
                     */
                    if (version_compare(PHP_VERSION, '5.2.0', '<')) {
                        deactivate_plugins($this->plugin_name); // Deactivate ourself
                        wp_die("This plugin requires PHP 5.2 or higher.");
                        return;
                    }
                    /**
                     * Check WordPress Version
                     */
                    if ($wp_version < 3.2) {
                        deactivate_plugins($this->plugin_name); // Deactivate ourself
                        wp_die("This plugin requires WordPress 3.2 or higher.");
                        return;
                    }
                    /**
                     * Save default options
                     */
                    $this->default_options();
                }
                switch_to_blog($old_blog);
                return;
            }
        } else {
            /**
             * Save default options
             */
            $this->default_options();
        }
    }

    /**
     * admin init function
     * @return void
     * 
     */
    function admin_init() {
        register_setting('fwpg-options', 'walleria');
        register_setting('fwpg-new-options', 'fwpg_accessToken');
    }

    /**
     * Uninstall Walleria
     */
    public function deactivate() {
        if (function_exists('is_multisite') && is_multisite()) {
            // check if it is a network activation - if so, run the activation function for each blog id
            if ($networkwide) {
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    $opts = get_option('walleria');

                    if ($opts['fwpg_uninstall']) {
                        delete_option('walleria');
                        delete_option('fwpg_accessToken');
                    }
                }
                switch_to_blog($old_blog);
                return;
            }
        } else {

            if ($this->options['fwpg_uninstall']) {
                delete_option('walleria');
                delete_option('fwpg_accessToken');
            }
        }
    }

    public function deleteTransients() {
        
    }

    /**
     * Embed a Facebook album
     * @param type $albumId
     * @param type $limit
     * @param type $template
     * @return type
     */
    public static function embedAlbum($albumId, $limit, $size = 'large', $noscroll = false, $options = array(), $template = "") {
        $options['limit'] = $limit;
        $album = new WalleriaAlbum($albumId, $options);
        $photos = $album->photos;
        /**
         * 
         */
        $sizes = array(
            'large' => array('code' => 'p206x206', 'position' => 5, 'classtext' => 'Large'),
            'medium' => array('code' => 'p160x160', 'position' => 6, 'classtext' => 'Medium'),
            'small' => array('code' => 'p120x120', 'position' => 7, 'classtext' => 'Small')
        );

        //$next = $noscroll ? "" : $next;
        $args = array_merge($sizes[$size], array('id' => $albumId, 'photos' => $photos, 'paging' => $album->paging, 'settings' => $album->settings));
        $template = $template == "" ? 'single-album' : $template;
        $output = self::capture($template, $args);
        return $output;
    }

    /**
     * Photo gallery 
     */
    public function getPhotosCyclic($albumId, $args, $options = array(), $paging = 25, $size = 'large', $template = "") {
        $album = new WalleriaAlbum();
        $album->getAlbumAjax($albumId, $args);
        $photos = $album->photos;
        $response = array('photos' => $photos, 'paging' => $album->paging);
        return $response;
    }

    /**
     * Embed a Facebook album
     * @param type $albumId
     * @param type $limit
     * @param type $template
     * @return type
     */
    public static function embedPhotosAjax($albumId, $args, $options = array(), $size = 'large', $paging = 25, $scroll = false, $template = "") {
        $tokenOpts = get_option('fwpg_accessToken');
        $token = $tokenOpts['access_token'];
        $album = new WalleriaAlbum();
        $album->getAlbumAjax($albumId, $args, $options);
        $photos = $album->photos;

        /**
         * 
         */
        $sizes = array(
            'large' => array('code' => 'p206x206', 'position' => 5, 'classtext' => 'Large'),
            'medium' => array('code' => 'p160x160', 'position' => 6, 'classtext' => 'Medium'),
            'small' => array('code' => 'p120x120', 'position' => 7, 'classtext' => 'Small')
        );
        //$args=isset($album->paging['next'])?$album->paging['cursors']:array();
        //$previous=isset($album->paging['previous'])?$album->paging['cursors']['previous']:"";
        $args = array_merge($sizes[$size], array('photos' => $photos, 'id' => $albumId, 'page' => $options['page'], 'cursors' => $album->paging, 'paging' => $paging, 'settings' => $album->settings));
        $template = $template == "" ? 'single-album-ajax' : $template;
        $output = self::capture($template, $args);
        return array('data' => $output, 'paging' => $args);
    }

    /**
     * Embed auser,page Facebook albums
     * @param type $userId
     * @param type $limit
     * @param type $template
     * @return type
     */
    public static function embedUserAlbums($userId, $limit, $paging = 25, $size = 'large', $scroll = true, $options = array(), $template = "") {
        $tokenOpts = get_option('fwpg_accessToken');
        $token = $tokenOpts['access_token'];
        $options['token'] = $token;
        $options['limit'] = $limit;
        $userAlb = new WalleriaUserAlbums($userId, $options);
        $albums = $userAlb->albums;
        /**
         * 
         */
        $sizes = array(
            'large' => array('code' => 'p206x206', 'position' => 5, 'classtext' => 'Large'),
            'medium' => array('code' => 'p160x160', 'position' => 6, 'classtext' => 'Medium'),
            'small' => array('code' => 'p120x120', 'position' => 7, 'classtext' => 'Small')
        );
        // $next = isset($userAlb->paging['next']) ? preg_replace('/access(.*&|.*)/', '', parse_url($userAlb->paging['next'], PHP_URL_QUERY)) : "";
        // $previous = isset($userAlb->paging['previous']) ? preg_replace('/access(.*&|.*)/', '', parse_url($userAlb->paging['previous'], PHP_URL_QUERY)) : "";
        $args = array_merge($sizes[$size], array('albums' => $albums, 'id' => $userId, 'scroll' => $scroll, 'cursors' => $userAlb->paging['cursors'], 'paging' => $paging, 'limit' => $options['limit'], 'toggle' => $options['toggle'], 'excl' => $options['excl']));
        $template = $template == "" ? 'albums' : $template;
        $output = self::capture($template, $args);
        return $output;
    }

    /**
     * Embed an object's stream
     */
    public static function embedAlbumsAjax($objectId, $args, $options = array(), $size = 'large', $template = "") {
        $tokenOpts = get_option('fwpg_accessToken');
        $token = $tokenOpts['access_token'];
        $options['token'] = $token;
        $attr = array_merge($options, $args);
        $userAlb = new WalleriaUserAlbums();
        // $args['limit']=10;print_r($args); 
        $userAlb->getAlbumsAjax($objectId, $attr);
        $albums = $userAlb->albums;
        /**
         * 
         */
        $sizes = array(
            'large' => array('code' => 'p206x206', 'position' => 5, 'classtext' => 'Large'),
            'medium' => array('code' => 'p160x160', 'position' => 6, 'classtext' => 'Medium'),
            'small' => array('code' => 'p120x120', 'position' => 7, 'classtext' => 'Small')
        );

        // $next = isset($userAlb->paging['next']) ? preg_replace('/access(.*&|.*)/', '', parse_url($userAlb->paging['next'], PHP_URL_QUERY)) : "";
        //$previous = isset($userAlb->paging['previous']) ? preg_replace('/access(.*&|.*)/', '', parse_url($userAlb->paging['previous'], PHP_URL_QUERY)) : "";
        $args = array_merge($sizes[$size], array('albums' => $albums, 'id' => $objectId, 'excl' => $options['excl']));
        $template = $template == "" ? 'albumsajax' : $template;
        $output = self::capture($template, $args);
        return array('data' => $output, 'paging' => isset($userAlb->paging['cursors']) ? $userAlb->paging['cursors'] : '');
    }

    /**
      }
      /**
     * Embed auser,page Facebook albums
     * @param type $userId
     * @param type $limit
     * @param type $template
     * @return type
     */
    public static function embedSpecificAlbums($albumIds, $paging, $size = 'large', $options = array(), $template = "") {
        $tokenOpts = get_option('fwpg_accessToken');
        $token = $tokenOpts['access_token'];
        $options['token'] = $token;
        $userAlb = new WalleriaUserAlbums();
        $userAlb->getSpecificAlbums($albumIds, $options);
        $albums = $userAlb->albums;


        /**
         * 
         */
        $sizes = array(
            'large' => array('code' => 'p206x206', 'position' => 5, 'classtext' => 'Large'),
            'medium' => array('code' => 'p160x160', 'position' => 6, 'classtext' => 'Medium'),
            'small' => array('code' => 'p120x120', 'position' => 7, 'classtext' => 'Small')
        );
        $args = array_merge($sizes[$size], array('albums' => $albums, 'paging' => $paging, 'toggle' => $options['toggle']));
        $template = $template == "" ? 'albums' : $template;
        $output = self::capture($template, $args);
        return $output;
    }

    /**
     * Embed an object's stream
     */
    public static function embedFeed($objectId, $limit, $options = array(), $template = "") {
        $tokenOpts = get_option('fwpg_accessToken');
        $token = $tokenOpts['access_token'];
        $options['limit'] = $limit;
        $options['token'] = $token;
        $feed = new WalleriaWall($objectId, $options);
        $template = $template == "" ? 'wall' : $template;
        $paging = isset($feed->paging['next']) ? parse_url($feed->paging['next'], PHP_URL_QUERY) : "";
        $args = array('feed' => $feed->stream, 'paging' => preg_replace('/access(.*&|.*)/', '', $paging), 'wallid' => $objectId, 'settings' => $feed->settings, 'objecttype' => $feed->type);
        $output = self::capture($template, $args);
        return $output;
    }

    /**
     * Embed an object's stream
     */
    public static function embedFeedAjax($objectId, $args, $options = array(), $template = "") {
        $tokenOpts = get_option('fwpg_accessToken');
        $token = $tokenOpts['access_token'];
        $options['token'] = $token;
        $feed = new WalleriaWall();
        $feed->getStreamAjax($objectId, $args, $options);
        $template = $template == "" ? 'wallajax' : $template;
        $paging = isset($feed->paging->next) ? parse_url($feed->paging->next, PHP_URL_QUERY) : "";
        $args = array('feed' => $feed->stream, 'paging' => preg_replace('/access(.*&|.*)/', '', $paging), 'settings' => $feed->settings);
        $output = self::capture($template, $args);
        return array('data' => $output, 'paging' => preg_replace('/access(.*&|.*)/', '', $paging));
    }

    /**
     * Embed comments
     */
    private function embedFeedComments($objectId, $args, $template = "") {

        $comments = new WalleriaComments($objectId, $args);
        $template = $template == "" ? 'comments' : $template;
        $args = array('comments' => $comments->comments, 'paging' => $comments->paging);
        $output = self::capture($template, $args);

        $paging = $comments->paging;
        return array('data' => $output, 'paging' => $paging);
    }

    /**
     * Renders a section of user display code.  The code is first checked for in the current theme display directory
     * before defaulting to the plugin
     * Call the function :	self::render ('template_name', array ('var1' => $var1, 'var2' => $var2));
     *
     * @param string $template_name Name of the template file (without extension)
     * @param string $vars Array of variable name=>value that is available to the display code (optional)
     * @param bool $callback In case we check we didn't find template we tested it one time more (optional)
     * @return void
     * */
    public static function render($template_name = '', $vars = array(), $callback = false) {
        foreach ($vars AS $key => $val) {
            $$key = $val;
        }


        // hook into the render feature to allow other plugins to include templates
        $custom_template = apply_filters('walleria_render_template', false, $template_name);

        if (( $custom_template != false ) && file_exists($custom_template)) {
            include ( $custom_template );
        } else if (file_exists(STYLESHEETPATH . "/walleria/$template_name.php")) {
            include (STYLESHEETPATH . "/walleria/$template_name.php");
        } else if (file_exists(WALLERIA_ABSPATH . "/Templates/Facebook/$template_name.php")) {
            include (WALLERIA_ABSPATH . "/Templates/Facebook/$template_name.php");
        } else if ($callback === true) {
            echo "<p>Rendering of template $template_name.php failed</p>";
        } else {
            //test without the "-template" name one time more
            $template_name = array_shift(explode('-', $template_name, 2));
            self::render($template_name, $vars, true);
        }
    }

    /**
     * Captures an section of user display code.
     *
     * @autor John Godley
     * @param string $template_name Name of the template file (without extension)
     * @param string $vars Array of variable name=>value that is available to the display code (optional)
     * @return void
     * */
    public static function capture($template_name, $vars = array()) {
        ob_start();
        self::render($template_name, $vars);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Define constants to be used for Walleria
     */
    private function define_constants() {
        /**
         * Walleria folder
         */
        define('WALLERIA_FOLDER', basename(dirname(__FILE__)));
        /**
         * Absolute path
         */
        define('WALLERIA_ABSPATH', trailingslashit(str_replace("\\", "/", WP_PLUGIN_DIR . '/' . WALLERIA_FOLDER)));
        /**
         * Walleria url
         */
        define('WALLERIA_URL', trailingslashit(plugins_url(WALLERIA_FOLDER)));
        /**
         * define slug
         */
        define('WALLERIA_SLUG', 'facebook-walleria');
        /**
         * 
         */
        define('WALLERIA_UPDATE_URL', 'http://zoxion.com/updates/walleria');
    }

    /**
     * Load stylesheets that will be used by Walleria
     * @param mixed $stylesheet The stylesheet to use for the design
     * @param array $opts array with fwpg_gallery key
     */
    public function loadStyles() {
        $opts = $this->options;
        $custom_style = '';
        /**
         * Allow plugins to override with an arraywih keys handler and path
         */
        $custom_style = apply_filters('walleria_custom_style', false, $custom_style);

        wp_register_style('facebook-walleria', WALLERIA_URL . 'css/facebook-walleria.css');
        wp_enqueue_style('facebook-walleria');

        if (false !== $custom_style && file_exists($custom_style['path'])) {
            wp_deregister_style($custom_style['handler']);
            wp_register_style($custom_style['handler'], $custom_style['path']);
            wp_enqueue_style($custom_style['handler']);
        } elseif (file_exists(STYLESHEETPATH . "/walleria/css/style.css")) {
            wp_enqueue_style('walleria-style', get_stylesheet_directory_uri() . "/walleria/css/style.css");
        } else {
            wp_enqueue_style('walleria-style', WALLERIA_URL . "Templates/Facebook/css/style.css");


            if ($opts['fwpg_gallery'] == 'Fancybox') {
                wp_deregister_style('fancybox');
                wp_register_style('fancybox', WALLERIA_URL . 'js/fancybox/jquery.fancybox-1.3.4.css', false, '', 'screen');
                wp_enqueue_style('fancybox');
            } elseif ($opts['fwpg_gallery'] == 'PrettyPhoto') {
                wp_deregister_style('prettyphoto');
                wp_register_style('prettyphoto', WALLERIA_URL . 'js/prettyPhoto/css/prettyPhoto.css', false, '', 'screen');
                wp_enqueue_style('prettyphoto');
            } elseif ($opts['fwpg_gallery'] == 'Photoswipe') {
                wp_deregister_style('photoswipe');
                wp_register_style('photoswipe', WALLERIA_URL . 'js/photoswipe/photoswipe.css', false, '', 'screen');
                wp_enqueue_style('photoswipe');
            }
        }
    }

    /**
     * register main walleribox script
     */
    function loadJS() {
        $opts = $this->options;

        if (!is_admin()) {
            $custom_scripts = "";
            /**
             * Allow plugins to override with an arraywih keys handler and path
             */
            $custom_script = apply_filters('walleria_custom_script', false, $custom_scripts);
            if (false !== $custom_script && file_exists($custom_script['path'])) {
                wp_deregister_script($custom_script['handler']);
                wp_register_script($custom_script['handler'], $custom_script['path']);
                wp_enqueue_script($custom_script['handler']);
            } elseif (file_exists(STYLESHEETPATH . "/walleria/js/walleria.js")) {
                wp_enqueue_style('walleria-style', get_stylesheet_directory_uri() . "/walleria/js/walleria.js");
            } else {

                if ($opts['fwpg_gallery'] == 'Fancybox') {
                    //deregister any fancybox
                    wp_deregister_script('fancybox');
                    //register fancybox
                    wp_register_script('fancybox', WALLERIA_URL . 'js/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'), '', $opts['fwpg_loadAtFooter]']);
                    //enqueue for use
                    wp_enqueue_script('fancybox');
                } elseif ($opts['fwpg_gallery'] == 'PrettyPhoto') {
                    //deregister 
                    wp_deregister_script('prettyphoto');
                    //register 
                    wp_register_script('prettyphoto', WALLERIA_URL . 'js/prettyPhoto/jquery.prettyPhoto.js', array('jquery'), '', $opts['fwpg_loadAtFooter']);
                    //enqueue for use
                    wp_enqueue_script('prettyphoto');
                } elseif ($opts['fwpg_gallery'] == 'Photoswipe') {
                    wp_deregister_script('klass');
                    wp_register_script('klass', WALLERIA_URL . 'js/photoswipe/lib/klass.min.js', false, '', 'screen');
                    wp_enqueue_script('klass');
                    wp_deregister_script('photoswipe');
                    wp_register_script('photoswipe', WALLERIA_URL . 'js/photoswipe/code.photoswipe.jquery-3.0.5.min.js', false, '', 'screen');
                    wp_enqueue_script('photoswipe');
                }

                wp_deregister_script('walleria');
                //re-register
                wp_register_script('walleria', WALLERIA_URL . 'js/walleria.js', array('jquery'), '', $opts['fwpg_loadAtFooter']);
                //enqueue walleria js
                wp_enqueue_script('walleria');
            }
            include_once WALLERIA_ABSPATH . 'Inc/text.php';
            //get array of settings
            $w = $this->options;

            $w['ajaxurl'] = admin_url('admin-ajax.php');
            $w['fwpg_url'] = WALLERIA_URL;
            unset($w['fwpg_apikey']);
            unset($w['fwpg_username']);
            unset($w['fwpg_purchaseCode']);
            unset($w['fwpg_appSecret']);
            unset($w['fwpg_accessToken']);
            $x = array_merge($w, $wordbase);
            wp_localize_script('walleria', 'fwpgsettings', $x);
            //wp_localize_script('walleria','intl', $wordbase);
        }
    }

    /**
     * Load text domain
     */
    public function loadTextDomain() {

        load_plugin_textdomain('facebook-walleria', false, WALLERIA_FOLDER . '/lang');
    }

    public function ajaxHooks() {
        /**
         * Ajax get comments
         */
        add_action('wp_ajax_nopriv_getcomments', array($this, 'getPostComments'));
        add_action('wp_ajax_getcomments', array($this, 'getPostComments'));
        /**
         * Ajax get stream
         */
        add_action('wp_ajax_nopriv_getstream', array($this, 'getFeedAjax'));
        add_action('wp_ajax_getstream', array($this, 'getFeedAjax'));
        /**
         * Ajax get albums
         */
        add_action('wp_ajax_nopriv_getalbums', array($this, 'getAlbumsAjax'));
        add_action('wp_ajax_getalbums', array($this, 'getAlbumsAjax'));
        /**
         * Ajax get albums
         */
        add_action('wp_ajax_nopriv_getphotos', array($this, 'getPhotosAjax'));
        add_action('wp_ajax_getphotos', array($this, 'getPhotosAjax'));
    }

    public function getPostComments() {
        $postid = trim($_POST['postid']);
        $args = $_POST['args'];
        echo json_encode($this->embedFeedComments($postid, $args));
        exit();
    }

    public function getFeedAjax() {
        $feedId = trim($_POST['id']);
        //to array
        parse_str(trim($_POST['args']), $args);
        $options['page'] = trim($_POST['page']);
        $options['type'] = trim($_POST['type']);
        echo json_encode($this->embedFeedAjax($feedId, $args, $options));
        exit();
    }

    public function getAlbumsAjax() {
        $id = trim($_POST['id']);
        $options['excl'] = trim($_POST['excl']);
        $args = isset($_POST['args']) ? $_POST['args'] : array();
        $args['limit'] = isset($_POST['limit']) ? $_POST['limit'] : 25;
        $args['page'] = $_POST['page'];
        $size = trim($_POST['size']);
        echo json_encode($this->embedAlbumsAjax($id, $args, $options, $size));
        exit();
    }

    public function getPhotosAjax() {
        $id = trim($_POST['id']);
        $args = isset($_POST['args']) ? $_POST['args'] : array();
        $args['limit'] = isset($_POST['limit']) ? $_POST['limit'] : 25;
        if (isset($args['before'])) {
            unset($args['before']);
        }
        $options['page'] = trim($_POST['page']);
        $size = trim($_POST['size']);
        echo json_encode($this->embedPhotosAjax($id, $args, $options, $size));
        exit();
    }

    public function getPhotoCyclicAjax() {
        $id = trim($_POST['id']);
        $args = trim($_POST['args']);

        echo json_encode($this->getPhotosCyclic($id, $args));
        exit();
    }

    private function default_options() {
        $walleriopts['fwpg_showTitle'] = 'on';
        $walleriopts['fwpg_titlePosition'] = 'inside';
        $walleriopts['fwpg_border'] = '';
        $walleriopts['fwpg_cyclic'] = 'on';
        $walleriopts['fwpg_borderColor'] = '#BBBBBB';
        $walleriopts['fwpg_closeHorPos'] = 'right';
        $walleriopts['fwpg_closeVerPos'] = 'top';
        $walleriopts['fwpg_paddingColor'] = '#FFFFFF';
        $walleriopts['fwpg_padding'] = '10';
        $walleriopts['fwpg_overlayShow'] = 'on';
        $walleriopts['fwpg_overlayColor'] = '#666666';
        $walleriopts['fwpg_overlayOpacity'] = '0.3';
        $walleriopts['fwpg_Opacity'] = 'on';
        $walleriopts['fwpg_SpeedIn'] = '500';
        $walleriopts['fwpg_SpeedOut'] = '500';
        $walleriopts['fwpg_SpeedChange'] = '300';
        $walleriopts['fwpg_easing'] = '';
        $walleriopts['fwpg_easingIn'] = 'swing';
        $walleriopts['fwpg_easingOut'] = 'swing';
        $walleriopts['fwpg_easingChange'] = 'easeInOutQuart';
        $walleriopts['fwpg_imageScale'] = 'on';
        $walleriopts['fwpg_enableEscapeButton'] = 'on';
        $walleriopts['fwpg_showCloseButton'] = 'on';
        $walleriopts['fwpg_centerOnScroll'] = 'on';
        $walleriopts['fwpg_hideOnOverlayClick'] = 'on';
        $walleriopts['fwpg_hideOnContentClick'] = '';
        $walleriopts['fwpg_loadAtFooter'] = 'on';
        $walleriopts['fwpg_frameWidth'] = '560';
        $walleriopts['fwpg_frameHeight'] = '340';
        $walleriopts['fwpg_callbackOnStart'] = '';
        $walleriopts['fwpg_callbackOnShow'] = '';
        $walleriopts['fwpg_callbackOnClose'] = '';
        $walleriopts['fwpg_galleryType'] = 'all';
        $walleriopts['fwpg_customExpression'] = '';
        $walleriopts['fwpg_nojQuery'] = '';
        $walleriopts['fwpg_jQnoConflict'] = 'on';
        $walleriopts['fwpg_uninstall'] = '';
        $walleriopts['fwpg_appId'] = '';
        $walleriopts['fwpg_appSecret'] = '';
        $walleriopts['fwpg_accessToken'] = '';
        $walleriopts['fwpg_showAdminError'] = true;
        $walleriopts['fwpg_sharePic'] = '';
        $walleriopts['fwpg_tokenTimeStamp'] = '';
        $walleriopts['fwpg_enableprivate'] = 0;
        $walleriopts['fwpg_cacheTime'] = 5;

        //which gallery to use                
        $walleriopts['fwpg_gallery'] = 'PrettyPhoto';
        add_option('walleria', $walleriopts);
    }

    public function update($obj) {

        if (empty($obj->checked))
            return $obj;
        $options = array('body' => array(
                'action' => 'check_update',
                'slug' => WALLERIA_SLUG,
                'version' => $obj->checked[WALLERIA_SLUG . '/' . WALLERIA_SLUG . '.php'],
                'purchasecode' => $this->options['fwpg_purchaseCode']
                ));
        $raw_response = wp_remote_post(WALLERIA_UPDATE_URL, $options);


        if (is_wp_error($raw_response) || 200 != wp_remote_retrieve_response_code($raw_response))
            return false;

        $response = maybe_unserialize(wp_remote_retrieve_body($raw_response));

        if (is_object($response) && !empty($response)) // Feed the update data into WP updater
            $obj->response[WALLERIA_SLUG . '/' . WALLERIA_SLUG . '.php'] = $response;

        return $obj;
    }

    /**
     * 
     * @param type $def
     * @param type $action
     * @param type $args
     * @return boolean|\WP_Error
     */
    public function pluginApiCall($def, $action, $args) {

        if ($args->slug != WALLERIA_SLUG)
            return false;

        // Get the current version
        $plugin_info = get_site_transient('update_plugins');
        $current_version = $plugin_info->checked[WALLERIA_SLUG . '/' . WALLERIA_SLUG . '.php'];
        $args->version = $current_version;

        $options = array(
            'body' => array(
                'action' => 'info',
                'slug' => WALLERIA_SLUG,
                'purchasecode' => $this->options['fwpg_purchaseCode']
            )
        );

        $request = wp_remote_post(WALLERIA_UPDATE_URL, $options);

        if (is_wp_error($request)) {
            $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
        } else {
            $res = unserialize($request['body']);

            if ($res === false)
                $res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
        }

        return $res;
    }

}

