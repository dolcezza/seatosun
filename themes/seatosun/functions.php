<?php
// Override default Skeleton functions
function st_widgets_init() {
    
}
function st_footer() {
    
}

// Initialize the theme
global $wp_theme;
$wp_theme = new WordPressToolKitTheme();


// Theme functions get packaged into a class to avoid namespace collision
class WordPressToolKitTheme {
    /* -----------------------------------------------------
       Variables
       ----------------------------------------------------- */
    
    public $config;
    protected $cached_values = array();
    
    /* -----------------------------------------------------
       Theme Functions
       ----------------------------------------------------- */
    
    public function WordPressToolKitTheme() {
        $args = func_get_args();
        call_user_func_array(array(&$this, '__construct'), $args);
    }
    
    public function __construct() {
        // Define settings for use in various theme files and scripts 
        $this->define_theme_settings();
        
        // Compile CSS and JavaScript if necessary
        $this->add_action('send_headers', 'compile_scripts_and_styles');
        
        // Add functions that run on ini
        $this->add_action('init', 'init_actions');
        
        // Add admin-only functions that run on init
        $this->add_action('admin_init', 'admin_init_functions');
        
        // Register custom widgets
        $this->add_action('widgets_init', 'register_widgets');
        
        // Contact Form 7 MailChimp Integration
        if (function_exists('wpcf7_add_shortcode')) {
            $this->add_action('wpcf7_mail_sent', 'wpcf7_send_to_mailchimp', 1);
        }
    }
    
    public function define_theme_settings() {
        // The name of the main theme class that encapsulates theme-specific functions
        @define('THEME_CLASS', get_class());

        // The name of the theme (used for enabling localization)
        @define('THEME_NAME', get_class());
        
        // Define theme directorys and URLs for including files
        $theme_dir = trailingslashit(get_template_directory());
        $theme_url = trailingslashit(get_template_directory_uri());
        $child_theme_dir = trailingslashit(get_stylesheet_directory());
        $child_theme_url = trailingslashit(get_stylesheet_directory_uri());
        
        // Get settings from configuration files
        require_once($child_theme_dir . 'config/ThemeConfig.php');
        ThemeConfig::setFile($child_theme_dir . 'config/config.php');
        $this->config = ThemeConfig::getInstance();
        
        // Various directories and URLs for including theme files
        $this->config->theme_dir = $theme_dir;
        $this->config->theme_url = $theme_url;
        $this->config->child_theme_dir = $child_theme_dir;
        $this->config->child_theme_url = $child_theme_url;
        
        // Set paths and URLs for css, js, images, and include folders
        foreach (array('css', 'js', 'image', 'include') as $folder_name) {
            $relative_path = $this->config->{$folder_name . '_folder'};
            
            $this->config->{$folder_name . '_dir'} = $child_theme_dir . $relative_path;
            $this->config->{$folder_name . '_url'} = $child_theme_url . $relative_path;
        }
        
        // SoundCloud & YouTube API Settings
        $this->config->soundcloud_client_id = '1fd5a9c284017fbcf509fb18527cf267';
        $this->config->youtube_api_key = 'AI39si4-lUAdE4snqUuQQeR23S6suDpxwn75Obmxo1k5wo2yfZQsk4GaQ10YKcAfr-6jf_rl5ol9kYH_Zw0VYIZXnCJp5B7XJA';
    }
    
    public function init_actions() {
        // Enable various WordPress features for the current theme
        $this->add_theme_features();
        
        // Add / remove filter and action hooks
        $this->add_remove_hooks();
        
        // Register custom post types
        $this->register_post_types();
        
        // Register custom taxonomies
        $this->register_taxonomies();
        
        // Add custom scripts
        $this->add_action('wp_enqueue_scripts', 'enqueue_scripts');
        
        // Add custom styles
        $this->add_action('wp_enqueue_scripts', 'enqueue_styles');
        
        // Add custom meta boxes
        $this->add_meta_boxes();
        
        // Add shortcodes
        $this->register_shortcodes();
        
        // Modify search results based on theme settings
        $this->filter_search_results();
    }
    
    public function admin_init_functions() {
        // Add custom styles for admin pages
        $this->admin_css();
        
        // Add custom admin scripts
        $this->add_action('admin_print_footer_scripts', 'admin_print_footer_scripts', 99);
        
        // Add custom styles for TinyMCE editor
        add_editor_style('admin/css/editor.css');
    }
    
    public function add_theme_features() {
        // Add navigation menus
        if (function_exists('register_nav_menus')) {
            register_nav_menus(
                array( 
                    'footer_nav_left' => __('Footer Navigation (Left)', THEME_NAME),
                    'footer_nav_right' => __('Footer Navigation (Right)', THEME_NAME),
                )
            );
        }
        
        // Add sidebars
        if (function_exists('register_sidebar')) {
            register_sidebar(array(
                'name' => 'Left Sidebar',
                'id' => 'left_sidebar',
            ));
            register_sidebar(array(
                'name' => 'Right Sidebar',
                'id' => 'right_sidebar',
            ));
        }
        
        // Add support for post thumbnails
        add_theme_support('post-thumbnails');
        add_image_size('releases-archive-thumbnail', 145, 145, true);
        add_image_size('releases-archive-featured-thumbnail', 250, 250, true);
        add_image_size('releases-widget-thumbnail', 64, 64, true);
        add_image_size('videos-archive-thumbnail', 292, 180, true);
    }
    
    public function add_remove_hooks() {
        // Add custom classes to the body and post container elements
        $this->add_filter('post_class', 'additional_post_classes');
        $this->add_filter('body_class', 'additional_body_classes');
        
        // Add custom edit screen columns
        $this->add_filter('manage_posts_columns', 'add_edit_screen_columns', 10, 2);
        $this->add_action('manage_posts_custom_column', 'display_edit_screen_column', 10, 2);
        
        // Customize the "continue reading" link displayed after each excerpt
        $this->add_filter('excerpt_more', 'excerpt_more');
    }
    
    public function get_function($function) {
        if (!is_callable($function) && is_string($function)) {
            $function = array(&$this, $function);
        }
        
        if (is_callable($function)) {
            return $function;
        }
        
        $error_message = 'Could not find function with name "' . $function .'". Ensure that the function exists either in the global scope or inside the main theme class, and double-check your code for spelling errors';
        $wp_errors = new WP_Error();
        $wp_errors->add('function_not_found', $error_message);
        $this->log($wp_errors);
        
        return $wp_errors;
    }
    
