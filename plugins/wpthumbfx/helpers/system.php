<?php
/*
Class: SystemAtomicPressHelper
System helper class
*/
class SystemAtomicPressHelper extends AtomicPressHelper {
    /* system path */
    public $path;
    /* system url */
    public $url;
    /* options */
    public $options;
    /* output */
    public $output;
    /* active */
    public $active;
    /* CSRF token */
    public $token;
    /* use old editor api for WP <= 3.2.1 */
    public $use_old_editor;
    /*
    Function: Constructor
    Class Constructor.
    */
    public function __construct($atomicpress) {
        parent::__construct($atomicpress);
        // init vars
        $this->path           = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', ABSPATH), '/');
        $this->url            = rtrim(site_url(), '/');
        $this->options        = $this['data']->create(get_option('atomicpress_options'));
        $this->active         = $this['request']->get('page', 'string') == 'atomicpress' || $this['request']->get('action', 'string') == 'atomicpress';
        $this->use_old_editor = version_compare(get_bloginfo('version'), '3.2.1', '<=');
    }
    /*
    Function: init
    Initialize system
    
    Returns:
    Void
    */
    public function init() {
        // set translations
        load_plugin_textdomain('atomicpress', false, plugin_basename($this["path"]->path('atomicpress:languages')));
        // get upload directory
        $upload = wp_upload_dir();
        // set paths
        $this['path']->register($this->path, 'site');
        $this['path']->register($this['path']->path('atomicpress:widgets'), 'widgets');
        $this['path']->register($this['path']->path('atomicpress:cache'), 'cache');
        $this['path']->register($this['path']->path('atomicpress:shortcode'), 'shortcode');
        $this['path']->register($upload['basedir'], 'media');
        // load widgets
        foreach ($this['path']->dirs('widgets:') as $name) {
            if ($file = $this['path']->path("widgets:{$name}/{$name}.php")) {
                require_once($file);
            }
        }
        add_action('wp_ajax_nopriv_atomicpress_render', array(
            $this,
            'ajaxRender'
        ));
        add_action('wp_ajax_atomicpress_render', array(
            $this,
            'ajaxRender'
        ));
        // add actions
        add_action('admin_init', array(
            $this,
            '_adminInit'
        ));
        add_action('admin_head', array(
            $this,
            '_adminHead'
        ));
        add_action('admin_menu', array(
            $this,
            '_adminMenu'
        ));
        add_action('wp_ajax_atomicpress', array(
            $this,
            '_adminView'
        ));
        // load system specific files
        require_once($this['path']->path('shortcode:available.php'));
        require_once($this['path']->path('shortcode:shortcodes.php'));
        //Add Shortcodes
        foreach (sc_list() as $shortcode => $params) {
            add_shortcode($shortcode, 'sc_' . $shortcode . '_shortcode');
        }
        // is admin or site
        if (is_admin()) {
            add_post_type_support('ATOMICPRESS', 'editor');
            // add widgets event
            $this['event']->bind('task:editor', array(
                $this,
                '_editor'
            ));
            // trigger event
            $this['event']->trigger('admin');
            // add notices
            if ($this->active) {
                add_action('admin_notices', array(
                    $this,
                    '_adminNotices'
                ));
            }
            add_action('media_buttons', array(
                $this,
                '_shortcode_editor_button'
            ), 100);
            add_action('admin_footer', array(
                $this,
                '_shortcode_editor_output'
            ));
            // add editor filters
            add_filter('tiny_mce_before_init', array(
                $this,
                '_tinymce'
            ));
            add_filter('teeny_mce_before_init', array(
                $this,
                '_tinymce'
            ));
        } else {
            // add jquery
            wp_enqueue_script('jquery');
            // add stylesheets/javascripts
            $this['asset']->addString('js', 'window["ATOMICPRESS_URL"]="' . $this['path']->url("atomicpress:") . '";');
            $this['asset']->addString('js', 'function apress_ajax_render_url(widgetid){ return "' . site_url('wp-admin') . '/admin-ajax.php?action=atomicpress_render&id="+widgetid}');
            $this['asset']->addFile('css', 'atomicpress:css/atomicpress.css');
            $this['asset']->addFile('js', 'atomicpress:js/jquery.plugins.js');
            if ($this->options->get('direction') == 'rtl') {
                $this['asset']->addFile('css', 'atomicpress:css/rtl.css');
            }
            // trigger event
            $this['event']->trigger('site');
            // add actions/shortcodes/filters
            add_action('wp_head', array(
                $this,
                '_siteHead'
            ));
            add_shortcode('atomicpress', array(
                $this,
                '_shortcode'
            ));
            add_filter('widget_text', 'do_shortcode');
            $this['event']->bind('widgetoutput', create_function('&$content', '$content=do_shortcode($content);'));
        }
    }
    /*
    Function: link
    Get link to system related resources.
    
    Parameters:
    $query - HTTP query options
    
    Returns:
    String
    */
    public function link($query = array()) {
        return $this->url . '/wp-admin/' . (isset($query['ajax']) ? 'admin-ajax.php?action=atomicpress&' : 'admin.php?page=atomicpress&') . http_build_query($query, '', '&');
    }
    /*
    Function: checkToken
    Checks CSRF token
    
    Returns:
    Boolean
    */
    public function checkToken($token) {
        return wp_verify_nonce($token, 'atomicpress-secure-token');
    }
    /*
    Function: saveOptions
    Save plugin options
    
    Returns:
    Void
    */
    public function saveOptions() {
        update_option('atomicpress_options', (string) $this->options);
    }
    /*
    Function: _adminInit
    Admin init actions
    
    Returns:
    Void
    */
    public function _adminInit() {
        if ($this->active) {
            $this->token = wp_create_nonce('atomicpress-secure-token');
            wp_enqueue_style('thickbox');
            wp_enqueue_style('editor-buttons');
            wp_enqueue_script('thickbox');
            wp_enqueue_script('editor');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('quicktags');
            wp_enqueue_script('jquery-ui-sortable');
            // execute task
            $task            = $this['request']->get('task', 'string');
            $this["version"] = ($path = $this['path']->path('atomicpress:atomicpress.xml')) && ($xml = simplexml_load_file($path)) ? (string) $xml->version[0] : '';
            $this->output    = $this['template']->render($task ? 'task' : 'dashboard', compact('task', 'version'));
        } else {
        }
    }
    /*
    Function: _adminNotices
    Admin notices action callback
    
    Returns:
    Void
    */
    public function _adminNotices() {
        // get atomicpress xml
        if ($xmlpath = $this['path']->path('atomicpress:atomicpress.xml')) {
            $xml = $this['dom']->create($xmlpath, 'xml');
            // update check
            if ($url = $xml->first('updateUrl')->text()) {
                // create check url
                $url  = sprintf('%s?application=%s&version=%s&format=raw', $url, 'atomicpress_wp', urlencode($xml->first('version')->text()));
                // only check once a day
                $hash = md5($url . date('Y-m-d'));
                if ($this['option']->get("update_check") != $hash) {
                    if ($request = $this['http']->get($url)) {
                        $this['option']->set("update_check", $hash);
                        $this['option']->set("update_data", $request['body']);
                    }
                }
                // decode response and set message
                if (($data = json_decode($this['option']->get("update_data"))) && $data->status == 'update-available') {
                    $update = $data->message;
                }
            }
        }
        // show notice
        if (!empty($update)) {
            echo '<div class="update-nag">' . $update . '</div>';
        }
        return false;
    }
    /*
    Function: _adminHead
    Admin head actions
    
    Returns:
    Void
    */
    public function _adminHead() {
        if ($this->active) {
            // cache writable ?
            if (!is_writable($this["path"]->path("cache:"))) {
                add_action('admin_notices', create_function('', "
                   echo '<div class=\"update-nag\"><strong>AtomicPress cache folder is not writable! Please check directory permissions.</strong><br />" . $this["path"]->path("cache:") . "</div>';
                    return false;
                "));
            }
            // add stylesheets/javascripts
            $this['asset']->addFile('css', 'atomicpress:css/admin.css');
            $this['asset']->addFile('js', 'atomicpress:js/admin.js');
            wp_tiny_mce(false, array(
                "height" => 150
            ));
        }
        // add stylesheets/javascripts
        $this['asset']->addFile('css', 'atomicpress:css/system.css');
        $this['asset']->addFile('js', 'atomicpress:js/topbox.js');
        $this['asset']->addFile('js', 'atomicpress:js/shortcodes.js');
        $this['asset']->addString('js', 'var atomicpressajax = "' . $this['system']->link(array(
            'ajax' => true
        )) . '";');
        // render assets stylesheets/javascripts
        echo $this['template']->render('assets');
    }
    /*
    Function: _adminMenu
    Admin menu actions
    
    Returns:
    Void
    */
    public function _adminMenu() {
        add_menu_page('', 'ThumbFx', 8, 'atomicpress', array(
            $this,
            '_adminView'
        ), $this['path']->url('atomicpress:images/atomicpress_16.png'));
    }
    /*
    Function: _adminView
    Render admin view
    
    Returns:
    Void
    */
    public function _adminView() {
        echo $this->output;
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            exit;
        }
    }
    /*
    Function: _siteHead
    Site head actions
    
    Returns:
    Void
    */
    public function _siteHead() {
        // render assets stylesheets/javascripts
        echo $this['template']->render('assets');
    }
    /*
    Function: _tinymce
    Tiny mce editor init callback
    
    Returns:
    Void
    */
    public function _tinymce($init) {
        if (version_compare($GLOBALS['wp_version'], 3.2, '<')) {
            $init['extended_valid_elements'] = (isset($init['extended_valid_elements']) ? $init['extended_valid_elements'] . ',' : '') . '@[data-lightbox],@[data-spotlight]';
        }
        if ($this->active) {
            $init['forced_root_block'] = "";
            $init['verify_html']       = false;
            if (!$this->use_old_editor) {
                $init['editor_selector']         = 'slide-content';
                $init['mode']                    = 'specific_textareas';
                $init['theme_advanced_buttons1'] = str_replace(',wp_more', '', $init['theme_advanced_buttons1']);
                $init['theme_advanced_disable']  = 'fullscreen';
            }
        }
        return $init;
    }
    /*
    Function: _editor
    Editor plugin callback
    
    Returns:
    Void
    */
    public function _editor() {
        printf('<p><strong>%s</strong></p>%s', _e('Widget:', 'atomicpress'), $this['field']->render('widget', 'widget_id', null, null, array(
            'id' => 'atomicpress_select_box',
            'class' => 'widefat'
        )));
    }
    /*
    Function: _shortcode
    Shortcode callback
    
    Returns:
    String
    */
    public function _shortcode($atts, $content = null, $code = '') {
        extract(shortcode_atts(array(
            'id' => null
        ), $atts));
        return is_numeric($id) ? $this['widget']->render($id) : '';
    }
    /*
    Function: _shortcode_editor_button
    Render Shortcode Button HTML on WP Editor
    
    Returns:
    Void
    */
    public function _shortcode_editor_button() {
        echo '<a href="#" class="shortcode-trigger" title="' . __('Insert shortcode', 'atom') . '"><img src="' . $this['path']->url('atomicpress:images/media-icon.png') . '" alt="" /></a>';
    }
    /*
    Function: _shortcode_editor_output
    Render Shortcode Editor HTML on WP Footer
    
    Returns:
    Void
    */
    public function _shortcode_editor_output() {
        // get shortcode xml
        $html_output = '<div id="sc-wrap" style="display:none">';
        $html_output .= '<select id="sc-generator-select" class="widefat">';
        $html_output .= '<option value="raw">' . __('Select shortcode', 'atom') . '</option>';
        foreach (sc_list() as $name => $shortcode):
            $html_output .= '<option value="' . $name . '">' . $shortcode['name'] . '</option>';
        endforeach;
        $html_output .= '</select>';
        $html_output .= '<div id="sc-generator-settings"></div>';
        $html_output .= '<input type="hidden" name="sc-generator-url" id="sc-generator-url" value="' . $this['path']->url('shortcode:') . '" />';
        $html_output .= '</div>';
        echo $html_output;
    }
    /*
    Function: __
    Retrieve translated strings
    
    Returns:
    String
    */
    public function __($string) {
        return __($string, "atomicpress");
    }
    /*
    Function: ajaxRender
    Get widget markup by ajax request
    
    Returns:
    Void
    */
    public function ajaxRender() {
        $output = isset($_GET["id"]) ? $this->atomicpress['widget']->render(intval($_GET["id"])) : "Missing widget id.";
        die($output);
    }
}