    public function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $this->add_hook('action', $hook_name, $callback, $priority, $accepted_args);
    }
    
    public function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $this->add_hook('filter', $hook_name, $callback, $priority, $accepted_args);
    }
    
    public function add_hook($hook_type, $hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $callback = $this->get_function($callback);
        
        if (!is_wp_error($callback)) {
            if ($hook_type == 'action') {
                add_action($hook_name, $callback, $priority, $accepted_args);
            } else if ($hook_type == 'filter') {
                add_filter($hook_name, $callback, $priority, $accepted_args);
            }
        }
    }
    
    public function remove_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $this->remove_hook('action', $hook_name, $callback, $priority, $accepted_args);
    }
    
    public function remove_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $this->remove_hook('filter', $hook_name, $callback, $priority, $accepted_args);
    }
    
    public function remove_hook($hook_type, $hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $callback = $this->get_function($callback);
        
        if (!is_wp_error($callback)) {
            if ($hook_type == 'action') {
                remove_action($hook_name, $callback, $priority, $accepted_args);
            } else if ($hook_type == 'filter') {
                remove_filter($hook_name, $callback, $priority, $accepted_args);
            }
        }
    }
    
    public function add_shortcode($tag, $function) {
        $function = $this->get_function($function);
        
        if (!is_wp_error($function)) {
            add_shortcode($tag, $function);
        }
    }
    
    public function additional_post_classes($classes) {
        $classes[] = 'clearfix';
        return $classes;
    }
    
    public function additional_body_classes($classes) {
        $classes[] = 'clearfix';
        
        $page_template = $this->get_page_template();
        if ($page_template) {
            $classes[] = "template-$page_template";
        }
                
        return $classes;
    }
    
    public function register_post_types() {
        // Artists
        /*
        $labels = $this->populate_post_type_labels(array(
            'name' => 'Artists',
            'singular_name' => 'Artist'
        ));
        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => true,
            'supports' => array('title', 'editor', 'revisions', 'thumbnail')
        );
        register_post_type('seatosun_artist', $args);
        */
        
        // Releases
        $labels = $this->populate_post_type_labels(array(
            'name' => 'Releases',
            'singular_name' => 'Release'
        ));
        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
            'rewrite' => array(
                'slug' => 'releases',
            ),
        );
        register_post_type('seatosun_release', $args);
        
        // Videos
        $labels = $this->populate_post_type_labels(array(
            'name' => 'Videos',
            'singular_name' => 'Video'
        ));
        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
            'rewrite' => array(
                'slug' => 'videos',
            ),
        );
        register_post_type('seatosun_video', $args);
        
        // Slides
        $labels = $this->populate_post_type_labels(array(
            'name' => 'Slides'
        ));
        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => true,
            'supports' => array('title', 'editor', 'revisions')
        );
        register_post_type('seatosun_slide', $args);
    }
    
    public function register_taxonomies() {
        
    }
    
    public function populate_post_type_labels($labels) {
        return $this->populate_post_type_or_taxonomy_labels($labels);
    }
    
    public function populate_taxonomy_labels($labels) {
        return $this->populate_post_type_or_taxonomy_labels($labels);
    }
    
    public function populate_post_type_or_taxonomy_labels($labels) {
        if (!is_array($labels) && !is_string($labels)) {
            return null;
        }
        if (is_string($labels)) {
            $labels = array($labels);
        }
        
        $name = '';
        $name_lower = '';
        $singular_name = '';
        $singular_name_lower = '';
        
        if (isset($labels['name'])) {
            $name = $labels['name'];
            $name_lower = strtolower($name);
            
            if (!isset($labels['search_items'])) {
                $labels['search_items'] = "Search $name";
            }
            if (!isset($labels['popular_items'])) {
                $labels['popular_items'] = "Popular $name";
            }
            if (!isset($labels['all_items'])) {
                $labels['all_items'] = "All $name";
            }
            if (!isset($labels['not_found'])) {
                $labels['not_found'] = "No $name_lower found";
            }
            if (!isset($labels['not_found_in_trash'])) {
                $labels['not_found_in_trash'] = "No $name_lower found in trash";
            }
            if (!isset($labels['menu_name'])) {
                $labels['menu_name'] = $name;
            }
            if (!isset($labels['separate_items_with_commas'])) {
                $labels['separate_items_with_commas'] = "Separate $name_lower with commas";
            }
            if (!isset($labels['add_or_remove_items'])) {
                $labels['add_or_remove_items'] = "Add or remove $name_lower";
            }
            if (!isset($labels['choose_from_most_used'])) {
                $labels['choose_from_most_used'] = "Choose from most used $name_lower";
            }
        }
        
        if (isset($labels['singular_name'])) {
            $singular_name = $labels['singular_name'];
            $singular_name_lower = strtolower($singular_name);
            
            if (!isset($labels['add_new'])) {
                $labels['add_new'] = "Add New";
            }
            if (!isset($labels['add_new_item'])) {
                $labels['add_new_item'] = "Add New $singular_name";
            }
            if (!isset($labels['edit_item'])) {
                $labels['edit_item'] = "Edit $singular_name";
            }
            if (!isset($labels['update_item'])) {
                $labels['update_item'] = "Update $singular_name";
            }
            if (!isset($labels['new_item'])) {
                $labels['new_item'] = "New $singular_name";
            }
            if (!isset($labels['new_item_name'])) {
                $labels['new_item_name'] = $labels['new_item'];
            }
            if (!isset($labels['view_item'])) {
                $labels['view_item'] = "View $singular_name";
            }
            if (!isset($labels['parent_item'])) {
                $labels['parent_item'] = "Parent $singular_name";
            }
            if (!isset($labels['parent_item_colon'])) {
                $labels['parent_item_colon'] = $labels['parent_item'] . ':';
            }
        }
        
        return $labels;
    }
    
    public function add_meta_boxes() {
        $wpa_dir = $this->config->include_dir . 'wpalchemy/';
        $wpa_url = $this->config->include_url . 'wpalchemy/';
        $metabox_class_file = $wpa_dir . 'MetaBox.php';
        $metabox_media_helper = $wpa_dir . 'MediaAccess.php';
        
        if (!class_exists('WPAlchemy_MetaBox') && file_exists($metabox_class_file)) {
            include_once($metabox_class_file);
            
            if (file_exists($metabox_media_helper)) {
                include_once($metabox_media_helper);
                global $wpalchemy_media_access;
                $wpalchemy_media_access = new WPAlchemy_MediaAccess();
            }
            
            if (is_admin()) {
                wp_enqueue_style('wpalchemy-metabox', $wpa_url . 'metaboxes/meta.css');
            }
            
            global $wpa_metabox_dir;
            $wpa_metabox_dir = $wpa_dir . 'metaboxes/';
            
            include_once($wpa_metabox_dir . 'default-spec.php');
            // include_once($wpa_metabox_dir . 'artist-spec.php');
            include_once($wpa_metabox_dir . 'release-spec.php');
            include_once($wpa_metabox_dir . 'video-spec.php');
        }
    }
    
    public function enqueue_scripts() {
        /* ------------------------------------------------------------------
           Global scripts
           ------------------------------------------------------------------ */
        // Libraries
        
        // Plugins
        
        // Custom scripts
        
        /* ------------------------------------------------------------------
           Admin only
           ------------------------------------------------------------------ */
        if (is_admin()) {
            // Libraries
            
            // Plugins
            
            // Custom scripts
        }
        
        /* ------------------------------------------------------------------
           Non-admin only
           ------------------------------------------------------------------ */
        if (!is_admin()) {
            // Replace current version of jQuery with Google's hosted version
            global $wp_scripts;
            $jquery_version = $wp_scripts->registered['jquery']->ver;
            $protocol = $this->get_current_protocol();
            
            if ($jquery_version) {
                $jquery_url = $protocol . '//ajax.googleapis.com/ajax/libs/jquery/' . $jquery_version . '/jquery.min.js';
                
                wp_deregister_script('jquery');
                wp_register_script('jquery', $jquery_url, array(), $jquery_version, false);
            }
            
            // Libraries
            $this->enqueue_script('modernizr', $this->config->js_folder . 'libraries/modernizr.dev.min.js', array(), '2.0', false);
            if ($this->config->typekit_id) {
                $typekit_url = 'http://use.typekit.com/' . $this->config->typekit_id . '.js';
                $this->enqueue_script('typekit', $typekit_url, array(), null, false);
            }
            
            // Plugins
            $this->enqueue_script('js_plugins', $this->config->js_folder . 'plugins.js', array('jquery'), '1.3.19', true);
            $this->enqueue_style('js_plugins', $this->config->js_folder . 'plugins.css', array(), '1.3.19', 'all');
            
            // Custom scripts
            $this->enqueue_script('main_js', $this->config->js_folder . 'main.min.js', array('modernizr', 'jquery'), 'auto', true);
        }
    }
    
    public function enqueue_script($script_name, $script_path, $deps = array(), $version = null, $in_footer = false) {
        $script_url = $this->get_dynamic_theme_file_url($script_path);
        if ($script_url) {
            if ($version == 'auto') {
                $script_file = $this->get_dynamic_theme_file($script_path);
                $version = @filemtime($script_file);
            }
            
            wp_enqueue_script($script_name, $script_url, $deps, $version, $in_footer);
        }
    }
    
    public function enqueue_styles() {
        /* ------------------------------------------------------------------
           Global styles
           ------------------------------------------------------------------ */
        
        
        /* ------------------------------------------------------------------
           Admin only
           ------------------------------------------------------------------ */
        if (is_admin()) {
            
        }
        
        /* ------------------------------------------------------------------
           Non-admin only
           ------------------------------------------------------------------ */
        if (!is_admin()) {
            // Main stylesheet
            $this->enqueue_style('main_css', $this->config->css_folder . 'style.min.css', array(), 'auto', 'all');
            
            // Custom overrides
            $this->enqueue_style('override_css', 'style-override.css', array(), 'auto', 'all');
        }
    }
    
    public function enqueue_style($style_name, $style_path, $deps = array(), $version = null, $media = 'all') {
        $style_url = $this->get_dynamic_theme_file_url($style_path);
        if ($style_url) {
            if ($version = 'auto') {
                $style_file = $this->get_dynamic_theme_file($style_path);
                $version = @filemtime($style_file);
            }
            
            wp_enqueue_style($style_name, $style_url, $deps, $version, $media);
        }
    }
    
    public function admin_css() {
        if (is_admin()) {
            $css_path = 'admin/css/admin.css';
            $css_file = $this->config->child_theme_dir . $css_path;
            $css_file_url = $this->config->child_theme_url . $css_path;
            if (!file_exists($css_file)) {
                $css_file = $this->config->theme_dir . $css_path;
                $css_file_url = $this->config->theme_url . $css_path;
            }
            
            if (file_exists($css_file)) {
                wp_enqueue_style('custom-admin-css', $css_file_url, false, '1.0', 'all');
            }
        }
    }
    
    public function admin_print_footer_scripts() {
        ?>
        <script type="text/javascript">
            (function($) {
                $(function() {
                    // Add TinyMCE support to textareas in metaboxes
                    var i=1;
                    $('textarea.custom-editor').each(function(e) {
                        var id = $(this).attr('id');
                        
                        if (!id) {
                            id = 'custom-editor-' + i++;
                            $(this).attr('id',id);
                        }
                        
                        tinyMCE.execCommand('mceAddControl', false, id);
                    });
                    
                    // Live update metaboxes based on page template
                    var metaboxContainer = $('#_wptk_page_meta_metabox');
                    if (metaboxContainer.length) {
                        $('#page_template').live('change', function(event) {
                            var self = $(this);
                            var currentTemplate = self.val();
                            var allMetaboxes = metaboxContainer.find('.custom-metabox');
                            var normalMetaboxes = allMetaboxes.not('[data-template-file]');
                            var templateMetaboxes = allMetaboxes.filter('[data-template-file]');
                            var currentMetabox = metaboxContainer.find('[data-template-file="' + currentTemplate + '"]');
                            
                            if (currentMetabox.length) {
                                templateMetaboxes.hide();
                                currentMetabox.show();
                                metaboxContainer.show();
                            } else {
                                templateMetaboxes.hide();
                                
                                if (!normalMetaboxes.length) {
                                    metaboxContainer.hide();
                                }
                            }
                        }).change();
                    }
                    
                    // Add colorpicker support for metaboxes / options pages
                    if ($.isFunction($.fn.jPicker)) {
                        $('input[type="text"].colorpicker').jPicker({
                            window : {
                                position : {
                                    y : '25px'
                                }
                            },
                            images : {
                                clientPath : "<?php echo $this->config->theme_url ?>admin/jpicker/images/"
                            }
                        });
                    }
                    
                    // Add image upload support for metaboxes / options pages
                    if ($.isFunction(send_to_editor)) {
                        var targetInput;
                        var default_send_to_editor = window.send_to_editor;
                        
                        $('.media-upload-button').live('click', function(event) {
                            event.preventDefault();
                            var self = $(this);
                            targetInput = $('#' + self.attr('data-target'));                            
                            tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                        });
                        
                        $('.media-upload-clear-value').live('click', function(event) {
                            event.preventDefault();
                            var self = $(this);
                            self.siblings('.media-upload-input').val('');
                        });
                        
                        window.send_to_editor = function(html) {
                            if (targetInput) {
                                imgURL = $('img', html).attr('src');
                                targetInput.val(imgURL);
                                targetInput = null;
                                tb_remove();
                            } else {
                                default_send_to_editor(html);
                            }
                        }
                    }
                });
            })(jQuery);
        </script>
        <?php
    }
    
    public function register_shortcodes() {
        
    }
    
    public function add_edit_screen_columns($columns, $post_type) {
        
        return $columns;
    }
    
    public function display_edit_screen_column($column_name, $post_id) {
        
    }
    
    public function filter_search_results() {
        $custom_fields = $this->get_config_value('custom_fields_to_search');
        if (!empty($custom_fields)) {
            $this->add_filter('posts_join', 'posts_join');
            $this->add_filter('posts_where', 'posts_where');
            $this->add_filter('posts_groupby', 'posts_groupby');
        }
    }
    
    public function posts_join($join) {
        global $wpdb;

        if (is_search()) {
            $join .= " LEFT JOIN " . $wpdb->postmeta . " ON " . 
                $wpdb->posts . ".ID = " . $wpdb->postmeta . 
                ".post_id ";
        }

        return $join;
    }
    
    public function posts_where($where) {
        global $wpdb;

        if (is_search()) {
            $custom_fields = $this->get_config_value('custom_fields_to_search');
            $replacement_where = "($wpdb->posts.post_title LIKE $1) ";

            foreach ($custom_fields as $field) {
                $replacement_where .= "OR (($wpdb->postmeta.meta_key = '" . $field . "') AND ($wpdb->postmeta.meta_value LIKE $1)) ";
            }

            $where = preg_replace(
                "/\(\s*$wpdb->posts.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
                $replacement_where,
                $where);
        }    

        return $where;
    }
    
    public function posts_groupby($groupby) {
        global $wpdb;

        if (!is_search()) {
            return $groupby;
        }

        // we need to group on post ID
        $mygroupby = "{$wpdb->posts}.ID";

        if (preg_match("/$mygroupby/", $groupby)) {
            // grouping we need is already there
            return $groupby;
        }

        if (!strlen(trim($groupby))) {
            // groupby was empty, use ours
            return $mygroupby;
        }

        // groupby wasn't empty, append ours
        return $groupby . ", " . $mygroupby;
    }
    
    public function wpcf7_send_to_mailchimp($contact_form) {
        $form_data = $contact_form->posted_data;
        $send_to_mailchimp = $form_data['send-to-mailchimp'];
        if ($send_to_mailchimp) {
            $api_key = get_option('seatosun_mailchimp_api_key');
            $list_id = $form_data['mailchimp-list-id'];
            if ($api_key && $list_id) {
                $email_address = $form_data['contact-email'];
                $merge_vars = array(
                    'EMAIL' => $email_address
                );
                
                require_once($this->config->child_theme_dir . '/include/mailchimp/MCAPI.class.php');
                
                $api = new MCAPI($api_key);
                $retval = $api->listSubscribe($list_id, $email_address, $merge_vars, 'html', false);
            }
        }
    }
    
    
    /* -----------------------------------------------------
       Utility Functions
       ----------------------------------------------------- */
    
    public function log($message, $level = E_USER_NOTICE) {
        if (WP_DEBUG === true) {
            $backtrace = debug_backtrace();
            $caller_function = $backtrace[1]['function'];
            $error_message_pre_wrap = '::' . $caller_function . ':: ["';
            $error_message_post_wrap = '"]';
            
            if (is_wp_error($message)) {
                $errors = $message->get_error_messages();
                foreach ($errors as $error) {
                    trigger_error($error_message_pre_wrap . $error . $error_message_post_wrap, $level);
                }
            } else {
                if (is_array($message) || is_object($message)) {
                    $message = print_r($message, true);
                }
                trigger_error($error_message_pre_wrap . $message . $error_message_post_wrap, $level);
            }
        }
    }
    
    public function &get_cached_value($name) {
        if (!isset($this->cached_values[$name])) {
            $this->cached_values[$name] = null;
        }
        return $this->cached_values[$name];
    }
    
    public function set_cached_value($name, $value) {
        $this->cached_values[$name] = $value;
    }
    
    public function get_config_value($setting) {
        if (!isset($this->config[$setting])) {
            $this->config[$setting] = null;
        }
        return $this->config[$setting];
    }
    
    public function set_config_value($setting, $value) {
        $this->config[$setting] = $value;
    }
    
    public function get_html_tag() {
        $language_attributes = $this->get_standard_output('language_attributes');
        
        $html_tag = "<!--[if lt IE 7]><html class='no-js old-ie ie6' $language_attributes><![endif]-->"; 
        $html_tag .= "<!--[if IE 7]><html class='no-js old-ie ie7' $language_attributes><![endif]-->";
        $html_tag .= "<!--[if IE 8]><html class='no-js old-ie ie8' $language_attributes><![endif]-->";
        $html_tag .= "<!--[if IE 9]><html class='no-js ie9' $language_attributes><![endif]-->";
        $html_tag .= "<!--[if (gt IE 9)|!(IE)]><!--><html class='no-js' $language_attributes><!--<![endif]-->";
        
        return $html_tag;
    }
    
    public function html_tag() {
        echo $this->get_html_tag();
    }
    
    public function is_ajax() {
        $is_ajax = &$this->get_cached_value('is_ajax');
        if ($is_ajax == null) {
            $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        }
        
        return $is_ajax;
    }
    
    public function get_current_protocol() {
        $current_protocol = &$this->get_cached_value('current_protocol');
        if ($current_protocol == null) {
            $current_protocol = is_ssl() ? 'https://' : 'http://';
        }
        
        return $current_protocol;
    }
    
    public function is_valid_url($string) {
        return preg_match('|^(http(s)?:)?//[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $string);
    }
    
    public function make_url($url) {
        $url_parts = parse_url($url);
        
        if (!$url_parts['scheme'] && $url != '#') {
            $url_parts['scheme'] = 'http';
            $url = $this->unparse_url($url_parts);
        }
        
        return $url;
    }
    
    public function unparse_url($parsed_url) { 
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
        $pass     = ($user || $pass) ? "$pass@" : ''; 
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
        return "$scheme$user$pass$host$port$path$query$fragment"; 
    }
    
    public function remove_empty_p_tags($content) {
        $content = force_balance_tags($content);
        $content = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
        
        return $content;
    }
    
    public function get_blog_page_id() {
        return get_option('page_for_posts', true);
    }
    
    public function blog_page_id() {
        echo $this->get_blog_page_id();
    }
    
    public function get_blog_page_url() {
        return get_permalink($this->get_blog_page_id());
    }
    
    public function blog_page_url() {
        echo $this->get_blog_page_url();
    }
    
    public function get_blog_page_title($default = null) {
        $page_id = get_option('page_for_posts');
        $page_object = get_post($page_id);
        
        if ($page_object->post_type == 'post') {
            return $default;
        }
        
        return $page_object->post_title;
    }
    
    public function blog_page_title($default = null) {
        echo $this->get_blog_page_title($default);
    }
    
    public function update_url($site_url, $home_url = null) {
        if ($home_url === null) {
            $home_url = $site_url;
        }
        update_option('siteurl', $site_url);
        update_option('home', $home_url);
    }
    
    public function get_html_comment($value) {
        if (is_array($value) || is_object($value)) {
            $value = print_r($value, true);
        }
        
        return "<!-- " . $value . " -->";
    }
    
    public function html_comment($value) {
        echo $this->get_html_comment($value);
    }
    
    public function get_standard_output($function_to_call, $args = null) {
        if (is_callable($function_to_call)) {
            ob_start();
            
            if (!is_array($args)) {
                $args = array($args);
            }
            
            call_user_func_array($function_to_call, $args);
            $output = ob_get_contents();
            ob_end_clean();
            
            return $output;
        }
    }
    
    public function get_php_file_contents($file) {
        if (file_exists($file)) {
            ob_start();
            include_once($file);
            $output = ob_get_contents();
            ob_end_clean();
            
            return $output;
        }
    }
    
    public function get_all_files_in_directory($directory, $recursive = false) {
         $result = array();
         $handle =  opendir($directory);
         while ($datei = readdir($handle)) {
              if (($datei != '.') && ($datei != '..')) {
                   $file = $directory . $datei;
                   if (is_dir($file)) {
                        if ($recursive) {
                             $result = array_merge($result, $this->get_all_files_in_directory($file . '/'));
                        }
                   } else {
                        $result[] = $file;
                   } 
              }
         }
         closedir($handle);
         return $result;
    }
    
    public function compile_scripts_and_styles() {
        $this->compile_source_files('css');
        $this->compile_source_files('js');
    }
    
    public function compile_source_files($type) {
        $file_group_list = $this->get_config_value($type . '_files');
        if (!is_array($file_group_list)) {
            $file_group_list = $this->string_to_array($file_group_list);
        }
        
        foreach ($file_group_list as $current_group) {
            list($source_files, $dest_file) = $this->string_to_array($current_group, '=>');
            $source_files = $this->string_to_array($source_files, '|');
            $source_files = $this->update_source_file_paths($source_files, $type);
            $file_contents = '';
            $dest_file_found = false;
            $recompile_needed = false;
            
            foreach ($source_files as $source_file) {
                if (!$dest_file_found) {
                    $dest_file_found = true;
                    
                    if (!$dest_file) {
                        $dest_file = $this->remove_theme_dir_or_url($source_file);
                        $dest_file = $this->replace_file_extension($dest_file, $type);
                        
                        if (basename($source_file) == basename($dest_file)) {
                            $dest_file = $this->replace_file_extension($dest_file, "min.$type");
                        }
                        
                        $dest_file = str_replace(basename($source_file), basename($dest_file), $source_file);
                    } else {
                        $dest_file = $this->get_source_file_path($dest_file, $type);
                    }
                    
                    $recompile_needed = $this->source_file_needs_recompiled($source_files, $dest_file, $type);
                }
                
                if ($recompile_needed) {
                    $current_contents = $this->get_php_file_contents($source_file);
                    
                    if ($current_contents) {
                        if ($type == 'css') {
                            $source_file_extension = $this->get_file_extension($source_file);
                            $dest_file_extension = $this->get_file_extension($dest_file);
                            
                            if ($source_file_extension == 'less' && $dest_file_extension == 'css') {
                                require_once($this->config->include_dir . 'compilers/less.php');
                                $less = new lessc();
                                $current_contents = $less->parse($current_contents);
                            }
                            
                            if ($this->config->compress_css && $dest_file_extension == 'css') {
                                $current_contents = $this->compress_css($current_contents);
                            }
                        } else if ($type == 'js') {
                            if ($this->config->compress_js) {
                                $current_contents = $this->compress_js($current_contents);
                            }
                        }
                        
                        $file_contents .= $current_contents . PHP_EOL;
                    }
                    
                    if (!empty($dest_file) && !empty($file_contents)) {
                        file_put_contents($dest_file, $file_contents);
                    } else {
                        $errors = new WP_Error();
                        if (empty($dest_file)) {
                            $errors->add('destination_file_not_specified', 'Destination File Not Specified');
                        }
                        if (empty($file_contents)) {
                            $errors->add('file_contents_empty', 'File Contents Empty');
                        }
                        $this->log($errors);
                        return $errors;
                    }
                }
            }
        }
    }
    
    public function get_source_file_path($source_file, $type) {
        $source_folder = '';
        $source_file_dir = dirname($source_file);
        $source_path_is_absolute = (substr($source_file_dir, 0, 1) == '/');
        $source_path_is_relative = ($source_file_dir == '.' || !$source_path_is_absolute);
        if ($source_path_is_relative) {
            $source_folder = $this->config->{$type . '_folder'};
        }
        if ($source_path_is_absolute) {
            $source_file = substr($source_file, 1);
        }
        
        $source_file = $this->config->child_theme_dir . $source_folder . $source_file;
        
        return $source_file;
    }
    
    public function update_source_file_paths($source_files, $type) {
        if (is_array($source_files)) {
            for ($i = 0, $max = count($source_files); $i < $max; $i++) {
                $source_files[$i] = $this->get_source_file_path($source_files[$i], $type);
            }
            
            return $source_files;
        }
    }
    
    public function source_file_needs_recompiled($source, $dest, $type = null) {
        if (empty($dest)) {
            return false;
        }
        
        if (!$type) {
            $type = $this->get_file_extension($dest);
        }
        
        $compile_setting = $this->get_config_value('compile_'. $type);
        
        if (!$compile_setting) {
            return false;
        }
        
        // Never compile
        if ($compile_setting === false) {
            return false;
        }
        
        // Always compile
        if ($compile_setting === true) {
            return true;
        }
        
        // Compile automatically based on file modification times
        if ($compile_setting === 'auto') {
        
            $dest_mod_time = @filemtime($dest);
            
            if (!is_array($source)) {
                $source_mod_time = @filemtime($source);
            } else {
                $mod_times = array();
                
                foreach ($source as $file) {
                    $mod_times[] = @filemtime($file);
                }
                
                $source_mod_time = max($mod_times);
            }
            
            if ($source_mod_time > $dest_mod_time) {
                return true;
            }
        }
        
        return false;
    }
    
    public function compress_css($string) {
        if (!empty($string)) {
            // Remove comments
            $string = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $string);
            
            // Remove newlines
            $string = str_replace(array("\r\n", "\r", "\n"), ' ', $string);
            
            // Compress tabs and spaces
            $string = trim(preg_replace('/\s+/', ' ', $string));
            
            return $string;
        }
        
        return null;
    }
    
    public function compress_js($string) {
        require_once($this->config->include_dir . 'compilers/jsmin.php');
        
        $string = JSMin::minify($string);
        $string = trim($string);
        
        if (!empty($string)) {
            if (substr($string, 0, 1) !== ';') {
                $string = ';' . $string;
            }
            return $string;
        }
        
        return null;
    }
    
    public function get_dynamic_theme_file($path, $base_dir = null) {
        $path = $this->remove_theme_dir_or_url($path);
        if (substr($path, 0, 1) == '/') {
            $path = substr($path, 1);
        }
        if ($base_dir) {
            $path = trailingslashit($base_dir) . $path;
        }
                
        $parent_path = $this->config->theme_dir . $path;
        $child_path = $this->config->child_theme_dir . $path;
        
        if (file_exists($child_path)) {
            return $child_path;
        }
        
        if (file_exists($parent_path)) {
            return $parent_path;
        }
    }
    
    public function get_dynamic_theme_file_url($file) {
        if ($this->is_valid_url($file)) {
            return $file;
        }
        
        $file_path = $this->get_dynamic_theme_file($file);
        
        if ($file_path) {
            $file_url = str_replace(
                array($this->config->theme_dir, $this->config->child_theme_dir),
                array($this->config->theme_url, $this->config->child_theme_url),
                $file_path
            );
            return $file_url;
        }
    }
    
    public function remove_theme_dir_or_url($path) {
        $path = str_replace(array(
            $this->config->theme_dir, $this->config->theme_url,
            $this->config->child_theme_dir, $this->config->child_theme_url),
            '', $path);
        return $path;
    }
    
    public function get_file_extension($file) {
        $info = pathinfo($file);
        if (isset($info['extension'])) {
            return $info['extension'];
        }
    }
    
    public function strip_file_extension($file) {
        $length = strlen($file);
        $extension = $this->get_file_extension($file);
        $extension_pos = strrpos($file, $extension);
        
        if ($extension_pos !== false) {
            $file = substr($file, 0, $extension_pos - 1);
        }
        
        return $file;
    }
    
    public function replace_file_extension($file, $new_extension) {
        return $this->strip_file_extension($file) . '.' . $new_extension;
    }
    
    public function string_to_array($string, $sep = ',') {
        return array_map('trim', explode($sep, $string));
    }
    
    public function check_for_curl_support() {
        if (in_array('curl', get_loaded_extensions())) {
            return true;
        } else {
            $error_message = 'The cURL extension is not loaded';
            $wp_errors = new WP_Error();
            $wp_errors->add('curl_not_loaded', $error_message);
            $this->log($wp_errors);
            
            return $wp_errors;
        }
    }
    
    public function curl_exec_follow($ch, &$maxredirect = null) {
        $curl_enabled = $this->check_for_curl_support();
        if (is_wp_error($curl_enabled)) {
            return false;
        }
        
        $mr = $maxredirect === null ? 5 : intval($maxredirect);
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
            curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        } else {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            if ($mr > 0) {
                $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

                $rch = curl_copy_handle($ch);
                curl_setopt($rch, CURLOPT_HEADER, true);
                curl_setopt($rch, CURLOPT_NOBODY, true);
                curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
                curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
                do {
                    curl_setopt($rch, CURLOPT_URL, $newurl);
                    $header = curl_exec($rch);
                    if (curl_errno($rch)) {
                        $code = 0;
                    } else {
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                        if ($code == 301 || $code == 302) {
                            preg_match('/Location:(.*?)\n/', $header, $matches);
                            $newurl = trim(array_pop($matches));
                        } else {
                            $code = 0;
                        }
                    }
                } while ($code && --$mr);
                curl_close($rch);
                if (!$mr) {
                    if ($maxredirect === null) {
                        trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
                    } else {
                        $maxredirect = 0;
                    }
                    return false;
                }
                curl_setopt($ch, CURLOPT_URL, $newurl);
            }
        }
        return curl_exec($ch);
    }
    
    public function get_image_from_url($url) {
        $curl_enabled = $this->check_for_curl_support();
        if (is_wp_error($curl_enabled)) {
            return false;
        }
        
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        $return = $this->curl_exec_follow($process);
        curl_close($process);
              
        return $return;
    }
    
    public function cache_post_object($post_object = null) {
        if ($post_object == null) {
            global $post;
            $post_object = $post;
        }
        
        $this->set_cached_value('post', $post_object);
    }
    
    public function restore_post_object($post_object = null) {
        global $post;
        $cached_post = &$this->get_cached_value('post');
        
        if ($post_object) {
            $post = $post_object;
        } else if ($cached_post) {
            $post = $cached_post;
        }
        
        if ($post) {
            setup_postdata($post);
        }
    }
    
    public function get_option_value($option, $prefix = null) {
        if (!$prefix) {
            $prefix = $this->get_config_value('settings_prefix');
        }
        
        $value = get_option($prefix . $option);
        if (is_array($value)) {
            $value = $value['text_string'];
            return $value;
        }
        
        return null;
    }
    
    public function has_more_tag($post_object = null) {
        if (!$post_object) {
            global $post;
            $post_object = $post;
        }
        return (strpos($post_object->post_content, '<!--more-->') !== false);
    }
    
    public function excerpt_more($more) {
        $more = '... <a class="excerpt-more-link" href="' . get_permalink() . '"><i class="excerpt-more-arrow"></i>More</a>';
        return $more;
    }
    
    public function excerpt_length($length) {
        $length = 100;
        return $length;
    }
    
    public function get_page_template($args = array()) {
        global $wp_query,
               $post;
        
        $defaults = array(
            'page_id' => null,
            'get_top_level' => false
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);
        
        if (!$page_id){
            if ($get_top_level && $post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                $root = count($ancestors) - 1;
                $page_id = $ancestors[$root];
            } else {
                $page_id = $wp_query->get_queried_object_id();
            }
        }
        
        $page_template = get_post_meta($page_id, '_wp_page_template', true);
        
        if ($page_template) {
            $page_template = $this->strip_file_extension($page_template);
            $page_template = preg_replace('/^template-/', '', $page_template);
        } else {
            $page_template = 'default';
        }

        return $page_template;
    }
    
    public function is_page_template($template, $args = array()) {
        return ($template == $this->get_page_template($args));
    }
    
    public function is_sub_page() {
        global $post;
        
        $is_sub_page = (is_page() && $post->post_parent);
        
        return $is_sub_page;
    }
    
    public function get_paginate_links($args = array()) {
        global $wp_query;
        $big = 999999999;
        $defaults = array(
            'base' => str_replace($big, '%#%', get_pagenum_link($big)),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'type' => 'list'
        );
        $args = wp_parse_args($args, $defaults);
        return paginate_links($args);
    }
    
    public function paginate_links($args = array()) {
        echo $this->get_paginate_links($args);
    }
    
    public function get_copyright_text($args = array()) {
        /* compare_unit below accepts the following options:
         * 'y' (year), 'm' (month), 'd' (day), 'h' (hour), 'i' (minute), 's' (second)
         */
        $defaults = array(
            'date_format' => 'Y',
            'start_date' => '',
            'end_date' => 'now',
            'date_separator' => '&ndash;',
            'before_text' => 'Copyright &copy; ',
            'after_text' => '',
            'compare_unit' => 'y'
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);
        
        $copyright_text = $before_text;
        
        if (class_exists('DateTime')) {
            $start_date_timestamp = strtotime($start_date);
            $end_date_timestamp = strtotime($end_date);
            
            if ($start_date) {
                $start_date = new DateTime($start_date);
            }
            
            $end_date = new DateTime($end_date);
                        
            if ($start_date) {
                $append_start_date = false;
                
                if (method_exists($start_date, 'diff')) {
                    $date_diffs = get_object_vars($start_date->diff($end_date));
                    if ($date_diffs[$compare_unit]) {
                        $append_start_date = true;
                    }
                } else {
                    if ($end_date > $start_date) {
                        $append_start_date = true;
                    }
                }
                
                if ($append_start_date) {
                    $copyright_text .= $start_date->format($date_format) . $date_separator;
                }
            }
            
            $copyright_text .= $end_date->format($date_format);
            $copyright_text .= $after_text;
        } else {
            $copyright_text .= $start_date . $date_separator;
            
            if ($end_date > $start_date) {
                $copyright_text .= $end_date;
            }
        }
        
        return $copyright_text;
    }
    
    public function copyright_text($args = array()) {
        echo $this->get_copyright_text($args);  
    }
    
    public function get_image_caption($image_id = null, $with_formatting = true) {
        if ($image_id == null) {
            return false;
        }
        
        $image = get_posts(array(
            'post_type' => 'attachment',
            'post_status' => null,
            'include' => $image_id
        ));
        $image = $image[0];
        $caption = $image->post_excerpt;
        if ($with_formatting) {
            $caption = $this->format_image_caption($caption);
        }
        return $caption;
    }
    
    public function image_caption($image_id = null, $with_formatting = true) {
        if ($image_id == null) {
            return false;
        }
        
        echo $this->get_image_caption($image_id, $with_formatting);
    }
    
    public function format_image_caption($caption = null) {
        if (!empty($caption)) {
            return "<p class='wp-caption-text'>$caption</p>";
        }
        return false;
    }
    
    public function register_widgets() {
        register_widget('SeaToSun_Releases_Widget');
        register_widget('SeaToSun_Radio_Widget');
        register_widget('SeaToSun_Social_Widget');
        register_widget('SeaToSun_Newsletter_Widget');
        register_widget('SeaToSun_Store_Widget');
    }
    
    public function get_youtube_url_type($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $url_vars);
        $url_type = null;
        if (strpos($url, 'youtu.be') !== false) {
            $url_type = 'video_short_link';
        } else if (isset($url_vars['v'])) {
            $url_type = 'video';
        } else if (isset($url_vars['list'])) {
            $url_type = 'playlist';
        }
        
        return $url_type;
    }
    
    public function get_youtube_resource_id($url) {
        $url_type = $this->get_youtube_url_type($url);
        if (!$url_type) {
            return false;
        }
        
        $resource_id = null;
        parse_str(parse_url($url, PHP_URL_QUERY), $url_vars);
        
        if ($url_type == 'video' || $url_type == 'video_short_link') {
            if ($url_type == 'video_short_link') {
                // This is a single video using a youtu.be short link
                $resource_id = strstr($url, 'youtu.be/');
                $resource_id = str_replace('youtu.be/', '', $resource_id);
            } else {
                // This is a single video using a normal URL
                $resource_id = $url_vars['v'];
            }
        } else if ($url_type == 'playlist') {
            // This is a playlist
            $resource_id = $url_vars['list'];
        }
        
        return $resource_id;
    }
    
    public function get_youtube_embed_url($url) {
        $embed_url = 'http://www.youtube.com/embed/';
        $url_type = $this->get_youtube_url_type($url);
        $resource_id = $this->get_youtube_resource_id($url);
        
        if (!$url_type || !$resource_id) {
            return false;
        }
        
        if ($url_type == 'video' || $url_type == 'video_short_link') {
            $embed_url .= $resource_id;
        } else if ($url_type == 'playlist') {
            $embed_url .= 'videoseries?list=' . $resource_id;
        } else {
            return false;
        }
        
        return $embed_url;
    }
    
    public function youtube_embed_url($url) {
        echo $this->get_youtube_embed_url($url);
    }
    
    public function get_youtube_embed_code($url, $args = array()) {
        $args = wp_parse_args($args, array(
            'width' => 580,
            'height' => 326
        ));
        
        $embed_url = $this->get_youtube_embed_url($url);
        $embed_code = '<iframe width="' . $args['width'] . '" height="' . $args['height'] . '" src="' . $embed_url . '"frameborder="0" allowfullscreen></iframe>';
        
        return $embed_code;
    }
    
    public function youtube_embed_code($url) {
        echo $this->get_youtube_embed_code($url, $args = array());
    }
    
    public function get_youtube_video_or_playlist_data($url){
        $url_type = $this->get_youtube_url_type($url);
        $resource_id = $this->get_youtube_resource_id($url);
        $feed_name = null;
        
        if ($url_type == 'video' || $url_type == 'video_short_link') {
            $feed_name = 'videos';
        } else if ($url_type = 'playlist') {
            $feed_name = 'playlists';
        } else {
            return false;
        }
        
        $data = @file_get_contents('http://gdata.youtube.com/feeds/api/' . $feed_name . '/' . $resource_id . '?v=2&alt=jsonc');
        if ($data === false) {
            return false;
        }
        
        $obj = json_decode($data);
        
        return $obj->data;
    }
    
    public function get_youtube_data($url) {
        global $seatosun_video_meta;
        
        $embed_url = $this->get_youtube_embed_url($url);
        $video_data = $this->get_youtube_video_or_playlist_data($url);
        
        $title = $seatosun_video_meta->get_the_value('title');
        if (!$title) {
            $title = $video_data->title ? $video_data->title : get_the_title();
        }
        
        $description = $seatosun_video_meta->get_the_value('description');
        if (!$description) {
            $description = $video_data->description ? $video_data->description : get_the_content();
        }
        
        if (has_post_thumbnail()) {
            $thumbnail = get_the_post_thumbnail($post->ID, 'videos-archive-thumbnail');
        } else {
            $thumbnail = $video_data->thumbnail->hqDefault;
            if (!$thumbnail) {
                $thumbnail = $video_data->thumbnail->sqDefault;
            }
            if ($thumbnail) {
                $thumbnail = '<img class="attachment-videos-archive-thumbnail wp-post-image" src="' . $thumbnail . '" alt="" />';
            }
        }
        
        if ($video_data->items) {
            $duration = $video_data->items[0]->video->duration;
        } else {
            $duration = $video_data->duration;
        }
        if ($duration) {
            $timestamp_format = 'i:s';
            if ($duration >= 3600) {
                $timestamp_format = 'H:' . $timestamp_format;
            }
            
            $duration = gmdate($timestamp_format, $duration);
        }
        
        $return_data = array(
            'title' => $title,
            'description' => $description,
            'thumbnail' => $thumbnail,
            'duration' => $duration,
            'embed_url' => $embed_url,
        );
        
        return $return_data;
    }
}

/**
 * Custom Widget classes
 */
class SeaToSun_Releases_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'seatosun_releases_widget',
            'Sea to Sun Releases',
            array('description' => __('Display the most recent releases in the sidebar', 'text_domain'))
        );
    }

    public function form($instance) {
        // outputs the options form on admin
        $defaults = array(
            'title' => 'Releases',
            'limit' => 4
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
             <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of Releases to Show:'); ?></label>
             <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($instance['limit']); ?>" />
         </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        // processes widget options to be saved
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['limit'] = strip_tags($new_instance['limit']);

        return $instance;
    }

    public function widget($args, $instance) {
        if (is_archive() && get_post_type() == 'seatosun_release') {
            return null;
        }
        
        // displays the widget on the front-end
        extract($args);

        $title = apply_filters('widget_title', $instance['title']);
        $limit = $instance['limit'];
        
        global $wp_theme;
        $wp_theme->cache_post_object();
        
        $posts = get_posts(array(
            'post_type' => 'seatosun_release',
            'numberposts' => $limit,
            'meta_query' => array(
                array(
                    'key' => '_default_meta_show_in_widget',
                    'value' => 'true'
                ),
            ),
        ));
        
        if (!empty($posts)) :
            echo $before_widget;
            
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }
            
            global $seatosun_release_meta;
            
            foreach ($posts as $post) :
                setup_postdata($post);
                $seatosun_release_meta->the_meta($post->ID);
                ?>
                <div class="release">
                    <?php if (has_post_thumbnail($post->ID)) : ?>
                        <div class="release-image">
                            <?php echo get_the_post_thumbnail($post->ID, 'releases-widget-thumbnail'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="release-info">
                        <p class="title"><?php $seatosun_release_meta->the_value('title'); ?></p>
                        <p class="artist"><?php $seatosun_release_meta->the_value('artist'); ?></p>
                        <p class="year"><?php $seatosun_release_meta->the_value('year'); ?></p>
                    </div>
                </div>
                <?php
            endforeach;
            
            $wp_theme->restore_post_object();
            
            echo $after_widget;
        endif;
    }
}

class SeaToSun_Radio_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'seatosun_radio_widget',
            'Sea to Sun Radio',
            array('description' => __('Radio widget that plays a selected SoundCloud playlist', 'text_domain'))
        );
    }
    
    public function form($instance) {
        // outputs the options form on admin
        $defaults = array(
            'title' => 'S2S Radio',
            'playlist_url' => ''
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('playlist_url'); ?>"><?php _e('Playlist URL:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('playlist_url'); ?>" name="<?php echo $this->get_field_name('playlist_url'); ?>" type="text" value="<?php echo esc_attr($instance['playlist_url']); ?>" />
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        // processes widget options to be saved
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['playlist_url'] = strip_tags($new_instance['playlist_url']);
        
        return $instance;
    }
    
    public function widget($args, $instance) {
        global $wp_theme;
        // displays the widget on the front-end
        extract($args);
        
        $title = apply_filters('widget_title', $instance['title']);
        
        echo $before_widget;
        
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        
        // rest of widget output goes here
        $playlist_url = $instance['playlist_url'];
        $client_id = $wp_theme->config->soundcloud_client_id;
        $playlist_data_url = "http://api.soundcloud.com/resolve.json?url=$playlist_url&client_id=$client_id";
        $process = curl_init($playlist_data_url);
        $curl_options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30
        );
        curl_setopt_array($process, $curl_options);
        $playlist_data = $wp_theme->curl_exec_follow($process);
        curl_close($process);
        
        if ($playlist_data) :
            $playlist_data = json_decode($playlist_data, true);
            $tracks = $playlist_data['tracks'];
            if ($tracks) :
                ?>
                <div id="soundcloud-player-container">
                    <div class="track-info clearfix">
                        <span class="track-title"></span>
                        <span class="track-duration"></span>
                    </div>
                    <div class="player-controls clearfix">
                        <a class="track-list ir" href="#" title="Track List">Track List</a>
                        <a class="previous ir" href="#" title="Previous Track">Previous Track</a>
                        <a class="play-pause paused ir" href="#" title="Play">Play</a>
                        <a class="next ir" href="#" title="Next Track">Next Track</a>
                        <a class="volume ir" href="#" title="Volume">Volume</a>
                    </div>
                    <form id="soundcloud-track-id-list">
                        <?php foreach ($tracks as $track) : ?>
                            <input class="track-id" type="hidden" value="<?php echo $track['id']; ?>" />
                        <?php endforeach; ?>
                    </form>
                </div>
                <?php
            endif;
        endif;
        
        echo $after_widget;
    }
}

class SeaToSun_Social_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'seatosun_social_widget',
            'Sea to Sun Social Links',
            array('description' => __('Displays various social network links', 'text_domain'))
        );
    }
    
    public function form($instance) {
        // outputs the options form on admin
        $defaults = array(
            'title' => 'S2S Comm',
            'youtube_url' => '',
            'twitter_url' =>'',
            'soundcloud_url' => '',
            'instagram_url' => '',
            'facebook_url' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('youtube_url'); ?>"><?php _e('YouTube URL:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('youtube_url'); ?>" name="<?php echo $this->get_field_name('youtube_url'); ?>" type="text" value="<?php echo esc_attr($instance['youtube_url']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter_url'); ?>"><?php _e('Twitter URL:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_url'); ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" type="text" value="<?php echo esc_attr($instance['twitter_url']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('soundcloud_url'); ?>"><?php _e('SoundCloud URL:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('soundcloud_url'); ?>" name="<?php echo $this->get_field_name('soundcloud_url'); ?>" type="text" value="<?php echo esc_attr($instance['soundcloud_url']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('instagram_url'); ?>"><?php _e('Instagram URL:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('instagram_url'); ?>" name="<?php echo $this->get_field_name('instagram_url'); ?>" type="text" value="<?php echo esc_attr($instance['instagram_url']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('facebook_url'); ?>"><?php _e('Facebook URL:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook_url'); ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" type="text" value="<?php echo esc_attr($instance['facebook_url']); ?>" />
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        // processes widget options to be saved
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['youtube_url'] = strip_tags($new_instance['youtube_url']);
        $instance['twitter_url'] = strip_tags($new_instance['twitter_url']);
        $instance['soundcloud_url'] = strip_tags($new_instance['soundcloud_url']);
        $instance['instagram_url'] = strip_tags($new_instance['instagram_url']);
        $instance['facebook_url'] = strip_tags($new_instance['facebook_url']);
    
        return $instance;
    }
    
    public function widget($args, $instance) {
        // displays the widget on the front-end
        extract($args);
        extract($instance);
    
        $title = apply_filters('widget_title', $title);
    
        echo $before_widget;
    
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <div class="social-network-links clearfix">
            <?php if (!empty($youtube_url)) : ?>
                <a class="ir social-network-link youtube" href="<?php echo $youtube_url; ?>">YouTube</a>
            <?php endif; ?>
            <?php if (!empty($twitter_url)) : ?>
                <a class="ir social-network-link twitter" href="<?php echo $twitter_url; ?>">Twitter</a>
            <?php endif; ?>
            <?php if (!empty($soundcloud_url)) : ?>
                <a class="ir social-network-link soundcloud" href="<?php echo $soundcloud_url; ?>">SoundCloud</a>
            <?php endif; ?>
            <?php if (!empty($instagram_url)) : ?>
                <a class="ir social-network-link instagram" href="<?php echo $instagram_url; ?>">Instagram</a>
            <?php endif; ?>
            <?php if (!empty($facebook_url)) : ?>
                <a class="ir social-network-link facebook" href="<?php echo $facebook_url; ?>">Facebook</a>
            <?php endif; ?>
        </div>
        <?php
    
        echo $after_widget;
    }
}
 
class SeaToSun_Newsletter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'seatosun_newsletter_widget',
            'Sea to Sun Newsletter',
            array('description' => __('MailChimp newsletter subscription form', 'text_domain'))
        );
    }
    
    public function form($instance) {
        // outputs the options form on admin
        $defaults = array(
            'placeholder' => 'Type Your Email Here',
            'description' => 'Plug in with S2S Newsletter',
            'mailchimp_api_key' => '',
            'mailchimp_list_id' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php _e('Placeholder:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" type="text" value="<?php echo esc_attr($instance['placeholder']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo esc_attr($instance['description']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('mailchimp_api_key'); ?>"><?php _e('MailChimp API Key:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('mailchimp_api_key'); ?>" name="<?php echo $this->get_field_name('mailchimp_api_key'); ?>" type="text" value="<?php echo esc_attr($instance['mailchimp_api_key']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('mailchimp_list_id'); ?>"><?php _e('MailChimp List ID:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('mailchimp_list_id'); ?>" name="<?php echo $this->get_field_name('mailchimp_list_id'); ?>" type="text" value="<?php echo esc_attr($instance['mailchimp_list_id']); ?>" />
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        // processes widget options to be saved
        $instance = $old_instance;
        $instance['placeholder'] = strip_tags($new_instance['placeholder']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['mailchimp_api_key'] = strip_tags($new_instance['mailchimp_api_key']);
        $instance['mailchimp_list_id'] = strip_tags($new_instance['mailchimp_list_id']);
        
        update_option('seatosun_mailchimp_api_key', strip_tags($new_instance['mailchimp_api_key']));
        
        return $instance;
    }
    
    public function widget($args, $instance) {
        // displays the widget on the front-end
        extract($args);
        
        echo $before_widget;
        
        ?>
        <form action="">
            <input type="email" class="email" placeholder="<?php echo $instance['placeholder']; ?>" />
            <span class="description"><?php echo $instance['description']; ?></span>
            <input type="submit" value="Submit" />
        </form>
        <?php
        
        echo $after_widget;
    }
}

class SeaToSun_Store_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'seatosun_store_widget',
            'Sea to Sun Store',
            array('description' => __('Store advertisement area', 'text_domain'))
        );
    }
    
    public function form($instance) {
        // outputs the options form on admin
        $defaults = array(
            'title' => 'S2S Store',
            'description' => 'Coming Soon',
            'mailchimp_api_key' => '',
            'mailchimp_list_id' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo esc_attr($instance['description']); ?>" />
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        // processes widget options to be saved
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        
        return $instance;
    }
    
    public function widget($args, $instance) {
        // displays the widget on the front-end
        extract($args);
        extract($instance);
        
        echo $before_widget;
        
        ?>
        <div class="widget-inner">
            <?php
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }
            ?>
            <?php if (!empty($description)) : ?>
                <p class="description"><?php echo $description; ?></p>
            <?php endif; ?>
            
        <?php
        
        echo $after_widget;
    }
}



/**
 * Extends WordPress's native Walker_Nav_Menu class to add enable more targeted
 * styling through the use of additional conditional classes
 */
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
    protected $num_menu_items = array();
    
    protected function get_num_menu_items($theme_location) {
        if (!isset($this->num_menu_items[$theme_location])) {
            $all_menu_locations = get_nav_menu_locations();
            $menu_id = $all_menu_locations[$theme_location];
            $this->num_menu_items[$theme_location] = count(wp_get_nav_menu_items($menu_id));
        }
        return $this->num_menu_items[$theme_location];
    }
    
    function display_element($element, &$children_elements, $max_depth, $depth=0, $args, &$output) {
        $id_field = $this->db_fields['id'];
        if (!empty($children_elements[$element->$id_field])) { 
            $element->classes[] = 'sub-menu-container';
        }
        
        if ($depth === 0) {
            $element->classes[] = 'top-level';
        } else if ($depth > 0) {
            $element->classes[] = 'sub-level';
        }
        
        $element->classes[] = "menu-order-$element->menu_order";
        
        $num_menu_items = $this->get_num_menu_items($args[0]->theme_location);
        if ($element->menu_order === 1) {
            $element->classes[] = 'first';
        } else if ($element->menu_order === $num_menu_items) {
            $element->classes[] = 'last';
        }
        
        $url = $element->url;
        $home_url = get_home_url();
        $site_url = get_site_url();
        $is_external_link = !empty($url) && strpos($url, $home_url) === false && strpos($url, $site_url) === false;
        
        if ($is_external_link) {
            $element->classes[] = 'external';
            $element->title .= '<span class="external-link-indicator"></span>';
        }
        
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}